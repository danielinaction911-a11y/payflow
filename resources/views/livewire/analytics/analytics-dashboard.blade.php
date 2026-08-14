<div>
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Performance</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Analytics</h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">A closer look at how your portfolio has performed.</p>
        </div>

        <div class="flex gap-1 tab-group">
            @foreach(['7d' => '1W', '30d' => '1M', '90d' => '3M', '1y' => '1Y'] as $value => $label)
                <button wire:click="setRange('{{ $value }}')" class="tab-btn {{ $range === $value ? 'is-active' : '' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </section>

    <x-ui.dashboard-tabs />

    {{-- Summary cards --}}
    <div class="grid gap-5 md:grid-cols-3">
        <div class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Total returns</p>
                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ money_format($this->totalReturns) }}</p>
                </div>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-emerald-500/12 text-emerald-500">
                    <x-app-icon name="trending-up" class="h-[17px] w-[17px]" />
                </span>
            </div>
            <div class="mt-5 flex justify-between">
                <small class="text-slate-500 dark:text-slate-400">All time</small>
                <small class="font-semibold {{ $this->totalReturnsAllTimePercent >= 0 ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-500' }}">
                    {{ percentage_format($this->totalReturnsAllTimePercent) }}
                </small>
            </div>
        </div>

        <div class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Best performing plan</p>
                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 dark:text-white">
                        {{ $this->bestPerformingPlan->plan->name ?? 'No investments yet' }}
                    </p>
                </div>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-sky-500/12 text-sky-500">
                    <x-app-icon name="sparkles" class="h-[17px] w-[17px]" />
                </span>
            </div>
            <div class="mt-5 flex justify-between">
                <small class="text-slate-500 dark:text-slate-400">Return so far</small>
                @if($this->bestPerformingPlan && $this->bestPerformingPlan->amount_invested > 0)
                    <small class="font-semibold text-emerald-600 dark:text-emerald-500">
                        {{ percentage_format(round(($this->bestPerformingPlan->total_paid_out / $this->bestPerformingPlan->amount_invested) * 100, 2)) }}
                    </small>
                @else
                    <small class="font-semibold text-slate-400">—</small>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Avg. plan ROI rate</p>
                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $this->avgMonthlyRoi }}%</p>
                </div>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-violet-500/12 text-violet-500">
                    <x-app-icon name="sparkles" class="h-[17px] w-[17px]" />
                </span>
            </div>
            <div class="mt-5 flex justify-between">
                <small class="text-slate-500 dark:text-slate-400">Investment plans</small>
                <small class="font-semibold text-emerald-600 dark:text-emerald-500">Across all active</small>
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(310px,.8fr)]">
        {{-- Portfolio growth chart --}}
        <section class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Portfolio growth</h2>
                <span class="text-xs font-medium {{ $this->portfolioChangeAmount >= 0 ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-500' }}">
                    {{ $this->portfolioChangeAmount >= 0 ? '+' : '' }}{{ money_format($this->portfolioChangeAmount) }} ({{ percentage_format($this->portfolioChangePercent) }})
                </span>
            </div>

            @php
                $points = $this->portfolioGrowth;
                $balances = array_column($points, 'balance');
                $min = count($balances) ? min($balances) : 0;
                $max = count($balances) ? max($balances) : 1;
                $range = ($max - $min) ?: 1;
                $width = 548;
                $height = 174;
                $count = max(count($points) - 1, 1);

                $coords = collect($points)->values()->map(function ($point, $i) use ($width, $height, $min, $range, $count) {
                    $x = ($i / $count) * $width;
                    $y = $height - (($point['balance'] - $min) / $range) * ($height - 20) - 10;
                    return [$x, $y];
                });

                $linePath = $coords->map(fn ($c, $i) => ($i === 0 ? 'M' : 'L') . round($c[0], 1) . ' ' . round($c[1], 1))->implode(' ');
                $fillPath = $linePath . " L{$width} {$height} L0 {$height} Z";
            @endphp

            <svg viewBox="0 0 {{ $width }} {{ $height }}" preserveAspectRatio="none" class="mt-6 h-52 w-full">
                <defs>
                    <linearGradient id="analytics-fill" x1="0" x2="0" y1="0" y2="1">
                        <stop offset="0" stop-color="#34d399" stop-opacity=".27"></stop>
                        <stop offset="1" stop-color="#34d399" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                @for($i = 1; $i <= 3; $i++)
                    <line x1="0" x2="{{ $width }}" y1="{{ $i * ($height / 4) }}" y2="{{ $i * ($height / 4) }}" class="stroke-slate-200 dark:stroke-white/[.06]" stroke-width="1"></line>
                @endfor
                <path d="{{ $fillPath }}" fill="url(#analytics-fill)"></path>
                <path d="{{ $linePath }}" fill="none" stroke="#34d399" stroke-width="2.4" vector-effect="non-scaling-stroke"></path>
                @if($coords->isNotEmpty())
                    <circle cx="{{ $coords->last()[0] }}" cy="{{ $coords->last()[1] }}" r="4" fill="#34d399" stroke="currentColor" stroke-width="3" class="text-white dark:text-[#111a2d]"></circle>
                @endif
            </svg>

            <div class="mt-2 flex justify-between text-[11px] text-slate-400 dark:text-slate-500">
                <span>{{ \Carbon\Carbon::parse($points[0]['date'] ?? now())->format('M j') }}</span>
                <span>Today</span>
            </div>
        </section>

        {{-- Return by asset --}}
        <section class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Return by plan</h2>

            @if($this->returnByAsset->isNotEmpty())
                <div class="mt-6 space-y-5">
                    @foreach($this->returnByAsset as $planName => $data)
                        <div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-700 dark:text-slate-300">{{ $planName }}</span>
                                <span class="{{ $data['percent'] >= 0 ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-500' }}">{{ percentage_format($data['percent']) }}</span>
                            </div>
                            <div class="mt-2 h-1.5 overflow-hidden rounded-full border border-slate-200 bg-slate-100 dark:border-white/[.07] dark:bg-white/[.035]">
                                <div class="h-full rounded-full bg-emerald-500" style="width: {{ min(abs($data['percent']), 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-10 flex flex-col items-center text-center">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                        <x-app-icon name="sparkles" class="h-5 w-5 text-slate-400" />
                    </span>
                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">No investments yet.</p>
                </div>
            @endif
        </section>
    </div>

    {{-- Deposits vs withdrawals + trade stats --}}
    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <section class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Deposits vs withdrawals</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">In the selected period</p>

            @php
                $summary = $this->depositWithdrawalSummary;
                $maxVal = max($summary['deposits'], $summary['withdrawals'], 1);
            @endphp

            <div class="mt-6 space-y-4">
                <div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Deposits</span>
                        <b class="text-slate-900 dark:text-white">{{ money_format($summary['deposits']) }}</b>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full border border-slate-200 bg-slate-100 dark:border-white/[.07] dark:bg-white/[.035]">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ ($summary['deposits'] / $maxVal) * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Withdrawals</span>
                        <b class="text-slate-900 dark:text-white">{{ money_format($summary['withdrawals']) }}</b>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full border border-slate-200 bg-slate-100 dark:border-white/[.07] dark:bg-white/[.035]">
                        <div class="h-full rounded-full bg-orange-500" style="width: {{ ($summary['withdrawals'] / $maxVal) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Trading activity</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">All time</p>

            @php $stats = $this->tradeStats; @endphp

            <div class="mt-5 grid grid-cols-3 gap-3 text-center">
                <div class="rounded-xl border p-3 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
                    <small class="text-xs text-slate-500 dark:text-slate-400">Total trades</small>
                </div>
                <div class="rounded-xl border p-3 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                    <p class="text-lg font-semibold text-emerald-600 dark:text-emerald-500">{{ $stats['buys'] }}</p>
                    <small class="text-xs text-slate-500 dark:text-slate-400">Buys</small>
                </div>
                <div class="rounded-xl border p-3 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                    <p class="text-lg font-semibold text-rose-500">{{ $stats['sells'] }}</p>
                    <small class="text-xs text-slate-500 dark:text-slate-400">Sells</small>
                </div>
            </div>

            <div class="mt-4 flex justify-between rounded-xl border p-3.5 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                <span class="text-xs text-slate-500 dark:text-slate-400">Total volume traded</span>
                <b class="text-sm text-slate-900 dark:text-white">{{ money_format($stats['volume']) }}</b>
            </div>
        </section>
    </div>
</div>