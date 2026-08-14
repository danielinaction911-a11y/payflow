<?php

namespace App\Livewire\Transfer;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transfer;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TransferForm extends Component
{
    public string $step = 'search'; // search -> pin -> success

    // --- Recipient search ---
    public string $query = '';
    public ?int $recipientId = null;

    // --- Transfer details ---
    public string $amount = '';
    public string $description = '';

    // --- PIN ---
    public string $pin = '';
    public string $newPin = '';
    public string $newPinConfirmation = '';

    public ?array $successData = null;

    public bool $accessBlocked = false;
    public string $blockedMessage = '';

    #[Computed]
    public function searchResults()
    {
        $term = trim($this->query);

        if ($this->recipientId || mb_strlen($term) < 2) {
            return collect();
        }

        return User::query()
            ->where('id', '!=', auth()->id())
            ->where(function ($q) use ($term) {
                $like = '%' . $term . '%';
                $q->where('username', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('name', 'like', $like);
            })
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function recipient()
    {
        return $this->recipientId ? User::find($this->recipientId) : null;
    }

    #[Computed]
    public function availableBalance(): float
    {
        return (float) (auth()->user()->balance ?? 0);
    }

    #[Computed]
    public function pinRequired(): bool
    {
        return (bool) setting('require_pin_for_transfer', true);
    }

    #[Computed]
    public function userHasPin(): bool
    {
        return ! empty(auth()->user()->transaction_pin);
    }

    #[Computed]
    public function canCreatePin(): bool
    {
        return (bool) setting('create_withdrawal_pin', true);
    }

    public function mount(): void
    {
        $this->checkAccess();
    }

    protected function checkAccess(): void
    {
        $user = Auth::user();

        if ($user->transfer_status === 'disabled') {
            $this->accessBlocked = true;
            $this->blockedMessage = $user->transfer_message ?: 'Transfers are currently disabled for your account. Please contact support.';
            return;
        }

        $this->accessBlocked = false;
        $this->blockedMessage = '';
    }

    public function updatedQuery(): void
    {
        // typing a fresh search always clears any previously selected recipient
        $this->recipientId = null;
        $this->resetErrorBag('recipientId');
    }

    public function selectRecipient(int $id): void
    {
        if ($id === auth()->id()) {
            $this->addError('recipientId', 'You cannot send money to yourself.');
            return;
        }

        $this->recipientId = $id;
        $this->query = '';
        $this->resetErrorBag('recipientId');
    }

    public function changeRecipient(): void
    {
        $this->recipientId = null;
        $this->query = '';
    }

    public function proceedToPin(): void
    {
        $this->checkAccess();
        if ($this->accessBlocked) {
            return;
        }

        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        if (! $this->recipient || $this->recipient->id === auth()->id()) {
            $this->addError('recipientId', 'Please search and select a recipient to continue.');
            return;
        }

        $min = (float) setting('min_transfer_amount', 1);
        $max = (float) setting('max_transfer_amount', 1000000);

        if ((float) $this->amount < $min || (float) $this->amount > $max) {
            $this->addError('amount', 'Amount must be between ' . money_format($min) . ' and ' . money_format($max) . '.');
            return;
        }

        if ((float) $this->amount > $this->availableBalance) {
            $this->addError('amount', 'Insufficient balance. Your available balance is ' . money_format($this->availableBalance) . '.');
            return;
        }

        // Check per-user transfer limits (daily / weekly / monthly)
        if (! $this->passesTransferLimits((float) $this->amount)) {
            return;
        }

        if (! $this->pinRequired) {
            $this->processTransfer();
            return;
        }

        $this->step = 'pin';
    }

    public function backToSearch(): void
    {
        $this->step = 'search';
    }

    public function createPin(): void
    {
        $this->validate([
            'newPin' => 'required|digits:4',
            'newPinConfirmation' => 'required|digits:4|same:newPin',
        ], [
            'newPinConfirmation.same' => 'PIN confirmation does not match.',
        ]);

        auth()->user()->update([
            'transaction_pin' => Hash::make($this->newPin),
            'pin_update_at' => now(),
        ]);

        $this->newPin = '';
        $this->newPinConfirmation = '';

        // Re-check computed props and continue straight to confirming the transfer
        unset($this->userHasPin);
        $this->pin = '';
    }

    public function submit(): void
    {
        // final checkpoint right before funds move
        $this->checkAccess();
        if ($this->accessBlocked) {
            $this->step = 'search';
            $this->addError('submit', $this->blockedMessage);
            return;
        }

        if ($this->pinRequired) {
            $this->validate(['pin' => 'required|digits:4']);

            if (! $this->userHasPin) {
                $this->addError('pin', 'You do not have a transaction PIN set.');
                return;
            }

            if (! Hash::check($this->pin, auth()->user()->transaction_pin)) {
                $this->addError('pin', 'Incorrect transaction PIN.');
                return;
            }
        }

        $this->processTransfer();
    }

    protected function processTransfer(): void
    {
        if (! $this->recipientId) {
            $this->addError('submit', 'No recipient selected.');
            $this->step = 'search';
            return;
        }

        $amount = (float) $this->amount;

        try {
            DB::beginTransaction();

            // Lock both rows for the duration of the transaction to prevent
            // a race condition where two simultaneous transfers/withdrawals
            // could both read a stale balance and overdraw the account.
            $sender = User::where('id', auth()->id())->lockForUpdate()->first();
            $recipient = User::where('id', $this->recipientId)->lockForUpdate()->first();

            if (! $recipient || $recipient->id === $sender->id) {
                throw new \RuntimeException('Invalid recipient.');
            }

            if ($sender->transfer_status === 'disabled') {
                throw new \RuntimeException($sender->transfer_message ?: 'Transfers are currently disabled for your account.');
            }

                // Re-check transfer limits while the sender row is locked to avoid races
                if (! $this->passesTransferLimits((float) $this->amount, $sender)) {
                    throw new \RuntimeException('This transfer would exceed your configured transfer limits.');
                }

            if ($amount > (float) $sender->balance) {
                throw new \RuntimeException('Insufficient balance.');
            }

            $reference = 'TRF-' . strtoupper(Str::random(10));

            $sender->decrement('balance', $amount);
            $recipient->increment('balance', $amount);

            $note = $this->description !== '' ? ": {$this->description}" : '';

            // Debit leg — sender
            app(TransactionService::class)->create([
                'user_id' => $sender->id,
                'reference' => $reference . '-S',
                'amount' => $amount,
                'fee' => 0,
                'currency' => 'USD',
                'type' => TransactionType::TransferOut,
                'direction' => TransactionDirection::Debit,
                'description' => "Transfer to {$recipient->username}{$note}",
                'status' => TransactionStatus::Completed,
                'metadata' => [
                    'transfer_reference' => $reference,
                    'recipient_id' => $recipient->id,
                    'recipient_username' => $recipient->username,
                ],
            ]);

            // Credit leg — recipient
            app(TransactionService::class)->create([
                'user_id' => $recipient->id,
                'reference' => $reference . '-R',
                'amount' => $amount,
                'fee' => 0,
                'currency' => 'USD',
                'type' => TransactionType::TransferIn,
                'direction' => TransactionDirection::Credit,
                'description' => "Transfer from {$sender->username}{$note}",
                'status' => TransactionStatus::Completed,
                'metadata' => [
                    'transfer_reference' => $reference,
                    'sender_id' => $sender->id,
                    'sender_username' => $sender->username,
                ],
            ]);

            Transfer::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $amount,
                'description' => $this->description ?: null,
                'reference' => $reference,
                'status' => 'completed',
            ]);

            DB::commit();

            $this->successData = [
                'amount' => $amount,
                'recipient_name' => $recipient->name ?? $recipient->username,
                'recipient_username' => $recipient->username,
                'reference' => $reference,
            ];

            $this->step = 'success';
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            $this->addError('submit', $e->getMessage() ?: 'Something went wrong while processing your transfer. Please try again.');
            $this->step = $this->pinRequired ? 'pin' : 'search';
        }
    }

