<?php

namespace App\Livewire\Withdraw;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\WithdrawGateway;
use App\Models\Withdrawal;
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
class WithdrawForm extends Component
{
    public string $step = 'select'; // select -> details -> pin -> success

    public string $balanceSource = 'balance'; // 'balance' | 'profit_balance'
    public ?int $gatewayId = null;
    public string $amount = '';
    public array $fields = [];

    public string $pin = '';
    public string $newPin = '';
    public string $newPinConfirmation = '';

    public ?array $successData = null;

    // security checkpoint state
    public bool $accessBlocked = false;
    public string $blockedMessage = '';

    #[Computed]
    public function gateways()
    {
        return WithdrawGateway::where('status', true)->orderBy('id')->get();
    }

    #[Computed]
    public function selectedGateway()
    {
        return $this->gatewayId ? WithdrawGateway::find($this->gatewayId) : null;
    }

    #[Computed]
    public function fee(): float
    {
        if (! $this->selectedGateway || ! is_numeric($this->amount)) {
            return 0;
        }

        return $this->selectedGateway->calculateFee((float) $this->amount);
    }

    #[Computed]
    public function total(): float
    {
        if (! is_numeric($this->amount)) {
            return 0;
        }

        return (float) $this->amount + $this->fee;
    }

    #[Computed]
    public function availableBalance(): float
    {
        return (float) (auth()->user()->{$this->balanceSource} ?? 0);
    }

    #[Computed]
    public function pinRequired(): bool
    {
        return (bool) setting('require_pin_for_withdrawal', true);
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

        if ($this->accessBlocked) {
            return;
        }

        $this->gatewayId = $this->gateways->first()?->id;
    }

    /**
     * Runs every security checkpoint for withdrawal access.
     * Sets $this->accessBlocked and $this->blockedMessage if any check fails.
     */
    protected function checkAccess(): void
    {
        $user = Auth::user();

        // 1. Global withdrawals toggle
        if (! (bool) setting('withdrawals_enabled', true)) {
            $this->accessBlocked = true;
            $this->blockedMessage = 'Withdrawals are currently disabled on the platform. Please try again later.';
            return;
        }

        // 2. KYC requirement
        if ((bool) setting('require_kyc', true)) {
            $kycVerified = $user->kyc_status === 'approved' || $user->kyc_status === 'verified';

            if (! $kycVerified) {
                $this->accessBlocked = true;
                $this->blockedMessage = 'Please complete your KYC verification before making a withdrawal.';
                return;
            }
        }

        // 3. Per-user withdrawal restriction
        if ($user->withdrawal_status === 'disabled') {
            $this->accessBlocked = true;
            $this->blockedMessage = $user->withdrawal_message ?: 'Withdrawals are currently disabled for your account. Please contact support.';
            return;
        }

        $this->accessBlocked = false;
        $this->blockedMessage = '';
    }

    public function selectBalanceSource(string $source): void
    {
        $this->balanceSource = $source;
    }

