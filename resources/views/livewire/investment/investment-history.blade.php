<div>
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Portfolio</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Investment history</h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Track every investment and its profit payouts.</p>
        </div>
        <a href="{{ route('investments.index') }}" wire:navigate class="primary-button w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            New investment
        </a>
    </section>

    {{-- Status filter tabs --}}
    <div class="tab-group">
        @foreach(['all' => 'All', 'active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
        <button
            wire:click="setStatusFilter('{{ $value }}')"
            class="tab-btn {{ $statusFilter === $value ? 'is-active' : '' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Investment list --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
        @forelse($this->investments as $investment)
        <button
            wire:click="view({{ $investment->id }})"
            wire:loading.attr="disabled"
            wire:target="view({{ $investment->id }})"
            class="flex w-full items-center gap-4 border-b p-4 sm:p-5 text-left transition last:border-b-0
                    border-slate-100 hover:bg-slate-50 dark:border-white/[.06] dark:hover:bg-white/[.03]">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-violet-500/12 text-violet-500">
                <x-app-icon name="sparkles" class="h-5 w-5" />
            </span>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <b class="truncate text-sm text-slate-900 dark:text-white">{{ $investment->plan->name ?? 'Deleted plan' }}</b>
                    <span class="shrink-0 rounded-md px-2 py-0.5 text-[10px] font-semibold
                        {{ match($investment->status) {
                            \App\Enums\InvestmentStatus::Active => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400',
                            \App\Enums\InvestmentStatus::Completed => 'bg-sky-500/12 text-sky-600 dark:text-sky-400',
                            \App\Enums\InvestmentStatus::Cancelled => 'bg-rose-500/12 text-rose-600 dark:text-rose-400',
                            default => 'bg-slate-500/12 text-slate-600 dark:text-slate-400', // covers a null status column
                        } }}">
                        {{ $investment->status?->value ? ucfirst($investment->status->value) : 'Pending' }}
                    </span>
                </div>
                <small class="text-xs text-slate-500 dark:text-slate-400">
                    Started {{ $investment->starts_at->format('M j, Y') }} · Matures {{ $investment->ends_at->format('M j, Y') }}
                </small>
            </div>

            <div class="shrink-0 text-right">
                <b class="block text-sm text-slate-900 dark:text-white">{{ money_format($investment->amount_invested) }}</b>
                <small class="text-xs text-emerald-600 dark:text-emerald-500">{{ money_format($investment->total_paid_out) }} paid</small>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-slate-300 dark:text-slate-600">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
        </button>
        @empty
        <div class="flex flex-col items-center p-14 text-center">
            <span class="grid h-12 w-12 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                <x-app-icon name="sparkles" class="h-5 w-5 text-slate-400" />
            </span>
            <b class="mt-3 text-sm text-slate-900 dark:text-white">No investments found</b>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Start your first investment plan to see it here.</p>
        </div>
        @endforelse
    </div>

    @if($this->investments->hasPages())
    <div class="mt-4">
        {{ $this->investments->links('vendor.pagination.custom') }}
    </div>
    @endif

    {{-- Details panel (slide-over) --}}
    <div
        x-show="$wire.viewing"
        x-cloak
        class="fixed inset-0 z-50 flex justify-end bg-slate-900/60 backdrop-blur-sm"
        x-transition.opacity
        @click.self="$wire.closeDetails()"
        @keydown.escape.window="$wire.closeDetails()">
        <div
            x-show="$wire.viewing"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="h-full w-full max-w-lg overflow-y-auto border-l border-slate-200 bg-white shadow-2xl dark:border-white/[.08] dark:bg-[#0f172a]">
            @if($this->selectedInvestment)
            @php $inv = $this->selectedInvestment; @endphp

            <div class="sticky top-0 z-10 flex items-center justify-between border-b p-5 border-slate-200 bg-white/90 backdrop-blur-xl dark:border-white/[.08] dark:bg-[#0f172a]/90">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $inv->plan->name ?? 'Investment' }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Investment #{{ $inv->id }}</p>
                </div>
                <button wire:click="closeDetails" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5">
                {{-- Summary cards --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Amount invested</span>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ money_format($inv->amount_invested) }}</p>
                    </div>
                    <div class="rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Expected return</span>
                        <p class="mt-1 text-lg font-semibold text-emerald-600 dark:text-emerald-500">{{ money_format($inv->expected_total_return) }}</p>
                    </div>
                    <div class="rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Total paid out</span>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ money_format($this->totalPaidOut) }}</p>
                    </div>
                    <div class="rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Status</span>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ ucfirst($inv->status->value ?? $inv->status) }}</p>
                    </div>
                </div>

                {{-- Progress bar --}}
                @php
                $totalDays = $inv->starts_at->diffInDays($inv->ends_at) ?: 1;
                $elapsedDays = min($inv->starts_at->diffInDays(now()), $totalDays);
                $progress = round(($elapsedDays / $totalDays) * 100);
                @endphp
                <div class="mt-5">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Progress to maturity</span>
                        <span class="font-medium text-slate-900 dark:text-white">{{ $progress }}%</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-white/[.06]">
                        <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="mt-1.5 flex justify-between text-[11px] text-slate-400 dark:text-slate-500">
                        <span>{{ $inv->starts_at->format('M j') }}</span>
                        <span>{{ $inv->ends_at->format('M j, Y') }}</span>
                    </div>
                </div>

                {{-- Profit logs --}}
                <div class="mt-7">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Profit payouts</h3>

                    @if($this->profitLogs && $this->profitLogs->isNotEmpty())
                    <div class="mt-3 divide-y divide-slate-100 rounded-xl border border-slate-200 dark:divide-white/[.06] dark:border-white/[.08]">
                        @foreach($this->profitLogs as $log)
                        <div class="flex items-center gap-3 p-3.5">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg
                                            {{ $log->status === 'paid' ? 'bg-emerald-500/12 text-emerald-500' : ($log->status === 'failed' ? 'bg-rose-500/12 text-rose-500' : 'bg-amber-500/12 text-amber-500') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v20"></path>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <b class="block text-sm text-slate-900 dark:text-white">{{ money_format($log->amount) }}</b>
                                <small class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $log->paid_at ? $log->paid_at->format('M j, Y · g:i A') : $log->created_at->format('M j, Y · g:i A') }}
                                </small>
                            </div>
                            <span class="shrink-0 rounded-md px-2 py-1 text-[10px] font-semibold
                                            {{ $log->status === 'paid' ? 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400' : ($log->status === 'failed' ? 'bg-rose-500/12 text-rose-600 dark:text-rose-400' : 'bg-amber-500/12 text-amber-600 dark:text-amber-400') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    @if($this->profitLogs->hasPages())
                    <div class="mt-3">
                        {{ $this->profitLogs->links() }}
                    </div>
                    @endif
                    @else
                    <div class="mt-3 flex flex-col items-center rounded-xl border border-dashed p-8 text-center border-slate-300 dark:border-white/[.12]">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.05]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400">
                                <path d="M12 2v20"></path>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </span>
                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">No profit payouts yet. Check back once your investment starts accruing.</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>