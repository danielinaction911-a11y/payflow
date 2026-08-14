<div>
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ now()->format('l, F j') }}</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', $this->user->name)[0] }}.
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Here is how your wealth is moving today.</p>
        </div>
        <a href="{{ Route::has('deposit.index') ? route('deposit.index') : '#' }}" class="primary-button w-fit">
            <x-app-icon name="plus" class="h-[17px] w-[17px]" /> Add funds
        </a>
    </section>
    <x-ui.dashboard-tabs />
    {{-- Balance hero --}}
    <section class="relative overflow-hidden rounded-[28px] border border-emerald-300/15 bg-[#112b29] px-5 py-6 shadow-[0_18px_45px_rgba(5,30,27,.24)] sm:px-7 sm:py-7">
        <div class="absolute -right-16 -top-28 h-72 w-72 rounded-full bg-emerald-400/15 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-emerald-100/75">Net worth</div>
                <p class="mt-2 text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                    {{ money_format($this->netWorth) }}
                </p>
                <div class="mt-3 flex gap-2 text-sm">
                    <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-400/15 px-2 py-1 font-medium text-emerald-300">
                        <x-app-icon name="trending-up" class="h-3.5 w-3.5" />
                    </span>
                    <span class="text-emerald-100/65">{{ money_format($this->user->profit_balance) }} in profit</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-x-7 gap-y-4 sm:gap-x-11">
                <div>
                    <p class="text-[11px] leading-tight text-emerald-100/60">Available to invest</p>
                    <p class="mt-1.5 text-sm font-semibold text-white">{{ money_format($this->user->balance) }}</p>
                </div>
                <div>
                    <p class="text-[11px] leading-tight text-emerald-100/60">Total invested</p>
                    <p class="mt-1.5 text-sm font-semibold text-emerald-300">{{ money_format($this->totalInvested) }}</p>
                </div>
                <div>
                    <p class="text-[11px] leading-tight text-emerald-100/60">Profit balance</p>
                    <p class="mt-1.5 text-sm font-semibold text-emerald-300">{{ money_format($this->user->profit_balance) }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(320px,.82fr)]">
        <div class="min-w-0 space-y-6">

            {{-- Quick actions --}}
            <section>
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Quick actions</h2>
                @php
                $actions = [
                ['label' => 'Deposit', 'icon' => 'arrow-down-left', 'color' => 'emerald', 'route' => 'deposit.index'],
                ['label' => 'Withdraw', 'icon' => 'arrow-up-right', 'color' => 'orange', 'route' => 'withdraw.index'],
                ['label' => 'Trade', 'icon' => 'chart-line', 'color' => 'sky', 'route' => 'trade.index'],
                ['label' => 'Send Money', 'icon' => 'send', 'color' => 'violet', 'route' => 'transfer.index'],
                ['label' => 'Invest', 'icon' => 'sparkles', 'color' => 'fuchsia', 'route' => 'investments.index'],
                ];
                @endphp
                <div class="mt-3 grid grid-cols-5 gap-2 sm:gap-3">
                    @foreach($actions as $action)
                    <a href="{{ Route::has($action['route']) ? route($action['route']) : '#' }}"
                        class="flex flex-col items-center justify-center gap-2 rounded-2xl border px-2 py-4 text-center transition
                                border-slate-200 bg-slate-50 hover:-translate-y-0.5 hover:bg-slate-100
                                dark:border-white/[.08] dark:bg-white/[.03] dark:hover:bg-white/[.06]">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-{{ $action['color'] }}-500/12 text-{{ $action['color'] }}-500">
                            <x-app-icon :name="$action['icon']" class="h-[18px] w-[18px]" />
                        </span>
                        <span class="text-[11px] font-medium text-slate-700 dark:text-slate-200 sm:text-xs">{{ $action['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </section>

            {{-- Recent transactions --}}
            <section class="rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <div class="flex justify-between p-5 pb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Recent transactions</h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Your latest account activity</p>
                    </div>
                    <a href="{{ Route::has('transactions.index') ? route('transactions.index') : '#' }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-500">View all</a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-white/[.07]">
                    @forelse($this->recentTransactions as $transaction)
                    @php
                    $type = $transaction->type;
                    $status = $transaction->status;

                    $typeStyle = match($type) {
                    \App\Enums\TransactionType::Deposit => ['bg-emerald-500/12 text-emerald-500', 'arrow-down-left'],
                    \App\Enums\TransactionType::Withdrawal => ['bg-orange-500/12 text-orange-500', 'arrow-up-right'],
                    \App\Enums\TransactionType::Trade => ['bg-sky-500/12 text-sky-500', 'chart-line'],
                    \App\Enums\TransactionType::Investment => ['bg-violet-500/12 text-violet-500', 'sparkles'],
                    \App\Enums\TransactionType::Profit => ['bg-emerald-500/12 text-emerald-500', 'money'],
                    \App\Enums\TransactionType::TransferIn => ['bg-sky-500/12 text-sky-500', 'arrow-down-left'],
                    \App\Enums\TransactionType::TransferOut => ['bg-sky-500/12 text-sky-500', 'send'],
                    \App\Enums\TransactionType::Exchange => ['bg-sky-500/12 text-sky-500', 'chart-line'],
                    \App\Enums\TransactionType::Bonus => ['bg-fuchsia-500/12 text-fuchsia-500', 'gift'],
                    \App\Enums\TransactionType::Staking => ['bg-emerald-500/12 text-emerald-500', 'money'],
                    \App\Enums\TransactionType::ReferralCredit => ['bg-violet-500/12 text-violet-500', 'users'],
                    \App\Enums\TransactionType::Refund => ['bg-sky-500/12 text-sky-500', 'undo'],
                    \App\Enums\TransactionType::Chargeback => ['bg-rose-500/12 text-rose-500', 'ban'],
                    \App\Enums\TransactionType::Fee => ['bg-rose-500/12 text-rose-500', 'money'],
                    default => ['bg-slate-500/12 text-slate-500', 'ellipsis'],
                    };

                    $statusStyle = match($status) {
                    \App\Enums\TransactionStatus::Completed => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400',
                    \App\Enums\TransactionStatus::Pending => 'bg-amber-500/12 text-amber-600 dark:text-amber-400',
                    \App\Enums\TransactionStatus::Failed => 'bg-rose-500/12 text-rose-600 dark:text-rose-400',
                    \App\Enums\TransactionStatus::Reversed => 'bg-slate-500/12 text-slate-600 dark:text-slate-400',
                    };
                    @endphp
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <span class="grid h-9 w-9 place-items-center rounded-xl {{ $typeStyle[0] }}">
                            <x-app-icon name="{{ $typeStyle[1] }}" class="h-[17px] w-[17px]" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <b class="block truncate text-[13px] text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $transaction->type->value)) }}</b>
                            <small class="text-[11px] text-slate-500 dark:text-slate-400">{{ time_ago($transaction->created_at) }}</small>
                        </span>
                        <div class="shrink-0 text-right">
                            <b class="block text-sm {{ $transaction->direction->value === 'credit' ? 'text-emerald-600 dark:text-emerald-500' : 'text-slate-900 dark:text-white' }}">
                                {{ $transaction->direction->value === 'credit' ? '+' : '-' }}{{ money_format($transaction->amount, $transaction->currency) }}
                            </b>
                            <span class="mt-1 inline-block rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $statusStyle }}">
                                {{ ucfirst($status->value) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <x-ui.empty-state
                        icon="clock"
                        title="No transactions yet"
                        subtitle="Transactions will show up here." />
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            {{-- Asset allocation --}}
            <section class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Investment allocation</h2>

                @if($this->investmentAllocation->isEmpty())
                <x-ui.empty-state
                    icon="clock"
                    title="No active investments yet"
                    subtitle="Investments will show up here." />
                @else
                <div class="mt-4 space-y-3">
                    @foreach($this->investmentAllocation as $investment)
                    <x-investment-row :investment="$investment" wire:key="investment-{{ $investment->id }}" />
                    @endforeach
                </div>
                @endif
            </section>

            {{-- Recent trades --}}
            <section class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Recent trades</h2>
                @if($this->recentTrades->isNotEmpty())
                <div class="custom-scroll mt-1 flex-1 overflow-y-auto pr-1">
                    @foreach($this->recentTrades as $trade)
                    <div class="flex items-center gap-3 border-t pt-3 mt-3 border-slate-100 dark:border-white/[.08]">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-[10px] font-bold text-white {{ $trade->side->value === 'buy' ? 'bg-emerald-500' : 'bg-rose-500' }}">
                            {{ strtoupper(substr($trade->side->value, 0, 1)) }}
                        </span>
                        <span class="min-w-0 flex-1 text-xs">
                            <b class="block truncate text-sm text-slate-900 dark:text-white">{{ ucfirst($trade->side->value) }} {{ $trade->tradingPair->baseCurrency->code }}</b>
                            <small class="text-slate-500 dark:text-slate-400">{{ time_ago($trade->created_at) }}</small>
                        </span>
                        <span class="shrink-0 text-right text-xs">
                            <b class="block text-slate-900 dark:text-white">{{ number_format($trade->amount, 4) }}</b>
                            <small class="text-slate-500 dark:text-slate-400">{{ money_format($trade->total) }}</small>
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <x-ui.empty-state
                    icon="clock"
                    title="No trades yet"
                    subtitle="Your trade history will appear here." />
                @endif
            </section>
        </aside>
    </div>
</div>