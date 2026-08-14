<div>
    <section class="mb-7">
        <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-500">Internal transfers</p>
        <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Receive money</h1>
        <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Let another member scan this to send you money instantly.</p>
    </section>
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
        <section class="rounded-2xl border p-5 sm:p-7 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90 text-center">
                <div class="qr-wrap">
                    {!! $qrSvg !!}
                </div>

                <p class="qr-code-label">Your username</p>
                <div class="qr-code-value" x-data="{ copied: false }">
                    <span>{{ '@' . $username }}</span>
                    <button
                        type="button"
                        @click="navigator.clipboard.writeText('{{ $username }}'); copied = true; setTimeout(() => copied = false, 1500)"
                        class="qr-copy-btn">
                        <span x-show="!copied">Copy</span>
                        <span x-show="copied" x-cloak>Copied</span>
                    </button>
                </div>

                <p class="mt-5 text-center text-xs text-slate-500 dark:text-slate-400">
                    Anyone who scans this can send you money to your account.
                </p>
        </section>

        <section class="flex flex-col rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-slate-900 dark:text-white">Recent incoming</h2>
                @if($this->recentIncoming->isNotEmpty())
                <a href="{{ route('transfer.history') }}" wire:navigate class="text-xs font-semibold text-emerald-600 hover:text-emerald-500 dark:text-emerald-500 dark:hover:text-emerald-400">
                    View all
                </a>
                @endif
            </div>

            @if($this->recentIncoming->isEmpty())
            <div class="flex flex-1 items-center justify-center">
                <x-ui.empty-state
                    icon="inbox"
                    title="Nothing yet"
                    subtitle="Money sent to you will show up here."
                    compact />
            </div>
            @else
            <div class="mt-5 space-y-4">
                @foreach($this->recentIncoming as $transfer)
                @php
                $sender = $transfer->sender;
                $senderLabel = $sender ? ($sender->name ?? '@' . $sender->username) : 'Member';
                $initial = strtoupper(substr($sender->name ?? $sender->username ?? 'M', 0, 1));
                $when = $transfer->created_at->isToday()
                ? 'Today'
                : $transfer->created_at->format('M j');
                @endphp
                <div class="flex gap-3" wire:key="incoming-{{ $transfer->id }}">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-500/12 text-xs font-bold text-emerald-500">
                        {{ $initial }}
                    </span>
                    <span class="min-w-0 flex-1">
                        <b class="block truncate text-sm text-slate-900 dark:text-white">{{ $senderLabel }}</b>
                        <small class="text-slate-500 dark:text-slate-400">{{ $when }}</small>
                    </span>
                    <b class="shrink-0 text-sm text-emerald-500">+{{ money_format($transfer->amount) }}</b>
                </div>
                @endforeach
            </div>
            @endif
        </section>
    </div>
</div>