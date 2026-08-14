<?php

namespace App\Livewire\Requests;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\MoneyRequest;
use App\Models\Notification;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class RequestMoney extends Component
{
    public string $step = 'search'; // search -> success

    // --- Recipient search (who we're requesting FROM) ---
    public string $query = '';
    public ?int $recipientId = null;

    // --- Request details ---
    public string $amount = '';
    public string $message = '';

    public ?array $successData = null;

    // --- Pay flow (for incoming requests) ---
    public ?int $payingRequestId = null;
    public bool $showPayModal = false;
    public string $pin = '';
    public string $newPin = '';
    public string $newPinConfirmation = '';
    public ?string $payError = null;

    // --- Decline flow ---
    public ?int $decliningRequestId = null;

    #[Computed]
    public function searchResults()
    {
        $term = trim($this->query);

        if ($this->recipientId || mb_strlen($term) < 2) {
            return collect();
        }

        return User::query()
            ->where('id', '!=', Auth::id())
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
    public function incomingRequests()
    {
        return MoneyRequest::with('requester')
            ->where('recipient_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    #[Computed]
    public function availableBalance(): float
    {
        return (float) (Auth::user()->balance ?? 0);
    }

    #[Computed]
    public function payingRequest()
    {
        return $this->payingRequestId ? MoneyRequest::find($this->payingRequestId) : null;
    }

    #[Computed]
    public function pinRequired(): bool
    {
        return (bool) setting('require_pin_for_transfer', true);
    }

    #[Computed]
    public function userHasPin(): bool
    {
        return ! empty(Auth::user()->transaction_pin);
    }

    #[Computed]
    public function canCreatePin(): bool
    {
        return (bool) setting('create_withdrawal_pin', true);
    }

    public function updatedQuery(): void
    {
        $this->recipientId = null;
        $this->resetErrorBag('recipientId');
    }

    public function selectRecipient(int $id): void
    {
        if ($id === Auth::id()) {
            $this->addError('recipientId', 'You cannot request money from yourself.');
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

    public function sendRequest(): void
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'message' => 'nullable|string|max:255',
        ]);

        if (! $this->recipient || $this->recipient->id === Auth::id()) {
            $this->addError('recipientId', 'Please search and select who you want to request from.');
            return;
        }

        $min = (float) setting('min_transfer_amount', 1);
        $max = (float) setting('max_transfer_amount', 1000000);

        if ((float) $this->amount < $min || (float) $this->amount > $max) {
            $this->addError('amount', 'Amount must be between ' . money_format($min) . ' and ' . money_format($max) . '.');
            return;
        }

        $request = MoneyRequest::create([
            'requester_id' => Auth::id(),
            'recipient_id' => $this->recipient->id,
            'amount' => (float) $this->amount,
            'message' => $this->message ?: null,
            'expires_at' => now()->addDays(7),
            'status' => 'pending',
        ]);

        Notification::create([
            'user_id' => $this->recipient->id,
            'title' => 'New money request',
            'body' => Auth::user()->name . ' requested ' . money_format($request->amount) . ' from you.',
            'type' => 'info',
            'is_read' => false,
        ]);

        $this->successData = [
            'amount' => $request->amount,
            'recipient_name' => $this->recipient->name ?? $this->recipient->username,
            'recipient_username' => $this->recipient->username,
        ];

        $this->step = 'success';
    }

    public function startOver(): void
    {
        $this->reset(['step', 'query', 'recipientId', 'amount', 'message', 'successData']);
        $this->step = 'search';
        unset($this->incomingRequests);
    }

    // ===== Pay incoming request =====

    public function openPayModal(int $requestId): void
    {
        $request = MoneyRequest::find($requestId);

        if (! $request || $request->recipient_id !== Auth::id() || $request->status !== 'pending') {
            return;
        }

        if ((float) $request->amount > $this->availableBalance) {
            $this->payError = 'Insufficient balance to pay this request. Your available balance is ' . money_format($this->availableBalance) . '.';
        } else {
            $this->payError = null;
        }

        $this->payingRequestId = $requestId;
        $this->pin = '';
        $this->newPin = '';
        $this->newPinConfirmation = '';
        $this->showPayModal = true;
    }

    public function closePayModal(): void
    {
        $this->showPayModal = false;
        $this->payingRequestId = null;
        $this->payError = null;
    }

    public function createPinForPayment(): void
    {
        $this->validate([
            'newPin' => 'required|digits:4',
            'newPinConfirmation' => 'required|digits:4|same:newPin',
        ], [
            'newPinConfirmation.same' => 'PIN confirmation does not match.',
        ]);

        Auth::user()->update([
            'transaction_pin' => Hash::make($this->newPin),
            'pin_update_at' => now(),
        ]);

        $this->newPin = '';
        $this->newPinConfirmation = '';
        $this->pin = '';

        unset($this->userHasPin);
    }

    public function confirmPay(): void
    {
        $request = $this->payingRequest;

        if (! $request || $request->status !== 'pending') {
            $this->closePayModal();
            unset($this->incomingRequests);
            return;
        }

        if ($this->pinRequired) {
            $this->validate(['pin' => 'required|digits:4']);

            if (! $this->userHasPin) {
                $this->addError('pin', 'You do not have a transaction PIN set.');
                return;
            }

            if (! Hash::check($this->pin, Auth::user()->transaction_pin)) {
                $this->addError('pin', 'Incorrect transaction PIN.');
                return;
            }
        }

        $amount = (float) $request->amount;

        try {
            DB::beginTransaction();

            $payer = User::where('id', Auth::id())->lockForUpdate()->first();
            $requester = User::where('id', $request->requester_id)->lockForUpdate()->first();

            if (! $requester) {
                throw new \RuntimeException('The original requester no longer exists.');
            }

            if ($amount > (float) $payer->balance) {
                throw new \RuntimeException('Insufficient balance.');
            }

            $reference = 'TRF-' . strtoupper(Str::random(10));

            $payer->decrement('balance', $amount);
            $requester->increment('balance', $amount);

            app(TransactionService::class)->create([
                'user_id' => $payer->id,
                'reference' => $reference . '-S',
                'amount' => $amount,
                'fee' => 0,
                'currency' => 'USD',
                'type' => TransactionType::TransferOut,
                'direction' => TransactionDirection::Debit,
                'description' => "Paid money request to {$requester->username}",
                'status' => TransactionStatus::Completed,
                'metadata' => [
                    'transfer_reference' => $reference,
                    'money_request_id' => $request->id,
                    'recipient_id' => $requester->id,
                ],
            ]);

            app(TransactionService::class)->create([
                'user_id' => $requester->id,
                'reference' => $reference . '-R',
                'amount' => $amount,
                'fee' => 0,
                'currency' => 'USD',
                'type' => TransactionType::TransferIn,
                'direction' => TransactionDirection::Credit,
                'description' => "Money request paid by {$payer->username}",
                'status' => TransactionStatus::Completed,
                'metadata' => [
                    'transfer_reference' => $reference,
                    'money_request_id' => $request->id,
                    'sender_id' => $payer->id,
                ],
            ]);

            $request->update(['status' => 'accepted']);

            Notification::create([
                'user_id' => $requester->id,
                'title' => 'Money request paid',
                'body' => $payer->name . ' paid your request for ' . money_format($amount) . '.',
                'type' => 'success',
                'is_read' => false,
            ]);

            DB::commit();

            $this->showPayModal = false;
            $this->payingRequestId = null;
            $this->pin = '';

            unset($this->incomingRequests);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            $this->addError('pin', $e instanceof \RuntimeException ? $e->getMessage() : 'Something went wrong while processing this payment.');
        }
    }

    // ===== Decline incoming request =====

    public function confirmDecline(int $requestId): void
    {
        $this->decliningRequestId = $requestId;
    }

    public function cancelDecline(): void
    {
        $this->decliningRequestId = null;
    }

    public function declineRequest(int $requestId): void
    {
        $request = MoneyRequest::with('requester')->find($requestId);

        if (! $request || $request->recipient_id !== Auth::id() || $request->status !== 'pending') {
            $this->decliningRequestId = null;
            return;
        }

        $request->update(['status' => 'declined']);

        Notification::create([
            'user_id' => $request->requester_id,
            'title' => 'Money request declined',
            'body' => Auth::user()->name . ' declined your request for ' . money_format($request->amount) . '.',
            'type' => 'warning',
            'is_read' => false,
        ]);

        $this->decliningRequestId = null;
        unset($this->incomingRequests);
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Request declined',
            message: 'You have declined the money request from ' . ($request->requester->name ?? $request->requester->username) . '.'
        );
    }

    public function render()
    {
        return view('livewire.requests.request-money')->layout('components.layouts.app', [
            'title' => 'Request Money',
        ]);
    }
}
