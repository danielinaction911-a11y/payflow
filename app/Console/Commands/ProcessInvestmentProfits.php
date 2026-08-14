<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\LogsCronRun;
use App\Enums\InvestmentStatus;
use App\Enums\RoiType;
use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Investment;
use App\Models\ProfitLog;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessInvestmentProfits extends Command
{
    use LogsCronRun;

    protected $signature = 'investments:process-profits';
    protected $description = 'Credit periodic profit payouts and finalize matured investments';

    protected string $cronName = 'investments:process-profits';

    protected int $processed = 0;
    protected int $completed = 0;
    protected int $skipped = 0;
    protected int $failed = 0;

    public function handle(): int
    {
        $startedAt = $this->cronStart($this->cronName);

        try {
            $investments = Investment::where('status', InvestmentStatus::Active)->get();

            foreach ($investments as $investment) {
                try {
                    DB::transaction(function () use ($investment) {
                        $locked = Investment::where('id', $investment->id)->lockForUpdate()->first();

                        if (! $locked || $locked->status !== InvestmentStatus::Active) {
                            $this->skipped++;
                            return;
                        }

                        $now = now();

                        if ($locked->ends_at->lessThanOrEqualTo($now)) {
                            $this->finalize($locked);
                            $this->completed++;
                            $this->processed++;
                            return;
                        }

                        $roiType = $locked->plan?->roi_type ?? RoiType::Daily;
                        $periodDays = match ($roiType) {
                            RoiType::Daily => 1,
                            RoiType::Weekly => 7,
                            RoiType::Monthly => 30,
                            RoiType::Yearly => 365,
                            RoiType::OneTime => null,
                        };

                        if ($periodDays === null) {
                            $this->skipped++;
                            return;
                        }

                        $anchor = $locked->last_profit_at ?? $locked->starts_at;
                        $nextDue = $anchor->copy()->addDays($periodDays);

                        if ($now->lessThan($nextDue)) {
                            $this->skipped++;
                            return;
                        }

                        $periodProfit = round((float) $locked->amount_invested * (float) $locked->roi_percentage / 100, 2);

                        if ($periodProfit <= 0) {
                            $this->skipped++;
                            return;
                        }

                        $this->payProfit($locked, $periodProfit, $now);
                        $this->processed++;
                    });
                } catch (\Throwable $e) {
                    $this->failed++;
                    report($e);
                    $this->error("Failed processing investment #{$investment->id}: {$e->getMessage()}");
                }
            }

            $this->cronFinish($this->cronName, $startedAt, 'success',
                "Processed {$this->processed} investment(s). Completed {$this->completed}. Skipped {$this->skipped}. Failed {$this->failed}.",
                ['processed' => $this->processed, 'completed' => $this->completed, 'skipped' => $this->skipped, 'failed' => $this->failed]
            );

            $this->info("Profit run complete. Processed: {$this->processed}. Completed: {$this->completed}. Skipped: {$this->skipped}. Failed: {$this->failed}.");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->cronFinish($this->cronName, $startedAt, 'failed', 'Fatal error: ' . $e->getMessage(),
                ['processed' => $this->processed, 'completed' => $this->completed, 'skipped' => $this->skipped, 'failed' => $this->failed]
            );

            report($e);
            $this->error('Fatal error during profit processing: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    protected function payProfit(Investment $investment, float $amount, $paidAt): void
    {
        $user = User::where('id', $investment->user_id)->lockForUpdate()->first();
        if (! $user) return;

        ProfitLog::create([
            'user_id' => $user->id,
            'investment_id' => $investment->id,
            'amount' => $amount,
            'status' => 'paid',
            'paid_at' => $paidAt,
        ]);

        $user->increment('profit_balance', $amount);
        $investment->increment('total_paid_out', $amount);
        $investment->update(['last_profit_at' => $paidAt]);

        app(TransactionService::class)->create([
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => 'USD',
            'type' => TransactionType::Profit,
            'direction' => TransactionDirection::Credit,
            'status' => TransactionStatus::Completed,
            'description' => "Profit payout — {$investment->plan?->name}",
            'metadata' => ['investment_id' => $investment->id, 'plan_name' => $investment->plan?->name],
        ]);
    }

    protected function finalize(Investment $investment): void
    {
        $user = User::where('id', $investment->user_id)->lockForUpdate()->first();
        if (! $user) return;

        $totalProfitOwed = round((float) $investment->expected_total_return - (float) $investment->amount_invested, 2);
        $remainingProfit = round($totalProfitOwed - (float) $investment->total_paid_out, 2);

        if ($remainingProfit > 0) {
            ProfitLog::create([
                'user_id' => $user->id,
                'investment_id' => $investment->id,
                'amount' => $remainingProfit,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $user->increment('profit_balance', $remainingProfit);
            $investment->increment('total_paid_out', $remainingProfit);

            app(TransactionService::class)->create([
                'user_id' => $user->id,
                'amount' => $remainingProfit,
                'currency' => 'USD',
                'type' => TransactionType::Profit,
                'direction' => TransactionDirection::Credit,
                'status' => TransactionStatus::Completed,
                'description' => "Final profit payout — {$investment->plan?->name}",
                'metadata' => ['investment_id' => $investment->id, 'plan_name' => $investment->plan?->name, 'final' => true],
            ]);
        }

        if ($investment->plan?->capital_back) {
            $user->increment('balance', (float) $investment->amount_invested);

            app(TransactionService::class)->create([
                'user_id' => $user->id,
                'amount' => (float) $investment->amount_invested,
                'currency' => 'USD',
                'type' => TransactionType::Investment,
                'direction' => TransactionDirection::Credit,
                'status' => TransactionStatus::Completed,
                'description' => "Capital returned — {$investment->plan?->name}",
                'metadata' => ['investment_id' => $investment->id, 'plan_name' => $investment->plan?->name, 'capital_return' => true],
            ]);
        }

        $investment->update(['status' => InvestmentStatus::Completed, 'last_profit_at' => now()]);
    }
}