    public function selectGateway(int $id): void
    {
        $this->gatewayId = $id;
        $this->fields = [];
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function proceedToDetails(): void
    {
        // re-check in case settings/user status changed mid-flow
        $this->checkAccess();
        if ($this->accessBlocked) {
            return;
        }

        $gateway = $this->selectedGateway;

        $this->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        if (! $gateway) {
            $this->addError('gatewayId', 'Please select a withdrawal method.');
            return;
        }

        if ((float) $this->amount < (float) $gateway->min_amount || (float) $this->amount > (float) $gateway->max_amount) {
            $this->addError('amount', 'Amount must be between ' . money_format($gateway->min_amount, $gateway->currency) . ' and ' . money_format($gateway->max_amount, $gateway->currency) . '.');
            return;
        }

        if ($this->total > $this->availableBalance) {
            $this->addError('amount', 'Insufficient balance. Your available ' . ($this->balanceSource === 'profit_balance' ? 'profit' : 'main') . ' balance is ' . money_format($this->availableBalance) . '.');
            return;
        }

        // Check per-user withdrawal limits (daily / weekly /monthly)
        if (! $this->passesWithdrawalLimits((float) $this->amount)) {
            return;
        }

        $this->step = 'details';
    }

    public function proceedToPin(): void
    {
        $gateway = $this->selectedGateway;

        $rules = [];

        if ($gateway->details) {
            foreach ($gateway->details as $field) {
                $required = ! empty($field['required']) ? 'required' : 'nullable';
                $rules["fields.{$field['name']}"] = "$required|string|max:255";
            }
        }

        $this->validate($rules);

        // If PIN isn't required by settings, skip straight to submission
        if (! $this->pinRequired) {
            $this->processWithdrawal();
            return;
        }

        $this->step = 'pin';
    }

    public function backToSelect(): void
    {
        $this->step = 'select';
    }

    public function backToDetails(): void
    {
        $this->step = 'details';
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

        // Re-check computed props and continue straight to confirming withdrawal
        unset($this->userHasPin);
        $this->pin = '';
    }

    public function submit(): void
    {
        // final checkpoint right before funds move
        $this->checkAccess();
        if ($this->accessBlocked) {
            $this->step = 'select';
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

        $this->processWithdrawal();
    }

    /**
     * Check user's daily/weekly/monthly withdrawal usage against their limits.
     * Returns true if the requested amount is allowed, otherwise adds an error and returns false.
     */
    protected function passesWithdrawalLimits(float $amount): bool
    {
        $user = auth()->user();

        $statuses = ['pending', 'approved', 'paid'];

        $todaySum = (float) $user->withdrawals()->whereIn('status', $statuses)->where('created_at', '>=', Carbon::now()->startOfDay())->sum('amount');
        $weekSum = (float) $user->withdrawals()->whereIn('status', $statuses)->where('created_at', '>=', Carbon::now()->startOfWeek())->sum('amount');
        $monthSum = (float) $user->withdrawals()->whereIn('status', $statuses)->where('created_at', '>=', Carbon::now()->startOfMonth())->sum('amount');

        // Daily
        if ((float) ($user->daily_withdrawal_limit ?? 0) > 0 && ($todaySum + $amount) > (float) $user->daily_withdrawal_limit) {
            $this->addError('amount', 'This withdrawal would exceed your daily withdrawal limit of ' . money_format($user->daily_withdrawal_limit) . '. You have withdrawn ' . money_format($todaySum) . ' today.');
            return false;
        }

        // Weekly
        if ((float) ($user->weekly_withdrawal_limit ?? 0) > 0 && ($weekSum + $amount) > (float) $user->weekly_withdrawal_limit) {
            $this->addError('amount', 'This withdrawal would exceed your weekly withdrawal limit of ' . money_format($user->weekly_withdrawal_limit) . '. You have withdrawn ' . money_format($weekSum) . ' this week.');
            return false;
        }

        // Monthly
        if ((float) ($user->monthly_withdrawal_limit ?? 0) > 0 && ($monthSum + $amount) > (float) $user->monthly_withdrawal_limit) {
            $this->addError('amount', 'This withdrawal would exceed your monthly withdrawal limit of ' . money_format($user->monthly_withdrawal_limit) . '. You have withdrawn ' . money_format($monthSum) . ' this month.');
            return false;
        }

        return true;
    }

    protected function processWithdrawal(): void
    {
        $gateway = $this->selectedGateway;
        $user = auth()->user();

        if (! $gateway) {
            return;
        }

        if ($this->total > (float) $user->{$this->balanceSource}) {
            $this->addError('pin', 'Insufficient balance.');
            $this->step = 'select';
            return;
        }

        // Re-check withdrawal limits right before processing to avoid race conditions
        if (! $this->passesWithdrawalLimits((float) $this->amount)) {
            $this->step = 'select';
            return;
        }

        try {
            DB::beginTransaction();

            $reference = 'WD-' . strtoupper(Str::random(10));

            $user->decrement($this->balanceSource, $this->total);

            $transaction = app(TransactionService::class)->create([
                'user_id' => $user->id,
                'reference' => $reference,
                'amount' => (float) $this->amount,
                'fee' => $this->fee,
                'currency' => $gateway->currency,
                'type' => TransactionType::Withdrawal,
                'direction' => TransactionDirection::Debit,
                'description' => "Withdrawal via {$gateway->name}",
                'status' => TransactionStatus::Pending,
                'metadata' => [
                    'gateway_id' => $gateway->id,
                    'gateway_name' => $gateway->name,
                    'balance_source' => $this->balanceSource,
                    'total' => $this->total,
                ],
            ]);

            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => (float) $this->amount,
                'fee' => $this->fee,
                'currency' => $gateway->currency,
                'method' => $gateway->name,
                'transaction_id' => $transaction->reference,
                'status' => 'pending',
                'metadata' => array_merge($this->fields, ['balance_source' => $this->balanceSource]),
            ]);

            DB::commit();

            $this->successData = [
                'amount' => $this->amount,
                'fee' => $this->fee,
                'total' => $this->total,
                'gateway' => $gateway->name,
                'reference' => $transaction->reference,
            ];

            $this->step = 'success';
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            $this->addError('submit', 'Something went wrong while processing your withdrawal. Please try again.');
        }
    }

    public function startOver(): void
    {
        $this->reset(['step', 'gatewayId', 'amount', 'fields', 'pin', 'newPin', 'newPinConfirmation', 'successData']);
        $this->checkAccess();

        if (! $this->accessBlocked) {
            $this->gatewayId = $this->gateways->first()?->id;
        }

        $this->step = 'select';
    }

    public function render()
    {
        return view('livewire.withdraw.withdraw-form')->layout('components.layouts.app', [
            'title' => 'Withdraw Funds',
        ]);
    }
}