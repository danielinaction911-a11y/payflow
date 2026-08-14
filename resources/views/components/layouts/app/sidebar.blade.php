<aside
    class="fixed inset-y-0 left-0 z-50 flex w-[258px] flex-col border-r backdrop-blur-xl transition-transform duration-300
        border-slate-200 bg-white/95
        dark:border-white/[.08] dark:bg-[#111a2d]/90
        lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex h-[76px] items-center border-b px-5 border-slate-200 dark:border-white/[.08]">
        <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3">
            <x-app-logo />
        </a>
        <button @click="sidebarOpen = false" class="ml-auto rounded-lg p-1.5 lg:hidden hover:bg-slate-100 dark:hover:bg-white/[.06]">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"></path>
                <path d="m6 6 12 12"></path>
            </svg>
        </button>
    </div>

    <div class="custom-scroll flex-1 overflow-y-auto px-3 py-5">
        <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[.16em] text-slate-500 dark:text-slate-400">Workspace</p>

        <nav class="space-y-1">
            @php
            $navItems = [
            ['label' => 'Dashboard', 'route' => 'home', 'icon' => 'house'],
            ['label' => 'Trade', 'route' => 'trade.index', 'icon' => 'chart-line'],
            ['label' => 'Investment Plans', 'route' => 'investments.index', 'icon' => 'sparkles'],
            ['label' => 'Investment History', 'route' => 'investments.history', 'icon' => 'history'],
            ['label' => 'Wallet', 'route' => 'wallet.index', 'icon' => 'wallet'],
            ['label' => 'Deposit', 'route' => 'deposit.index', 'icon' => 'arrow-down-left'],
            ['label' => 'Withdraw', 'route' => 'withdraw.index', 'icon' => 'arrow-up-right'],
            ['label' => 'Send Money', 'route' => 'transfer.index', 'icon' => 'send'],
            ['label' => 'Receive Money', 'route' => 'receive.index', 'icon' => 'arrow-down'],
            ['label' => 'Request Money', 'route' => 'requests.index', 'icon' => 'hand-coins'],
            ['label' => 'Transaction History', 'route' => 'transactions.index', 'icon' => 'receipt-text'],
            ['label' => 'Analytics', 'route' => 'analytics.index', 'icon' => 'trending-up'],
            ['label' => 'Referral Program', 'route' => 'referrals.index', 'icon' => 'gift'],
            ];
            @endphp

            @foreach($navItems as $item)
            @php
            $isActive = Route::has($item['route']) && request()->routeIs($item['route']);
            @endphp

            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                @if(Route::has($item['route'])) wire:navigate @endif
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition
                        {{ $isActive
                            ? 'bg-emerald-500 text-white shadow-[0_8px_20px_rgba(16,185,129,.2)]'
                            : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/[.06] dark:hover:text-white' }}">
                <x-app-icon :name="$item['icon']" class="h-[18px] w-[18px] shrink-0" />
                <span class="truncate">{{ $item['label'] }}</span>
                @if($isActive)
                <i class="ml-auto h-1.5 w-1.5 rounded-full bg-white"></i>
                @endif
            </a>
            @endforeach
        </nav>

        <div class="my-5 border-t border-slate-200 dark:border-white/[.08]"></div>

        <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[.16em] text-slate-500 dark:text-slate-400">Account</p>

        <nav class="space-y-1">
            @php
            $accountItems = [
            ['label' => 'Notifications', 'route' => 'notifications.index', 'icon' => 'bell'],
            ['label' => 'Privacy & Security', 'route' => 'security.index', 'icon' => 'shield-check'],
            ['label' => 'Settings', 'route' => 'profile.index', 'icon' => 'settings'],
            ['label' => 'Help & Support', 'route' => 'support.index', 'icon' => 'life-buoy'],
            ['label' => 'Legal center', 'route' => 'legal.index', 'icon' => 'file-text'],
            ];
            @endphp

            @foreach($accountItems as $item)
            @php
            $isActive = Route::has($item['route']) && request()->routeIs($item['route']);
            @endphp

            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                @if(Route::has($item['route'])) wire:navigate @endif
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium transition
                        {{ $isActive
                            ? 'bg-emerald-500 text-white shadow-[0_8px_20px_rgba(16,185,129,.2)]'
                            : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/[.06] dark:hover:text-white' }}">
                <x-app-icon :name="$item['icon']" class="h-[18px] w-[18px] shrink-0" />
                <span class="truncate">{{ $item['label'] }}</span>
                @if($isActive)
                <i class="ml-auto h-1.5 w-1.5 rounded-full bg-white"></i>
                @endif
            </a>
            @endforeach
        </nav>
    </div>

    <div class="border-t p-3 border-slate-200 dark:border-white/[.08]">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium text-rose-500 hover:bg-rose-50 dark:hover:bg-red-500/10">
                <x-app-icon name="log-out" class="h-[18px] w-[18px]" />
                <span>Log out</span>
            </button>
        </form>
    </div>
</aside>