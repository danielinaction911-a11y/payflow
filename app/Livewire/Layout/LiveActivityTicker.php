<?php

namespace App\Livewire\Layout;

use App\Models\Deposit;
use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\TradingPair;
use App\Models\Withdrawal;
use App\Models\WithdrawGateway;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class LiveActivityTicker extends Component
{
    public array $items = [];
    public int $durationSeconds = 60;

    protected array $namePool = [];
    protected array $planPool = [];
    protected array $methodPool = [];
    protected array $pairPool = [];

    public function mount(): void
    {
        $count = 24;

        $this->namePool = $this->fetchNamePool($count);
        $this->planPool = $this->fetchPlanPool();
        $this->methodPool = $this->fetchMethodPool();
        $this->pairPool = $this->fetchPairPool();

        // Generate fake items
        $generatedItems = collect(range(1, $count))
            ->map(fn () => $this->generateItem())
            ->all();

        // Fetch real items from database
        $realItems = $this->fetchRealItems();

        // Combine both fake and real items
        $this->items = array_merge($generatedItems, $realItems);

        // ~280px average item width / ~35px per second ≈ a comfortably
        // readable, slow scroll — was 2.2s/item before, which was far too
        // fast to actually read. 8s/item gets close to that pace.
        $this->durationSeconds = max(90, (int) round(count($this->items) * 8));
    }

    /**
     * One batch call to randomuser.me for real first/last names, cached
     * across all visitors for 30 minutes — never called per-item, and
     * never blocks page render for more than a couple seconds even on
     * a cache miss (short timeout + fallback to the static list below).
     */
    protected function fetchNamePool(int $count): array
    {
        return Cache::remember('ticker_name_pool', now()->addMinutes(30), function () use ($count) {
            try {
                $response = Http::timeout(3)->get('https://randomuser.me/api/', [
                    'results' => $count,
                    'inc' => 'name',
                    'nat' => 'us,gb,ca,au',
                ]);

                if ($response->failed()) {
                    throw new \RuntimeException('randomuser.me request failed');
                }

                $names = collect($response->json('results', []))
                    ->map(function ($r) {
                        $first = trim($r['name']['first'] ?? '');
                        $lastInitial = strtoupper(substr($r['name']['last'] ?? '', 0, 1));

                        return $first && $lastInitial ? "{$first} {$lastInitial}." : null;
                    })
                    ->filter()
                    ->values()
                    ->all();

                if (empty($names)) {
                    throw new \RuntimeException('No usable names in response');
                }

                return $names;
            } catch (\Throwable $e) {
                report($e);

                return $this->fallbackNamePool();
            }
        });
    }

    protected function fallbackNamePool(): array
    {
        $first = [
            'Chloe', 'Robert', 'Ashley', 'Michael', 'Sarah', 'David', 'Emily', 'James',
            'Olivia', 'Daniel', 'Sophia', 'Matthew', 'Isabella', 'Andrew', 'Mia', 'Joshua',
            'Amelia', 'Ryan', 'Charlotte', 'Brandon', 'Grace', 'Tyler', 'Lily', 'Justin',
            'Ella', 'Kevin', 'Zoe', 'Aaron', 'Hannah', 'Nathan',
        ];

        return collect($first)
            ->map(fn ($name) => $name . ' ' . chr(random_int(65, 90)) . '.')
            ->all();
    }

    /**
     * NOTE: assumes an App\Models\InvestmentPlan model over an
     * `investment_plans` table with a `name` column, per your instruction.
     * Adjust the model reference if yours differs.
     */
    protected function fetchPlanPool(): array
    {
        return Cache::remember('ticker_plan_pool', now()->addMinutes(15), function () {
            $names = InvestmentPlan::query()->pluck('name')->filter()->values()->all();

            return $names ?: ['Starter Plan', 'Growth Plan', 'Premium Plan', 'Elite Fund', 'Diamond Portfolio'];
        });
    }

    /**
     * NOTE: "gateways table" — the only gateway model in this app so far
     * is App\Models\WithdrawGateway (used on the withdraw form). If you
     * have a separate/unified Gateway model covering deposits too, swap
     * it in here.
     */
    protected function fetchMethodPool(): array
    {
        return Cache::remember('ticker_method_pool', now()->addMinutes(15), function () {
            $names = WithdrawGateway::query()
                ->where('status', true)
                ->pluck('name')
                ->filter()
                ->values()
                ->all();

            return $names ?: ['Chime', 'CashApp', 'PayPal', 'Zelle', 'Bank Transfer', 'Apple Pay', 'Venmo', 'Bitcoin', 'Wire Transfer'];
        });
    }

    protected function fetchPairPool(): array
    {
        return Cache::remember('ticker_pair_pool', now()->addMinutes(15), function () {
            $symbols = TradingPair::query()->pluck('symbol')->filter()->values()->all();

            return $symbols ?: ['BTC/USD', 'ETH/USD', 'SOL/USD', 'XRP/USD', 'BNB/USD', 'ADA/USD'];
        });
    }

    protected function generateItem(): array
    {
        $name = collect($this->namePool)->random();
        $type = collect(['deposit', 'withdrawal', 'withdrawal_code', 'transfer', 'trade', 'invest'])->random();

        $text = match ($type) {
            'deposit' => "{$name} deposited " . $this->randomAmount(50, 25000) . ' via ' . $this->randomMethod(),
            'withdrawal' => "{$name} withdrew " . $this->randomAmount(50, 50000) . ' via ' . $this->randomMethod(),
            'withdrawal_code' => "{$name} purchased a withdrawal code",
            'transfer' => "{$name} transferred " . $this->randomAmount(100, 150000) . ' via ' . $this->randomMethod(),
            'trade' => "{$name} traded " . $this->randomAmount(50, 20000) . ' on ' . $this->randomPair(),
            'invest' => "{$name} invested " . $this->randomAmount(100, 10000) . ' in ' . $this->randomPlan(),
        };

        $dotClass = match ($type) {
            'deposit', 'invest' => 'ticker-dot-green',
            'withdrawal', 'withdrawal_code' => 'ticker-dot-orange',
            'transfer' => 'ticker-dot-blue',
            'trade' => 'ticker-dot-violet',
        };

        return ['text' => $text, 'dot' => $dotClass];
    }

    /**
     * Fetch real items from database (deposits, withdrawals, investments)
     */
    protected function fetchRealItems(): array
    {
        $items = [];

        try {
            // Fetch recent confirmed/completed deposits
            $deposits = Deposit::with('user')
                ->whereIn('status', ['confirmed', 'completed', 'success'])
                ->latest()
                ->take(10)
                ->get();

            foreach ($deposits as $deposit) {
                if ($deposit->user) {
                    $items[] = [
                        'text' => "{$deposit->user->name} deposited " . $this->formatAmount($deposit->amount) . ' via ' . ($deposit->method ?? 'Bank Transfer'),
                        'dot' => 'ticker-dot-green',
                    ];
                }
            }

            // Fetch recent confirmed/completed withdrawals
            $withdrawals = Withdrawal::with('user')
                ->whereIn('status', ['confirmed', 'completed', 'success'])
                ->latest()
                ->take(10)
                ->get();

            foreach ($withdrawals as $withdrawal) {
                if ($withdrawal->user) {
                    $items[] = [
                        'text' => "{$withdrawal->user->name} withdrew " . $this->formatAmount($withdrawal->amount) . ' via ' . ($withdrawal->method ?? 'Bank Transfer'),
                        'dot' => 'ticker-dot-orange',
                    ];
                }
            }

            // Fetch recent active/running investments
            $investments = Investment::with(['user', 'plan'])
                ->whereIn('status', ['active', 'running', 'ongoing'])
                ->latest()
                ->take(10)
                ->get();

            foreach ($investments as $investment) {
                if ($investment->user && $investment->plan) {
                    $items[] = [
                        'text' => "{$investment->user->name} invested " . $this->formatAmount($investment->amount_invested) . ' in ' . $investment->plan->name,
                        'dot' => 'ticker-dot-green',
                    ];
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $items;
    }

    /**
     * Format amount with currency symbol
     */
    protected function formatAmount($amount): string
    {
        return '$' . number_format((float) $amount, 2);
    }

    protected function randomMethod(): string
    {
        return collect($this->methodPool)->random();
    }

    protected function randomPlan(): string
    {
        return collect($this->planPool)->random();
    }

    protected function randomPair(): string
    {
        return collect(['BTC/USD', 'ETH/USD', 'SOL/USD', 'XRP/USD', 'BNB/USD', 'ADA/USD'])->random();
    }

    protected function randomAmount(int $min, int $max): string
    {
        $amount = random_int($min, $max);
        $round = $amount >= 10000 ? 100 : ($amount >= 1000 ? 50 : 10);
        $amount = (int) (round($amount / $round) * $round);

        return '$' . number_format($amount);
    }

    public function render()
    {
        return view('livewire.layout.live-activity-ticker');
    }
}