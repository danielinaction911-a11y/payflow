<?php

namespace App\Livewire\Analytics;

use App\Models\Investment;
use App\Models\ProfitLog;
use App\Models\Trade;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class AnalyticsDashboard extends Component
{
    public string $range = '30d'; // 7d | 30d | 90d | 1y

    public function setRange(string $range): void
    {
        $this->range = in_array($range, ['7d', '30d', '90d', '1y']) ? $range : '30d';
        unset($this->portfolioGrowth);
    }

    protected function rangeDays(): int
    {
        return match ($this->range) {
            '7d' => 7,
            '90d' => 90,
            '1y' => 365,
            default => 30,
        };
    }

    #[Computed]
    public function totalReturns()
    {
        return ProfitLog::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->sum('amount');
    }

    #[Computed]
    public function totalReturnsAllTimePercent(): float
    {
        $invested = (float) Investment::where('user_id', Auth::id())->sum('amount_invested');

        if ($invested <= 0) {
            return 0;
        }

        return round(((float) $this->totalReturns / $invested) * 100, 2);
    }

    #[Computed]
    public function avgMonthlyRoi(): float
    {
        $investments = Investment::where('user_id', Auth::id())->get();

        if ($investments->isEmpty()) {
            return 0;
        }

        return round($investments->avg('roi_percentage'), 2);
    }

    #[Computed]
    public function bestPerformingTrade()
    {
        return Trade::where('user_id', Auth::id())
            ->with('tradingPair.baseCurrency')
            ->where('side', 'sell')
            ->orderByDesc('total')
            ->first();
    }

    #[Computed]
    public function bestPerformingPlan()
    {
        return Investment::where('user_id', Auth::id())
            ->with('plan')
            ->get()
            ->sortByDesc(function ($investment) {
                if ($investment->amount_invested <= 0) {
                    return 0;
                }

                return ($investment->total_paid_out / $investment->amount_invested) * 100;
            })
            ->first();
    }

    #[Computed]
    public function portfolioGrowth(): array
    {
        $days = $this->rangeDays();
        $startDate = now()->subDays($days)->startOfDay();

        // Starting balance = whatever the running total was immediately
        // before the window began, derived from every prior transaction.
        $priorCredits = Transaction::where('user_id', Auth::id())
            ->where('status', \App\Enums\TransactionStatus::Completed)
            ->where('created_at', '<', $startDate)
            ->where('direction', \App\Enums\TransactionDirection::Credit)
            ->sum('amount');

        $priorDebits = Transaction::where('user_id', Auth::id())
            ->where('status', \App\Enums\TransactionStatus::Completed)
            ->where('created_at', '<', $startDate)
            ->where('direction', \App\Enums\TransactionDirection::Debit)
            ->sum('amount');

        $runningBalance = (float) $priorCredits - (float) $priorDebits;

        $dailyNet = Transaction::where('user_id', Auth::id())
            ->where('status', \App\Enums\TransactionStatus::Completed)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, direction, SUM(amount) as total')
            ->groupBy('date', 'direction')
            ->get()
            ->groupBy('date');

        $points = [];

        for ($i = 0; $i <= $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $dayRows = $dailyNet->get($date, collect());

            $credit = (float) $dayRows->firstWhere('direction', \App\Enums\TransactionDirection::Credit)?->total;
            $debit = (float) $dayRows->firstWhere('direction', \App\Enums\TransactionDirection::Debit)?->total;

            $runningBalance += $credit - $debit;

            $points[] = [
                'date' => $date,
                'balance' => round($runningBalance, 2),
            ];
        }

        return $points;
    }

    #[Computed]
    public function portfolioChangeAmount(): float
    {
        $points = $this->portfolioGrowth;

        if (count($points) < 2) {
            return 0;
        }

        return round(end($points)['balance'] - $points[0]['balance'], 2);
    }

    #[Computed]
    public function portfolioChangePercent(): float
    {
        $points = $this->portfolioGrowth;

        if (count($points) < 2 || $points[0]['balance'] == 0) {
            return 0;
        }

        return round((($this->portfolioChangeAmount) / abs($points[0]['balance'])) * 100, 2);
    }

    #[Computed]
    public function returnByAsset()
    {
        return Investment::where('user_id', Auth::id())
            ->with('plan')
            ->get()
            ->groupBy(fn ($investment) => $investment->plan->name ?? 'Unknown plan')
            ->map(function ($group) {
                $invested = $group->sum('amount_invested');
                $paidOut = $group->sum('total_paid_out');
                $percent = $invested > 0 ? round(($paidOut / $invested) * 100, 2) : 0;

                return [
                    'invested' => $invested,
                    'paid_out' => $paidOut,
                    'percent' => $percent,
                ];
            })
            ->sortByDesc('percent');
    }

    #[Computed]
    public function depositWithdrawalSummary(): array
    {
        $days = $this->rangeDays();
        $startDate = now()->subDays($days);

        $deposits = Transaction::where('user_id', Auth::id())
            ->where('type', \App\Enums\TransactionType::Deposit)
            ->where('status', \App\Enums\TransactionStatus::Completed)
            ->where('created_at', '>=', $startDate)
            ->sum('amount');

        $withdrawals = Transaction::where('user_id', Auth::id())
            ->where('type', \App\Enums\TransactionType::Withdrawal)
            ->where('status', \App\Enums\TransactionStatus::Completed)
            ->where('created_at', '>=', $startDate)
            ->sum('amount');

        return ['deposits' => (float) $deposits, 'withdrawals' => (float) $withdrawals];
    }

    #[Computed]
    public function tradeStats(): array
    {
        $trades = Trade::where('user_id', Auth::id())->get();

        $buys = $trades->where('side', 'buy')->count();
        $sells = $trades->where('side', 'sell')->count();
        $volume = $trades->sum('total');

        return ['buys' => $buys, 'sells' => $sells, 'total' => $trades->count(), 'volume' => $volume];
    }

    public function render()
    {
        return view('livewire.analytics.analytics-dashboard')->layout('components.layouts.app', [
            'title' => 'Analytics Dashboard',
        ]);
    }
}