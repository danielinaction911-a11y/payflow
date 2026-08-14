<div>
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Account activity</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Transaction history</h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Every deposit, withdrawal, trade, and transfer on your account.</p>
        </div>
    </section>
     <x-ui.dashboard-tabs />

    {{-- Filters --}}
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="tab-group">
            <button wire:click="setTypeFilter('all')" class="tab-btn {{ $typeFilter === 'all' ? 'is-active' : '' }}">
                All types
            </button>
            @foreach($this->typeOptions as $type)
                <button wire:click="setTypeFilter('{{ $type->value }}')" class="tab-btn {{ $typeFilter === $type->value ? 'is-active' : '' }}">
                    {{ $type->label() }}
                </button>
            @endforeach
        </div>

        <div class="flex items-center gap-2 rounded-xl border px-3 py-2 border-slate-200 bg-white dark:border-white/[.09] dark:bg-white/[.045]">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle></svg>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="Search reference..." class="w-40 bg-transparent text-xs outline-none text-slate-900 placeholder:text-slate-400 dark:text-white sm:w-56">
        </div>
    </div>

    <div class="tab-group">
        <button wire:click="setStatusFilter('all')" class="tab-btn {{ $statusFilter === 'all' ? 'is-active' : '' }}">
            All
        </button>
        @foreach($this->statusOptions as $status)
            <button wire:click="setStatusFilter('{{ $status->value }}')" class="tab-btn {{ $statusFilter === $status->value ? 'is-active' : '' }}">
                {{ ucfirst($status->value) }}
            </button>
        @endforeach
    </div>

    {{-- List --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
        @forelse($this->transactions as $transaction)
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

            <button wire:click="view({{ $transaction->id }})" wire:key="txn-{{ $transaction->id }}" class="flex w-full items-center gap-3 border-b p-4 sm:p-5 text-left transition last:border-b-0 border-slate-100 hover:bg-slate-50 dark:border-white/[.06] dark:hover:bg-white/[.03]">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $typeStyle[0] }}">
                    <x-app-icon :name="$typeStyle[1]" class="h-4 w-4" />
                </span>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <b class="truncate text-sm text-slate-900 dark:text-white">{{ $transaction->description ?: $type->label() }}</b>
                    </div>
                    <small class="text-xs text-slate-500 dark:text-slate-400">{{ $transaction->reference }} · {{ time_ago($transaction->created_at) }}</small>
                </div>

                <div class="shrink-0 text-right">
                    <b class="block text-sm {{ $transaction->direction->value === 'credit' ? 'text-emerald-600 dark:text-emerald-500' : 'text-slate-900 dark:text-white' }}">
                        {{ $transaction->direction->value === 'credit' ? '+' : '-' }}{{ money_format($transaction->amount, $transaction->currency) }}
                    </b>
                    <span class="mt-1 inline-block rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $statusStyle }}">
                        {{ ucfirst($status->value) }}
                    </span>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-slate-300 dark:text-slate-600"><path d="m9 18 6-6-6-6"></path></svg>
            </button>
        @empty
            <div class="flex flex-col items-center p-14 text-center">
                <span class="grid h-12 w-12 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                    <x-app-icon name="receipt-text" class="h-5 w-5 text-slate-400" />
                </span>
                <b class="mt-3 text-sm text-slate-900 dark:text-white">No transactions found</b>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Try adjusting your filters or search.</p>
            </div>
        @endforelse
    </div>

    @if($this->transactions->hasPages())
        <div class="mt-4">
            {{ $this->transactions->links('vendor.pagination.custom') }}
        </div>
    @endif

    {{-- Slide-over details --}}
    <div
        x-show="$wire.viewing"
        x-cloak
        class="fixed inset-0 z-50 flex justify-end bg-slate-900/60 backdrop-blur-sm"
        x-transition.opacity
        @click.self="$wire.closeDetails()"
        @keydown.escape.window="$wire.closeDetails()"
    >
        <div
            x-show="$wire.viewing"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="h-full w-full max-w-md overflow-y-auto border-l border-slate-200 bg-white shadow-2xl dark:border-white/[.08] dark:bg-[#0f172a]"
        >
            @if($this->selectedTransaction)
                @php
                    $t = $this->selectedTransaction;
                    $type = $t->type;
                    $status = $t->status;

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

                <div class="sticky top-0 z-10 flex items-center justify-between border-b p-5 border-slate-200 bg-white/90 backdrop-blur-xl dark:border-white/[.08] dark:bg-[#0f172a]/90">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Transaction details</h2>
                    <button wire:click="closeDetails" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                    </button>
                </div>

                <div class="p-5">
                    {{-- Icon + amount --}}
                    <div class="flex flex-col items-center text-center">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl {{ $typeStyle[0] }}">
                            <x-app-icon :name="$typeStyle[1]" class="h-6 w-6" />
                        </span>
                        <p class="mt-3 text-2xl font-semibold tracking-tight {{ $t->direction->value === 'credit' ? 'text-emerald-600 dark:text-emerald-500' : 'text-slate-900 dark:text-white' }}">
                            {{ $t->direction->value === 'credit' ? '+' : '-' }}{{ smart_money($t->amount, $t->currency) }}
                        </p>
                        <span class="mt-2 rounded-md px-2.5 py-1 text-xs font-semibold {{ $statusStyle }}">
                            {{ ucfirst($status->value) }}
                        </span>
                    </div>

                    {{-- Failed reason banner --}}
                    @if($status === \App\Enums\TransactionStatus::Failed && $t->failed_reason)
                        <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-sm text-rose-600 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-400">
                            {{ $t->failed_reason }}
                        </div>
                    @endif

                    {{-- Detail rows --}}
                    <div class="mt-6 space-y-1 divide-y divide-slate-100 rounded-xl border border-slate-200 dark:divide-white/[.06] dark:border-white/[.08]">
                        <div class="flex items-center justify-between p-3.5">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Reference</span>
                            <div class="flex items-center gap-1.5">
                                <b class="text-sm text-slate-900 dark:text-white">{{ $t->reference }}</b>
                                <button
                                    x-data="{ copied: false }"
                                    @click="navigator.clipboard.writeText('{{ $t->reference }}'); copied = true; setTimeout(() => copied = false, 1500)"
                                    class="text-slate-400 hover:text-emerald-500"
                                >
                                    <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path></svg>
                                    <svg x-show="copied" x-cloak xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3.5">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Type</span>
                            <b class="text-sm text-slate-900 dark:text-white">{{ $type->label() }}</b>
                        </div>

                        <div class="flex items-center justify-between p-3.5">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Direction</span>
                            <b class="text-sm text-slate-900 dark:text-white">{{ ucfirst($t->direction->value) }}</b>
                        </div>

                        <div class="flex items-center justify-between p-3.5">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Amount</span>
                            <b class="text-sm text-slate-900 dark:text-white">{{ smart_money($t->amount, $t->currency) }}</b>
                        </div>

                        @if($t->fee > 0)
                            <div class="flex items-center justify-between p-3.5">
                                <span class="text-xs text-slate-500 dark:text-slate-400">Fee</span>
                                <b class="text-sm text-slate-900 dark:text-white">{{ smart_money($t->fee, $t->currency) }}</b>
                            </div>
                        @endif

                        <div class="flex items-center justify-between p-3.5">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Currency</span>
                            <b class="text-sm text-slate-900 dark:text-white">{{ $t->currency }}</b>
                        </div>

                        @if($t->description)
                            <div class="flex items-center justify-between p-3.5">
                                <span class="text-xs text-slate-500 dark:text-slate-400">Description</span>
                                <b class="text-right text-sm text-slate-900 dark:text-white">{{ $t->description }}</b>
                            </div>
                        @endif

                        <div class="flex items-center justify-between p-3.5">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Date</span>
                            <b class="text-sm text-slate-900 dark:text-white">{{ $t->created_at->format('M j, Y · g:i A') }}</b>
                        </div>
                    </div> 
                </div>
            @endif
        </div>
    </div>
</div>

