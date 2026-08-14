<div>
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Inbox</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Notifications</h1>
            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                @if($this->unreadCount > 0)
                    You have {{ $this->unreadCount }} unread {{ Str::plural('notification', $this->unreadCount) }}.
                @else
                    You're all caught up.
                @endif
            </p>
        </div>

        @if($this->unreadCount > 0)
            <button wire:click="markAllRead" wire:loading.attr="disabled" wire:target="markAllRead" class="w-fit rounded-xl border px-4 py-2.5 text-sm font-semibold border-slate-200 bg-slate-50 hover:bg-slate-100 dark:border-white/[.08] dark:bg-white/[.035] dark:hover:bg-white/[.06] dark:text-white">
                Mark all as read
            </button>
        @endif
    </section>

    {{-- Filter tabs --}}
    <div class="tab-group">
        <button wire:click="setFilter('all')" class="tab-btn {{ $filter === 'all' ? 'is-active' : '' }}">
            All
        </button>
        <button wire:click="setFilter('unread')" class="tab-btn {{ $filter === 'unread' ? 'is-active' : '' }}">
            Unread
            @if($this->unreadCount > 0)
                <span class="ml-1 rounded-full bg-rose-500 px-1.5 py-0.5 text-[9px] text-white">{{ $this->unreadCount }}</span>
            @endif
        </button>
    </div>

    {{-- List --}}
    @if($this->notifications->isEmpty())
        <div class="flex flex-col items-center rounded-2xl border p-14 text-center border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <span class="grid h-12 w-12 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                <x-app-icon name="bell" class="h-5 w-5 text-slate-400" />
            </span>
            <b class="mt-3 text-sm text-slate-900 dark:text-white">
                {{ $filter === 'unread' ? "You're all caught up" : 'No notifications yet' }}
            </b>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                {{ $filter === 'unread' ? 'New notifications will appear here.' : "We'll notify you when something happens." }}
            </p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($this->grouped as $group => $items)
                <div>
                    <p class="mb-2 px-1 text-[10px] font-bold uppercase tracking-[.14em] text-slate-400 dark:text-slate-500">{{ $group }}</p>

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                        @foreach($items as $notification)
                            <div
                                wire:key="notif-{{ $notification->id }}"
                                x-data="{ showConfirm: false }"
                                @keydown.escape.window="showConfirm = false"
                                class="group flex items-start gap-3 border-b p-4 last:border-b-0 transition
                                    border-slate-100 dark:border-white/[.06]
                                    {{ ! $notification->is_read ? 'bg-emerald-50/40 dark:bg-emerald-500/[.04]' : '' }}"
                            >
                                @php
                                    $typeStyle = match($notification->type) {
                                        'success' => ['bg-emerald-500/12 text-emerald-500', 'circle-check'],
                                        'warning' => ['bg-amber-500/12 text-amber-500', 'triangle-alert'],
                                        'error' => ['bg-rose-500/12 text-rose-500', 'circle-x'],
                                        default => ['bg-sky-500/12 text-sky-500', 'info'],
                                    };
                                @endphp

                                @if($notification->image)
                                    <img src="{{ asset($notification->image) }}" alt="" class="h-10 w-10 shrink-0 rounded-xl object-cover">
                                @else
                                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $typeStyle[0] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            @if($notification->type === 'success')
                                                <circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path>
                                            @elseif($notification->type === 'warning')
                                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path>
                                            @elseif($notification->type === 'error')
                                                <circle cx="12" cy="12" r="10"></circle><path d="m15 9-6 6"></path><path d="m9 9 6 6"></path>
                                            @else
                                                <circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path>
                                            @endif
                                        </svg>
                                    </span>
                                @endif

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <b class="text-sm text-slate-900 dark:text-white">{{ $notification->title }}</b>
                                        @if(! $notification->is_read)
                                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $notification->body }}</p>
                                    <small class="mt-1.5 block text-xs text-slate-400 dark:text-slate-500">{{ $notification->created_at->format('g:i A') }}</small>
                                </div>

                                <div class="flex shrink-0 items-center gap-1 opacity-100 sm:opacity-0 transition sm:group-hover:opacity-100 group-focus-within:opacity-100">
                                    @if(! $notification->is_read)
                                        <button wire:click="markAsRead({{ $notification->id }})" title="Mark as read" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-emerald-500 dark:hover:bg-white/[.06]">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
                                        </button>
                                    @endif
                                    <button type="button" @click="showConfirm = true" title="Delete" class="rounded-lg p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-500 dark:hover:bg-rose-500/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>

                                    <!-- Alpine confirmation modal -->
                                    <div x-show="showConfirm" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center">
                                        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" x-show="showConfirm" x-transition.opacity></div>

                                        <div class="relative max-w-md w-full mx-4" x-show="showConfirm" x-transition.scale>
                                            <div class="rounded-2xl bg-white dark:bg-[#0b1220] border p-5 shadow-lg dark:border-white/[.06]">
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Delete notification</h3>
                                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Are you sure you want to delete this notification? This action cannot be undone.</p>

                                                <div class="mt-4 flex justify-end gap-2">
                                                    <button type="button" @click="showConfirm = false" class="link-button">Cancel</button>
                                                    <button type="button" @click.prevent="$wire.delete({{ $notification->id }}).then(() =&gt; showConfirm = false)" wire:loading.attr="disabled" class="primary-button">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @if($this->notifications->hasPages())
            <div class="mt-6">
                {{ $this->notifications->links('vendor.pagination.custom') }}
            </div>
        @endif
    @endif
</div>