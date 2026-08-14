<div wire:poll.10s="refreshPrice">
    @if ($accessBlocked)
    <div class="max-w-md mx-auto text-center py-16 px-6">
        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
            Trading Unavailable
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
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Markets</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Trade</h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Execute trades with live pricing and clear order controls.</p>
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_330px]">
        {{-- Chart + pair info --}}
        <section class="min-w-0 rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="relative flex flex-wrap justify-between gap-3 border-b p-4 sm:p-5 border-slate-200 dark:border-white/[.08]">
                <div wire:loading wire:target="selectPair" class="absolute inset-0 z-10 flex items-center gap-3 bg-white/80 px-4 backdrop-blur-sm dark:bg-[#111a2d]/80 sm:px-5">
                    <svg class="h-5 w-5 animate-spin text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Switching pair...</span>
                </div>

                <div wire:loading.remove wire:target="selectPair" class="flex gap-3">
                    @if($this->selectedPair->baseCurrency->icon && file_exists(public_path($this->selectedPair->baseCurrency->icon)))
                    <span class="grid h-10 w-10 shrink-0 place-items-center overflow-hidden rounded-xl bg-slate-100 dark:bg-white/10">
                        <img src="{{ asset($this->selectedPair->baseCurrency->icon) }}" alt="{{ $this->selectedPair->baseCurrency->code }}" class="h-full w-full object-contain p-1.5">
                    </span>
                    @else
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-orange-500/12 text-sm font-bold text-orange-500">
                        {{ substr($this->selectedPair->baseCurrency->code ?? '?', 0, 1) }}
                    </span>
                    @endif
                    <span>
                        <b class="text-slate-900 dark:text-white">{{ $this->selectedPair->symbol ?? '--' }}</b>
                        <small class="block text-xs text-slate-500 dark:text-slate-400">{{ $this->selectedPair->baseCurrency->name ?? '' }}</small>
                    </span>
                </div>

                <span wire:loading.remove wire:target="selectPair" class="sm:text-right">
                    <b class="text-slate-900 dark:text-white">{{ money_format($this->price) }}</b>
                    <small class="mt-0.5 block text-xs font-medium {{ $this->selectedPair->change_24h_percent >= 0 ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-500' }}">
                        {{ percentage_format($this->selectedPair->change_24h_percent ?? 0) }} today
                    </small>
                </span>
            </div>

            <div class="p-4 sm:p-5" wire:ignore
                x-data="{
            symbol: '{{ $this->selectedPair->symbol ?? 'BTCUSDT' }}',
            resizeTimer: null,
            lastWidth: window.innerWidth,

            init() {
                this.loadChart();

                Livewire.on('pair-changed', (event) => {
                    this.symbol = event.symbol;
                    this.loadChart();
                });

                window.addEventListener('resize', () => {
                    clearTimeout(this.resizeTimer);
                    this.resizeTimer = setTimeout(() => {
                        if (window.innerWidth !== this.lastWidth) {
                            this.lastWidth = window.innerWidth;
                            this.loadChart();
                        }
                    }, 300);
                });
            },

            getChartHeight() {
                const w = window.innerWidth;
                if (w <= 575) return 320;
                if (w <= 991) return 360;
                if (w <= 1279) return 400;
                return 420;
            },

            loadChart() {
                this.$refs.chartContainer.innerHTML = '';
                const script = document.createElement('script');
                script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js';
                script.async = true;
                script.innerHTML = JSON.stringify({
                    width: '100%',
                    height: this.getChartHeight(),
                    symbol: 'BINANCE:' + this.symbol,
                    interval: '60',
                    timezone: 'Etc/UTC',
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                    style: '1',
                    locale: 'en',
                    hide_top_toolbar: false,
                    hide_legend: true,
                    withdateranges: true,
                    range: '1D',
                    allow_symbol_change: false,
                });
                this.$refs.chartContainer.appendChild(script);
            }
        }">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Live Chart</h3>
                    <span class="flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-500">
                        <i class="h-1.5 w-1.5 rounded-full animate-pulse bg-emerald-500"></i> Live
                    </span>
                </div>
                <div x-ref="chartContainer"></div>
            </div>
        </section>

        {{-- Order panel --}}
       <section class="trade-card rounded-2xl border p-4 sm:p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div wire:loading.flex wire:target="placeOrder,setSide,setPercent" class="trade-loading-overlay">
                <span class="spinner-ring"></span>
            </div>

            <div class="buy-sell">
                <button type="button" wire:click="setSide('buy')" class="{{ $side === 'buy' ? 'selected buy' : '' }}">Buy</button>
                <button type="button" wire:click="setSide('sell')" class="{{ $side === 'sell' ? 'selected sell' : '' }}">Sell</button>
            </div>

            <label class="mt-5 block text-xs font-medium text-slate-500 dark:text-slate-400">Trading pair</label>
            <div class="relative mt-2">
                <select wire:change="selectPair($event.target.value)" class="w-full appearance-none rounded-xl border px-3 py-3 pr-9 text-sm outline-none border-slate-200 bg-slate-50 text-slate-900 focus:border-emerald-500 dark:border-white/10 dark:bg-white/5 dark:text-white">
                    @foreach($this->pairs as $pair)
                    <option value="{{ $pair->id }}" {{ $pairId == $pair->id ? 'selected' : '' }}>
                        {{ $pair->symbol }} — {{ $pair->baseCurrency->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            @if($this->needsFunding)
            <div class="mt-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3.5 dark:border-amber-500/20 dark:bg-amber-500/10">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-amber-500/15 text-amber-600 dark:text-amber-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    </svg>
                </span>
                <div class="flex-1">
                    <b class="block text-sm text-amber-800 dark:text-amber-300">
                        {{ $side === 'buy' ? 'You need ' . ($this->selectedPair->quoteCurrency->code ?? 'funds') : 'You need ' . ($this->selectedPair->baseCurrency->code ?? 'coins') }} to {{ $side }}
                    </b>
                    <p class="mt-0.5 text-xs text-amber-700 dark:text-amber-400/80">
                        Your {{ $side === 'buy' ? ($this->selectedPair->quoteCurrency->code ?? '') : ($this->selectedPair->baseCurrency->code ?? '') }} wallet balance is currently zero.
                        @if($side === 'buy')
                        Deposit funds first to place a buy order.
                        @else
                        You'll need to buy or deposit {{ $this->selectedPair->baseCurrency->code ?? 'this asset' }} before you can sell it.
                        @endif
                    </p>
                    @if($side === 'buy')
                    <a href="{{ route('deposit.index') }}" wire:navigate class="mt-2 inline-block text-xs font-semibold text-amber-800 underline dark:text-amber-300">
                        Go to Deposit →
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <label class="mt-5 block text-xs font-medium text-slate-500 dark:text-slate-400">
                Amount ({{ $this->selectedPair->baseCurrency->code ?? '' }})
            </label>
            <div class="mt-2 flex items-center rounded-xl border px-3 border-slate-200 bg-slate-50 focus-within:border-emerald-500 dark:border-white/10 dark:bg-white/5 dark:focus-within:border-emerald-400">
                <input type="text" inputmode="decimal" wire:model.live.debounce.400ms="amount" placeholder="0.00" class="w-full bg-transparent py-3 text-sm font-semibold outline-none text-slate-900 dark:text-white">
                <small class="shrink-0 text-slate-400 dark:text-slate-500">{{ $this->selectedPair->baseCurrency->code ?? '' }}</small>
            </div>

            <div class="mt-4 flex flex-wrap gap-2 amount-shortcuts">
                @foreach([25, 50, 75, 100] as $percent)
                <button type="button" wire:click="setPercent({{ $percent }})" class="rounded-lg py-1.5 text-[10px] font-medium border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 dark:border-white/[.07] dark:bg-white/[.035] dark:hover:bg-white/[.06] dark:text-slate-300">
                    {{ $percent === 100 ? 'Max' : $percent . '%' }}
                </button>
                @endforeach
            </div>

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

            <div class="mt-5 space-y-2 border-t pt-4 text-xs border-slate-200 dark:border-white/[.08]">
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Available</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        {{ number_format($this->availableBalance, 4) }} {{ $side === 'buy' ? ($this->selectedPair->quoteCurrency->code ?? '') : ($this->selectedPair->baseCurrency->code ?? '') }}
                    </span>
                </div>

                @if($amount && $this->price > 0)
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Price</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ money_format($this->price) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Fee ({{ $this->feePercent }}%)</span>
                    <span class="font-medium text-rose-500">{{ money_format($this->fee) }}</span>
                </div>
                <div class="flex justify-between border-t pt-2 text-sm font-semibold border-slate-200 dark:border-white/[.08]">
                    <span class="text-slate-900 dark:text-white">Total</span>
                    <span class="text-slate-900 dark:text-white">{{ money_format($this->total) }}</span>
                </div>
                @endif
            </div>

            <button
                type="button"
                wire:click="placeOrder"
                wire:loading.attr="disabled"
                wire:target="placeOrder"
                class="mt-5 w-full rounded-xl py-3 text-sm font-semibold text-white transition disabled:cursor-not-allowed disabled:opacity-70
                    {{ $side === 'buy' ? '!bg-emerald-500 hover:!bg-emerald-400' : '!bg-rose-500 hover:!bg-rose-400' }}">
                <span wire:loading.remove wire:target="placeOrder">
                    {{ ucfirst($side) }} {{ $this->selectedPair->baseCurrency->code ?? '' }}
                </span>
                <span wire:loading wire:target="placeOrder">Processing...</span>
            </button>
        </section>
    </div>  

    {{-- Market watch + recent trades --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <section class="flex h-[404px] flex-col rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Market watch</h2>
            </div> 

            <div class="custom-scroll mt-1 flex-1 overflow-y-auto pr-1">
                @foreach($this->pairs as $pair)
                <button wire:click="selectPair({{ $pair->id }})" class="mt-3 flex w-full items-center gap-3 border-t pt-3 text-left border-slate-100 dark:border-white/[.08]">
                    @if($pair->baseCurrency->icon && file_exists(public_path($pair->baseCurrency->icon)))
                    <span class="grid h-8 w-8 shrink-0 place-items-center overflow-hidden rounded-lg bg-slate-100 dark:bg-white/10">
                        <img src="{{ asset($pair->baseCurrency->icon) }}" alt="{{ $pair->baseCurrency->code }}" class="h-full w-full object-contain p-1">
                    </span>
                    @else
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-[10px] font-bold text-white bg-indigo-500">
                        {{ substr($pair->baseCurrency->code, 0, 1) }}
                    </span>
                    @endif
                    <span class="min-w-0 flex-1 text-xs">
                        <b class="block truncate text-sm text-slate-900 dark:text-white">{{ $pair->baseCurrency->code }}</b>
                        <small class="text-slate-500 dark:text-slate-400">{{ $pair->baseCurrency->name }}</small>
                    </span>
                    <span class="shrink-0 text-right text-xs">
                        <b class="block text-slate-900 dark:text-white">{{ money_format($pair->current_price) }}</b>
                        <small class="{{ $pair->change_24h_percent >= 0 ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-500' }}">{{ percentage_format($pair->change_24h_percent) }}</small>
                    </span>
                </button>
                @endforeach
            </div>
        </section>

        <section class="flex h-[404px] flex-col rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
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
            <div class="flex flex-1 flex-col items-center justify-center text-center">
                <span class="grid h-12 w-12 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400">
                        <path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"></path>
                        <path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"></path>
                        <path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"></path>
                    </svg>
                </span>
                <b class="mt-3 text-sm text-slate-900 dark:text-white">No trades yet</b>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Your trade history will appear here.</p>
            </div>
            @endif
        </section>
    </div>
    @endif
</div>