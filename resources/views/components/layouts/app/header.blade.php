<header class="sticky top-0 z-30 h-[76px] border-b backdrop-blur-xl border-slate-200 bg-white/80 dark:border-white/[.06] dark:bg-[#090f1e]/75">
    <div class="flex h-full items-center justify-between px-4 sm:px-6 lg:px-9">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="rounded-xl p-2 lg:hidden hover:bg-slate-100 dark:hover:bg-white/[.06]">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 5h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 19h16"></path>
                </svg>
            </button>

            <div class="hidden lg:block">
                <p class="text-[11px] text-slate-500 dark:text-slate-400">Overview</p>
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $title ?? 'Dashboard' }}</h2>
            </div>
        </div>

        <div class="flex items-center gap-1.5 sm:gap-2" x-data="{ notifOpen: false, profileOpen: false }">
            <x-ui.theme-switch class="relative icon-button rounded-xl" />
            <div class="relative">
                <button @click="notifOpen = !notifOpen; profileOpen = false" class="relative rounded-xl p-2.5 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <x-app-icon name="bell" class="h-[18px] w-[18px] text-slate-600 dark:text-slate-300" />
                    @php
                    $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                    <span class="absolute -right-1 -top-1 flex h-4.5 min-w-[18px] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold leading-none text-white ring-2 ring-white dark:ring-[#090f1e]">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                    @endif
                </button>

                <div
                    x-show="notifOpen" x-cloak @click.outside="notifOpen = false"
                    x-transition
                    class="absolute right-0 top-12 w-80 rounded-2xl border p-2 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/95">
                    <div class="flex justify-between px-3 py-2">
                        <b class="text-sm text-slate-900 dark:text-white">Notifications</b>
                        <a href="{{ Route::has('notifications.index') ? route('notifications.index') : '#' }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-500">View all</a>
                    </div>

                    @forelse(auth()->user()->notifications()->where('is_read', false)->latest()->limit(4)->get() as $notification)
                    <a href="{{ Route::has('notifications.index') ? route('notifications.index') : '#' }}"  wire:navigate class="flex w-full items-start gap-3 rounded-xl p-3 text-left hover:bg-slate-50 dark:hover:bg-white/[.06]">
                        <i class="mt-1.5 h-2 w-2 rounded-full bg-emerald-400 shrink-0"></i>
                        <span>
                            <b class="block text-[13px] font-medium text-slate-900 dark:text-white">{{ $notification->title }}</b>
                            <small class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400">{{ time_ago($notification->created_at) }}</small>
                        </span>
                    </a>
                    @empty 
                    <x-ui.empty-state
                        icon="bell"
                        title="No notifications yet"  />
                    @endforelse
                </div>
            </div>

            <div class="relative ml-1">
                <button @click="profileOpen = !profileOpen; notifOpen = false" class="flex items-center gap-2 rounded-xl p-1 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    @if (auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar)))
                    <img src="{{ auth()->user()->profileImageUrl() }}" alt="{{ auth()->user()->name }}"
                        class="w-8 h-8 rounded-full object-cover ring-2 ring-green-400 dark:ring-green-500" />
                    @else
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-gradient-to-br from-sky-400 to-violet-500 text-[11px] font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                    @endif
                </button>

                <div
                    x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                    x-transition
                    class="absolute right-0 top-12 w-56 rounded-2xl border p-2 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/95">
                    <div class="border-b px-3 py-2.5 border-slate-200 dark:border-white/[.08]">
                        <b class="block text-sm text-slate-900 dark:text-white">{{ auth()->user()->name }}</b>
                        <small class="text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</small>
                    </div>

                    <a href="{{ route('profile.index') }}" wire:navigate class="mt-1 flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-white/[.06]">
                        <x-app-icon name="user-round" class="h-4 w-4" /> Profile
                    </a>
                    <a href="{{ route('security.index') }}" wire:navigate class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-white/[.06]">
                        <x-app-icon name="shield-check" class="h-4 w-4" /> Security
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mt-1 flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm text-rose-500 hover:bg-rose-50 dark:hover:bg-red-500/10">
                            <x-app-icon name="log-out" class="h-4 w-4" /> Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>