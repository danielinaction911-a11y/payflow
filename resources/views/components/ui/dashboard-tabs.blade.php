@php
$tabs = [
['label' => 'Dashboard', 'route' => 'home'],
['label' => 'History', 'route' => 'transactions.index'],
['label' => 'Analytics', 'route' => 'analytics.index'],
];
@endphp

<div class="mb-6 flex gap-1 overflow-x-auto border-b border-slate-200 dark:border-white/[.08]">
    @foreach($tabs as $tab)
    @php $isActive = Route::has($tab['route']) && request()->routeIs($tab['route']); @endphp

    <a href="{{ Route::has($tab['route']) ? route($tab['route']) : '#' }}"
        @if(Route::has($tab['route'])) wire:navigate @endif
        class="relative whitespace-nowrap px-4 py-2.5 text-sm font-medium transition
                {{ $isActive ? 'text-emerald-600 dark:text-emerald-500' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white' }}">
        {{ $tab['label'] }}
        @if($isActive)
        <i class="absolute inset-x-3 bottom-0 h-0.5 rounded-full bg-emerald-500 shadow-[0_0_9px_rgba(16,185,129,.85)]"></i>
        @endif
    </a>
    @endforeach
</div>