    /**
     * Check user's daily/weekly/monthly transfer usage against their limits.
     * Returns true if the requested amount is allowed, otherwise adds an error and returns false.
     */
    protected function passesTransferLimits(float $amount, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        $statuses = ['pending', 'completed'];

        $todaySum = (float) $user->sentTransfers()->whereIn('status', $statuses)->where('created_at', '>=', Carbon::now()->startOfDay())->sum('amount');
        $weekSum = (float) $user->sentTransfers()->whereIn('status', $statuses)->where('created_at', '>=', Carbon::now()->startOfWeek())->sum('amount');
        $monthSum = (float) $user->sentTransfers()->whereIn('status', $statuses)->where('created_at', '>=', Carbon::now()->startOfMonth())->sum('amount');

        // Daily
        if ((float) ($user->daily_transfer_limit ?? 0) > 0 && ($todaySum + $amount) > (float) $user->daily_transfer_limit) {
            $this->addError('amount', 'This transfer would exceed your daily transfer limit of ' . money_format($user->daily_transfer_limit) . '. You have transferred ' . money_format($todaySum) . ' today.');
            return false;
        }

        // Weekly
        if ((float) ($user->weekly_transfer_limit ?? 0) > 0 && ($weekSum + $amount) > (float) $user->weekly_transfer_limit) {
            $this->addError('amount', 'This transfer would exceed your weekly transfer limit of ' . money_format($user->weekly_transfer_limit) . '. You have transferred ' . money_format($weekSum) . ' this week.');
            return false;
        }

        // Monthly
        if ((float) ($user->monthly_transfer_limit ?? 0) > 0 && ($monthSum + $amount) > (float) $user->monthly_transfer_limit) {
            $this->addError('amount', 'This transfer would exceed your monthly transfer limit of ' . money_format($user->monthly_transfer_limit) . '. You have transferred ' . money_format($monthSum) . ' this month.');
            return false;
        }

        return true;
    }

    public function startOver(): void
    {
        $this->reset(['step', 'query', 'recipientId', 'amount', 'description', 'pin', 'newPin', 'newPinConfirmation', 'successData']);
        $this->checkAccess();
        $this->step = 'search';
    }

    public function render()
    {
        return view('livewire.transfer.transfer-form')->layout('components.layouts.app', [
            'title' => 'Transfer Money',
        ]);
    }
}