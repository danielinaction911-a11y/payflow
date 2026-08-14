<div wire:poll.15s="refreshPrice">
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
    <section class="mb-7">
        <p class="text-xs font-semibold uppercase tracking-[.16em] {{ $mode === 'withdraw' ? 'text-orange-600 dark:text-orange-400' : 'text-emerald-600 dark:text-emerald-500' }}">Swap</p>
        <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
            {{ $mode === 'deposit' ? 'Deposit to wallet' : 'Withdraw from wallet' }}
        </h1>
        <p class="mt-1.5 max-w-lg text-sm text-slate-500 dark:text-slate-400">
            {{ $mode === 'deposit'
                ? 'Convert your USD balance into crypto at live market rates.'
                : 'Convert your crypto wallet balance back to USD at live market rates.' }}
        </p>
    </section>

    <div class="mx-auto max-w-lg rounded-2xl border p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">

        {{-- Mode toggle --}}
        <div class="swap-toggle">
            <button type="button" wire:click="switchMode('deposit')" class="swap-toggle-btn is-deposit {{ $mode === 'deposit' ? 'is-active' : '' }}">
                Deposit
            </button>
            <button type="button" wire:click="switchMode('withdraw')" class="swap-toggle-btn is-withdraw {{ $mode === 'withdraw' ? 'is-active' : '' }}">
                Withdraw
            </button>
        </div>

        @if($mode === 'deposit')
        <div class="mt-4 flex justify-between rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <span class="text-sm text-slate-500 dark:text-slate-400">Available USD balance</span>
            <strong class="text-sm text-slate-900 dark:text-white">{{ money_format(auth()->user()->balance) }}</strong>
        </div>
        @endif

        @if(empty($wallets))
        <div class="mt-6 flex flex-col items-center rounded-xl border border-dashed p-8 text-center border-slate-300 dark:border-white/[.12]">
            <span class="grid h-12 w-12 place-items-center rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-white/[.05]">
                <x-app-icon name="wallet" class="h-5 w-5 text-slate-400" />
            </span>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                {{ $mode === 'deposit'
                        ? 'You need at least one crypto wallet before you can deposit. Create one from the Wallets page first.'
                        : 'No wallets are currently eligible for withdrawal.' }}
            </p>
            <a href="{{ route('wallet.index') }}" wire:navigate class="primary-button mt-4">Go to Wallets</a>
        </div>
        @else
        {{-- Wallet select --}}
        <div class="mt-5">
            <x-ui.label>{{ $mode === 'deposit' ? 'Deposit into' : 'Withdraw from' }}</x-ui.label>
            <div class="relative mt-2">
                <select wire:model.live="walletId" class="w-full appearance-none rounded-xl border px-3 py-3 pr-9 text-sm outline-none border-slate-200 bg-slate-50 text-slate-900 focus:border-emerald-500 dark:border-white/10 dark:bg-white/5 dark:text-white">
                    @foreach($wallets as $wallet)
                    <option value="{{ $wallet['wallet_id'] }}">
                        {{ $wallet['code'] }} — {{ $wallet['name'] }} ({{ number_format($wallet['available'], 6) }} available)
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($mode === 'withdraw' && $this->selectedWallet)
        <div class="mt-4 flex justify-between rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <span class="text-sm text-slate-500 dark:text-slate-400">Available in wallet</span>
            <strong class="text-sm text-slate-900 dark:text-white">{{ number_format($this->selectedWallet['available'], 8) }} {{ $this->selectedWallet['code'] }}</strong>
        </div>
        @endif

        {{-- Live rate --}}
        <div wire:loading.class="opacity-50" wire:target="refreshPrice,walletId" class="mt-4 flex items-center justify-between rounded-xl border p-3.5 transition
                border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <span class="flex items-center gap-1.5 text-xs font-medium {{ $mode === 'withdraw' ? 'text-orange-600 dark:text-orange-400' : 'text-emerald-600 dark:text-emerald-500' }}">
                <i class="h-1.5 w-1.5 rounded-full animate-pulse {{ $mode === 'withdraw' ? 'bg-orange-500' : 'bg-emerald-500' }}"></i> Live rate
            </span>
            <strong class="text-sm text-slate-900 dark:text-white">1 {{ $this->selectedWallet['code'] ?? '' }} = {{ money_format($price) }}</strong>
        </div>

        {{-- USD amount --}}
        <div class="mt-5">
            <x-ui.label>Amount (USD)</x-ui.label>
            <div class="mt-2 flex items-center rounded-xl border px-4 border-slate-200 bg-slate-50 focus-within:border-emerald-500 dark:border-white/10 dark:bg-white/5 dark:focus-within:border-emerald-400">
                <span class="text-lg font-semibold text-slate-400 dark:text-slate-500">$</span>
                <input type="text" inputmode="decimal" wire:model.live.debounce.400ms="usdAmount" placeholder="0.00" class="w-full bg-transparent py-3.5 text-xl font-bold outline-none text-slate-900 placeholder:text-slate-300 dark:text-white dark:placeholder:text-slate-600">
            </div>
        </div>

        <div class="mt-3 grid grid-cols-4 gap-2 amount-shortcuts">
            @foreach([25, 50, 75, 100] as $percent)
            <button type="button" wire:click="setPercent({{ $percent }})" class="rounded-lg py-1.5 text-[10px] font-medium border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 dark:border-white/[.07] dark:bg-white/[.035] dark:hover:bg-white/[.06] dark:text-slate-300">
                {{ $percent === 100 ? 'Max' : $percent . '%' }}
            </button>
            @endforeach
        </div>

        {{-- Conversion preview --}}
        @if($usdAmount && $price > 0)
        <div class="mt-5 flex items-center gap-3 rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <div class="flex-1 text-center">
                <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $mode === 'deposit' ? 'You pay' : 'You withdraw' }}</span>
                <strong class="mt-1 block text-sm text-slate-900 dark:text-white">
                    @if($mode === 'deposit')
                    {{ money_format((float) $usdAmount) }}
                    @else
                    {{ number_format($this->cryptoAmount, 8) }} {{ $this->selectedWallet['code'] ?? '' }}
                    @endif
                </strong>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-slate-400">
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
            </svg>
            <div class="flex-1 text-center">
                <span class="block text-xs text-slate-500 dark:text-slate-400">You receive</span>
                <strong class="mt-1 block text-sm text-emerald-600 dark:text-emerald-500">
                    @if($mode === 'deposit')
                    {{ number_format($this->cryptoAmount, 8) }} {{ $this->selectedWallet['code'] ?? '' }}
                    @else
                    {{ money_format((float) $usdAmount) }}
                    @endif
                </strong>
            </div>
        </div>
        @endif

        @if($error)
        <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-3 text-sm text-rose-600 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-400">
            {{ $error }}
        </div>
        @endif

        @if($success)
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ $success }}
        </div>
        @endif

        <button
            type="button"
            wire:click="submit"
            wire:loading.attr="disabled"
            wire:target="submit"
            class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition disabled:cursor-not-allowed disabled:opacity-70
                    {{ $mode === 'withdraw' ? '!bg-orange-500 hover:!bg-orange-400' : '!bg-emerald-500 hover:!bg-emerald-400' }}">
            <span wire:loading.remove wire:target="submit">{{ $mode === 'deposit' ? 'Deposit now' : 'Withdraw now' }}</span>
            <span wire:loading wire:target="submit">Processing...</span>
        </button>
        @endif
    </div>
    @endif
</div>