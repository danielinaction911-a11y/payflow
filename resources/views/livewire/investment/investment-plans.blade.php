<div>
    @if ($accessBlocked)
    <div class="max-w-md mx-auto text-center py-16 px-6">
        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
            Investments Unavailable
        </h2>

        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            {{ $blockedMessage }}
        </p>

        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-medium">
            Back to Dashboard
        </a>
    </div>
    @elseif($showSuccess && $successData)
    {{-- Success state — replaces the plan list --}}
    <div class="mx-auto max-w-lg rounded-2xl border p-8 text-center border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
        <span
            x-data="{ show: true }"
            x-init="$nextTick(() => show = true)"
            class="success-check"
            :class="{ 'success-check-animate': show }">
            <svg class="success-check-circle" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="29" stroke="currentColor" stroke-width="2.5" />
            </svg>
            <svg class="success-check-mark" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6 9 17l-5-5"></path>
            </svg>
        </span>

        <h2 class="mt-5 text-xl font-semibold text-slate-900 dark:text-white">Investment activated</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            You've successfully invested in the {{ $successData['plan_name'] }}. Your returns will begin accruing shortly.
        </p>

        <div class="mt-6 space-y-2 rounded-xl border p-4 text-left text-sm border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Plan</span><b class="text-slate-900 dark:text-white">{{ $successData['plan_name'] }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Amount invested</span><b class="text-slate-900 dark:text-white">{{ money_format($successData['amount']) }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Expected return</span><b class="text-emerald-600 dark:text-emerald-500">{{ money_format($successData['expected_return']) }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Duration</span><b class="text-slate-900 dark:text-white">{{ $successData['duration_days'] }} days</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Matures on</span><b class="text-slate-900 dark:text-white">{{ $successData['ends_at']->format('M j, Y') }}</b></div>
        </div>

        <div class="mt-6 flex flex-col gap-2">
            <a href="{{ route('home') }}" wire:navigate class="primary-button w-full justify-center">Return to dashboard</a>
            <button wire:click="investAgain" class="link-button">Invest in another plan</button>
        </div>
    </div>
    @else
    {{-- Plan list --}}
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Grow your wealth</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Investment plans</h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Purpose-built portfolios with transparent performance and risk levels.</p>
        </div>
        <div class="rounded-xl border px-4 py-2.5 text-sm border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <span class="text-slate-500 dark:text-slate-400">Balance</span>
            <b class="ml-2 text-slate-900 dark:text-white">{{ money_format(auth()->user()->balance) }}</b>
        </div>
    </section>

    <div class="grid gap-5 lg:grid-cols-3">
        @foreach($this->plans as $plan)
        <section class="relative overflow-hidden rounded-2xl border p-6 transition hover:-translate-y-1
                    {{ $plan->is_popular
                        ? 'border-emerald-500/50 bg-white shadow-[0_14px_35px_rgba(16,185,129,.12)] dark:bg-[#111a2d]/90'
                        : 'border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90' }}">

            @if($plan->is_popular)
            <span class="absolute right-0 top-0 rounded-bl-xl bg-emerald-500 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-white">
                Most popular
            </span>
            @endif

            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-sky-500/12 text-sky-500">
                <x-app-icon name="sparkles" class="h-5 w-5" />
            </span>

            <h2 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">{{ $plan->name }}</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $plan->description }}</p>

            <div class="my-5 border-t border-slate-200 dark:border-white/[.08]"></div>

            <small class="text-slate-500 dark:text-slate-400">Expected {{ strtolower($plan->roi_type->label()) }} return</small>
            <p class="mt-1 text-3xl font-semibold tracking-tight text-emerald-600 dark:text-emerald-500">{{ $plan->roi_percentage }}%</p>

            <div class="mt-6 space-y-3 text-xs">
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 dark:text-slate-400">Investment window</span>
                    <span class="text-right font-medium text-slate-900 dark:text-white">{{ $plan->duration_days }} days</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 dark:text-slate-400">Investment range</span>
                    <span class="text-right font-medium text-slate-900 dark:text-white">{{ money_format($plan->min_amount) }} - {{ money_format($plan->max_amount) }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-500 dark:text-slate-400">Capital return</span>
                    <span class="text-right font-medium text-slate-900 dark:text-white">{{ $plan->capital_back ? 'At maturity' : 'Not returned' }}</span>
                </div>
            </div>

            @if($plan->features)
            <ul class="mt-5 space-y-2 border-t pt-5 border-slate-200 dark:border-white/[.08]">
                @foreach($plan->features as $feature)
                <li class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 shrink-0 text-emerald-500">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
            @endif

            <button
                wire:click="openInvestModal({{ $plan->id }})"
                class="mt-6 w-full rounded-xl py-3 text-sm font-semibold transition
                            {{ $plan->is_popular
                                ? 'btn btn-primary'
                                : 'btn btn-secondary' }}">
                Invest in {{ $plan->name }}
            </button>
        </section>
        @endforeach
    </div>
    @endif

    {{-- Invest amount modal --}}
    <div
        x-show="$wire.showInvestModal"
        x-cloak
        class="fixed inset-0 z-50 grid place-items-center bg-slate-900/60 p-4 backdrop-blur-sm"
        x-transition.opacity
        @click.self="$wire.closeInvestModal()"
        @keydown.escape.window="$wire.closeInvestModal()">
        <div x-show="$wire.showInvestModal" x-transition class="w-full max-w-md rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Invest in {{ $this->selectedPlan->name ?? '' }}</h3>
                <button type="button" wire:click="closeInvestModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            @if($this->selectedPlan)
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Range: {{ money_format($this->selectedPlan->min_amount) }} - {{ money_format($this->selectedPlan->max_amount) }}
            </p>

            <div class="mt-5">
                <x-ui.label>Amount to invest</x-ui.label>
                <div class="mt-2 flex items-center rounded-xl border px-4 transition
        {{ $amount !== '' && $this->amountError ? 'border-rose-300 dark:border-rose-500/50' : 'border-slate-200 dark:border-white/10' }}
        bg-slate-50 focus-within:border-emerald-500 dark:bg-white/5 dark:focus-within:border-emerald-400">
                    <span class="text-lg font-semibold text-slate-400 dark:text-slate-500">$</span>
                    <input type="text" inputmode="decimal" wire:model.live.debounce.300ms="amount" placeholder="0.00" class="w-full bg-transparent py-3.5 text-xl font-bold outline-none text-slate-900 placeholder:text-slate-300 dark:text-white dark:placeholder:text-slate-600">
                </div>

                @if($amount !== '' && $this->amountError)
                <p class="mt-1.5 text-xs text-rose-500">{{ $this->amountError }}</p>
                @endif
            </div>

            @if($this->isAmountValid && $this->expectedReturn > 0)
            <div class="mt-4 flex justify-between rounded-xl border p-3.5 text-sm border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                <span class="text-slate-500 dark:text-slate-400">Expected return</span>
                <b class="text-emerald-600 dark:text-emerald-500">{{ money_format($this->expectedReturn) }}</b>
            </div>
            @endif

            @if($error)
            <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-3 text-sm text-rose-600 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-400">
                {{ $error }}
            </div>
            @endif

            <button
                type="button"
                wire:click="invest"
                wire:loading.attr="disabled"
                wire:target="invest"
                @disabled(! $this->isAmountValid)
                class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-50"
                >
                <span wire:loading.remove wire:target="invest">Confirm investment</span>
                <span wire:loading wire:target="invest">Processing...</span>
            </button>
            @endif
        </div>
    </div>
</div>