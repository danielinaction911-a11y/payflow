<nav class="fixed inset-x-0 bottom-0 z-30 flex h-[68px] items-center justify-around border-t px-2 backdrop-blur-xl lg:hidden border-slate-200 bg-white/95 dark:border-white/[.08] dark:bg-[#111a2d]/90">
    <a href="{{ route('home') }}" wire:navigate class="flex min-w-[50px] flex-col items-center gap-1 rounded-lg px-2 py-1.5 text-[10px] font-medium {{ request()->routeIs('home') ? 'text-emerald-600 dark:text-emerald-500' : 'text-slate-500 dark:text-slate-400' }}">
        <x-app-icon name="house" class="h-[19px] w-[19px]" /> Dashboard
    </a>
    <a href="{{ Route::has('wallet.index') ? route('wallet.index') : '#' }}" class="flex min-w-[50px] flex-col items-center gap-1 rounded-lg px-2 py-1.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
        <x-app-icon name="wallet-cards" class="h-[19px] w-[19px]" /> Wallet
    </a>
    <a href="{{ Route::has('deposit.index') ? route('deposit.index') : '#' }}" class="flex min-w-[50px] flex-col items-center gap-1 rounded-lg px-2 py-1.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
        <span class="-mt-5 grid h-11 w-11 place-items-center rounded-2xl bg-emerald-500 text-white shadow-[0_8px_20px_rgba(16,185,129,.3)]">
            <x-app-icon name="plus" class="h-5 w-5" />
        </span>
        Deposit
    </a>
    <a href="{{ Route::has('trade.index') ? route('trade.index') : '#' }}" class="flex min-w-[50px] flex-col items-center gap-1 rounded-lg px-2 py-1.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
        <x-app-icon name="chart-line" class="h-[19px] w-[19px]" /> Trade
    </a>
    <a href="{{ route('profile.index') }}" wire:navigate class="flex min-w-[50px] flex-col items-center gap-1 rounded-lg px-2 py-1.5 text-[10px] font-medium text-slate-500 dark:text-slate-400">
        <x-app-icon name="settings" class="h-[19px] w-[19px]" /> Settings
    </a>
</nav>