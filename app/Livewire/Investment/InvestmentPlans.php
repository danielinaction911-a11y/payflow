<?php

namespace App\Livewire\Investment;

use App\Enums\InvestmentStatus;
use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class InvestmentPlans extends Component
{
    public bool $showInvestModal = false;
    public ?int $selectedPlanId = null;
    public string $amount = '';
    public ?string $error = null;

    public bool $showSuccess = false;
    public ?array $successData = null;

    public bool $accessBlocked = false;
    public string $blockedMessage = '';

    public function mount(): void
    {
        $this->checkAccess();
    }

    protected function checkAccess(): void
    {
        $user = Auth::user();

        if (! (bool) setting('investments_enabled', true)) {
            $this->accessBlocked = true;
            $this->blockedMessage = 'Investments are currently disabled on the platform. Please try again later.';
            return;
        }

        if ($user->investment_status === 'disabled') {
            $this->accessBlocked = true;
            $this->blockedMessage = $user->investment_message ?: 'Investments are currently disabled for your account. Please contact support.';
            return;
        }

        $this->accessBlocked = false;
        $this->blockedMessage = '';
    }

    #[Computed]
    public function plans()
    {
        return InvestmentPlan::where('status', 'active')->orderBy('min_amount')->get();
    }

    #[Computed]
    public function selectedPlan()
    {
        return $this->selectedPlanId ? InvestmentPlan::find($this->selectedPlanId) : null;
    }

    #[Computed]
    public function expectedReturn(): float
    {
        if (! $this->selectedPlan || ! is_numeric($this->amount)) {
            return 0;
        }

        return $this->selectedPlan->calculateExpectedReturn((float) $this->amount);
    }

    #[Computed]
    public function amountError(): ?string
    {
        if (! $this->selectedPlan || $this->amount === '') {
            return null;
        }

        if (! is_numeric($this->amount)) {
            return 'Enter a valid number.';
        }

        $amount = (float) $this->amount;

        if ($amount <= 0) {
            return 'Amount must be greater than zero.';
        }

        if ($amount < (float) $this->selectedPlan->min_amount) {
            return 'Minimum investment is ' . money_format($this->selectedPlan->min_amount) . '.';
        }

        if ($amount > (float) $this->selectedPlan->max_amount) {
            return 'Maximum investment is ' . money_format($this->selectedPlan->max_amount) . '.';
        }

        if ($amount > (float) auth()->user()->balance) {
            return 'Insufficient balance. Your available balance is ' . money_format(auth()->user()->balance) . '.';
        }

        return null;
    }

    #[Computed]
    public function isAmountValid(): bool
    {
        return $this->amount !== '' && is_numeric($this->amount) && $this->amountError === null;
    }

    public function openInvestModal(int $planId): void
    {
        $this->selectedPlanId = $planId;
        $this->amount = '';
        $this->error = null;
        $this->showInvestModal = true;
    }

    public function closeInvestModal(): void
    {
        $this->showInvestModal = false;
    }

    public function invest(): void
    {
        $this->checkAccess();

        if ($this->accessBlocked) {
            $this->error = $this->blockedMessage;
            return;
        }

        $this->error = null;
        $plan = $this->selectedPlan;

        if (! $plan) {
            $this->error = 'Please select a plan.';
            return;
        }

        if (! $this->isAmountValid) {
            $this->error = $this->amountError ?? 'Enter a valid amount.';
            return;
        }

        $amount = (float) $this->amount;
        $user = Auth::user();

        try {
            DB::transaction(function () use ($plan, $amount, $user) {
                $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();

                if ($amount > (float) $lockedUser->balance) {
                    throw new \RuntimeException('Insufficient balance.');
                }

                $expectedReturn = $plan->calculateExpectedReturn($amount);
                $startsAt = now();
                $endsAt = now()->addDays($plan->duration_days);

                $investment = Investment::create([
                    'user_id' => $user->id,
                    'investment_plan_id' => $plan->id,
                    'amount_invested' => $amount,
                    'roi_percentage' => $plan->roi_percentage,
                    'expected_total_return' => $expectedReturn,
                    'total_paid_out' => 0,
                    'status' => InvestmentStatus::Active,
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                ]);

                $lockedUser->decrement('balance', $amount);

                app(TransactionService::class)->create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'currency' => 'USD',
                    'type' => TransactionType::Investment,
                    'direction' => TransactionDirection::Debit,
                    'status' => TransactionStatus::Completed,
                    'description' => "Invested in {$plan->name}",
                    'metadata' => [
                        'investment_id' => $investment->id,
                        'plan_name' => $plan->name,
                        'expected_return' => $expectedReturn,
                        'ends_at' => $endsAt->toDateTimeString(),
                    ],
                ]);

                $this->successData = [
                    'plan_name' => $plan->name,
                    'amount' => $amount,
                    'expected_return' => $expectedReturn,
                    'duration_days' => $plan->duration_days,
                    'ends_at' => $endsAt,
                ];
            });

            $this->showInvestModal = false;
            $this->showSuccess = true;
            unset($this->plans);
        } catch (\Throwable $e) {
            $this->error = $e instanceof \RuntimeException ? $e->getMessage() : 'Something went wrong. Please try again.';

            if (! $e instanceof \RuntimeException) {
                report($e);
            }
        }
    }

    public function investAgain(): void
    {
        $this->showSuccess = false;
        $this->successData = null;
    }

    public function render()
    {
        return view('livewire.investment.investment-plans')->layout('components.layouts.app', [
            'title' => 'Investment Plans',
        ]);
    }
}
