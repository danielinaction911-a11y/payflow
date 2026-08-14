<div>
    @if ($accessBlocked)
    <div class="max-w-md mx-auto text-center py-16 px-6">
        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
            Wallets Unavailable
        </h2>

        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            {{ $blockedMessage }}
        </p>

        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-medium">
            Back to Dashboard
        </a>
    </div>
    @else
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Assets</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">My Wallets</h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Manage your balances across every supported currency.</p>
        </div>

        <button
            type="button"
            wire:click="openCreateModal"
            @if($this->availableCurrencies->isEmpty()) disabled @endif
            class="primary-button w-fit disabled:cursor-not-allowed disabled:opacity-50"
            >
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            Create wallet
        </button>
    </section>

    {{-- Empty state --}}
    @if($this->wallets->isEmpty())
    <div class="grid place-items-center rounded-2xl border border-dashed p-14 text-center border-slate-300 bg-slate-50 dark:border-white/[.12] dark:bg-white/[.02]">
        <span class="grid h-14 w-14 place-items-center rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-white/[.05]">
            <x-app-icon name="wallet" class="h-6 w-6 text-slate-400" />
        </span>
        <p class="mt-4 text-base font-semibold text-slate-900 dark:text-white">No wallets yet</p>
        <p class="mt-1 max-w-xs text-sm text-slate-500 dark:text-slate-400">Create your first wallet to start depositing funds and trading.</p>
        <button type="button" wire:click="openCreateModal" class="primary-button mt-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            Create wallet
        </button>
    </div>
    @else
    {{-- Wallet grid --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($this->wallets as $wallet)
        <div class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex items-center gap-3">
                @if($wallet->currency->icon && file_exists(public_path($wallet->currency->icon)))
                <span class="grid h-11 w-11 shrink-0 place-items-center overflow-hidden rounded-xl bg-slate-100 dark:bg-white/10">
                    <img src="{{ asset($wallet->currency->icon) }}" alt="{{ $wallet->currency->code }}" class="h-full w-full object-contain p-1.5">
                </span>
                @else
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-emerald-500/12 text-sm font-bold text-emerald-600 dark:text-emerald-500">
                    {{ strtoupper(substr($wallet->currency->code, 0, 1)) }}
                </span>
                @endif

                <div class="min-w-0 flex-1">
                    <h4 class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $wallet->currency->code }}</h4>
                    <span class="block truncate text-xs text-slate-500 dark:text-slate-400">{{ $wallet->currency->name }}</span>
                </div>

                @if($wallet->currency->network)
                <span class="shrink-0 rounded-lg bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-500 dark:bg-white/[.06] dark:text-slate-400">
                    {{ $wallet->currency->network }}
                </span>
                @endif

                @if($wallet->is_primary)
                <span class="shrink-0 rounded-lg bg-emerald-500/12 px-2 py-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
                    Primary
                </span>
                @endif
            </div>

            <div class="mt-5 rounded-xl border p-4 border-slate-100 bg-slate-50 dark:border-white/[.06] dark:bg-white/[.03]">
                <span class="text-xs text-slate-500 dark:text-slate-400">Balance</span>
                <h3 class="mt-1 text-xl font-semibold tracking-tight text-slate-900 dark:text-white">
                    {{ number_format($wallet->available, $wallet->currency->type === 'fiat' ? 2 : 8) }}
                    <span class="text-sm font-medium text-slate-400 dark:text-slate-500">{{ $wallet->currency->code }}</span>
                </h3>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <a href="{{ route('wallet.swap', ['walletId' => $wallet->id, 'mode' => 'deposit']) }}" wire:navigate class="flex items-center justify-center gap-1.5 rounded-xl border py-2.5 text-xs font-semibold transition
        border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100
        dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/15">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 7 7 17"></path>
                        <path d="M17 17H7V7"></path>
                    </svg>
                    Deposit
                </a>
                <a href="{{ route('wallet.swap', ['walletId' => $wallet->id, 'mode' => 'withdraw']) }}" wire:navigate class="flex items-center justify-center gap-1.5 rounded-xl border py-2.5 text-xs font-semibold transition
        border-orange-200 bg-orange-50 text-orange-700 hover:bg-orange-100
        dark:border-orange-500/20 dark:bg-orange-500/10 dark:text-orange-400 dark:hover:bg-orange-500/15">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 7h10v10"></path>
                        <path d="M7 17 17 7"></path>
                    </svg>
                    Withdraw
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Create Wallet Modal --}}
    <div
        x-data="{ selectedCurrency: null }"
        x-show="$wire.showCreateModal"
        x-cloak
        class="fixed inset-0 z-50 grid place-items-center bg-slate-900/60 p-4 backdrop-blur-sm"
        x-transition.opacity
        @click.self="$wire.closeCreateModal()"
        @keydown.escape.window="$wire.closeCreateModal()">
        <div
            x-show="$wire.showCreateModal"
            x-transition
            class="w-full max-w-md rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Create new wallet</h3>
                <button type="button" wire:click="closeCreateModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            @if($this->availableCurrencies->isEmpty())
            <div class="mt-6 flex flex-col items-center text-center">
                <span class="grid h-12 w-12 place-items-center rounded-full bg-emerald-500/12 text-emerald-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                </span>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">You already have a wallet for every available currency.</p>
            </div>
            @else
            <div class="mt-5">
                <x-ui.label>Select currency</x-ui.label>
                <div class="relative mt-2">
                    <select
                        wire:model.live="selectedCurrencyId"
                        x-on:change="selectedCurrency = {{ Js::from($this->availableCurrencies->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'code' => $c->code, 'network' => $c->network, 'icon' => $c->icon ? asset($c->icon) : null])) }}.find(c => c.id == $event.target.value) || null"
                        class="w-full appearance-none rounded-xl border px-3 py-3 pr-9 text-sm outline-none border-slate-200 bg-slate-50 text-slate-900 focus:border-emerald-500 dark:border-white/10 dark:bg-white/5 dark:text-white">
                        <option value="">-- Choose a currency --</option>
                        @foreach($this->availableCurrencies as $currency)
                        <option value="{{ $currency->id }}">
                            {{ $currency->name }} ({{ $currency->code }}){{ $currency->network ? ' - ' . $currency->network : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @error('selectedCurrencyId') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div x-show="selectedCurrency" x-cloak class="mt-4 flex items-center gap-3 rounded-xl border p-3.5 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                <template x-if="selectedCurrency?.icon">
                    <img :src="selectedCurrency.icon" class="h-10 w-10 rounded-xl object-contain bg-white p-1.5" alt="">
                </template>
                <template x-if="!selectedCurrency?.icon">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-500/12 text-sm font-bold text-emerald-600 dark:text-emerald-500" x-text="selectedCurrency ? selectedCurrency.code.charAt(0) : ''"></span>
                </template>
                <div>
                    <strong class="block text-sm text-slate-900 dark:text-white" x-text="selectedCurrency?.name"></strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400" x-text="selectedCurrency ? selectedCurrency.code + (selectedCurrency.network ? ' · ' + selectedCurrency.network : '') : ''"></span>
                </div>
            </div>

            <button
                type="button"
                wire:click="createWallet"
                wire:loading.attr="disabled"
                wire:target="createWallet"
                :disabled="!$wire.selectedCurrencyId"
                class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-50">
                <span wire:loading.remove wire:target="createWallet">Create wallet</span>
                <span wire:loading wire:target="createWallet">Creating...</span>
            </button>
            @endif
        </div>
    </div>
    @endif
</div>