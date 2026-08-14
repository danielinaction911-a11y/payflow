@props([
    'type' => 'info', // info | warning | success | danger
    'title' => null,
    'dismissible' => false,
    'actionLabel' => null,
    'actionUrl' => null,
])

@php
    $config = match ($type) {
        'success' => [
            'bg' => 'bg-emerald-50 dark:bg-emerald-500/10',
            'border' => 'border-emerald-200 dark:border-emerald-500/20',
            'iconBg' => 'bg-emerald-100 dark:bg-emerald-500/20',
            'iconColor' => 'text-emerald-600 dark:text-emerald-400',
            'titleColor' => 'text-emerald-900 dark:text-emerald-300',
            'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'warning' => [
            'bg' => 'bg-amber-50 dark:bg-amber-500/10',
            'border' => 'border-amber-200 dark:border-amber-500/20',
            'iconBg' => 'bg-amber-100 dark:bg-amber-500/20',
            'iconColor' => 'text-amber-600 dark:text-amber-400',
            'titleColor' => 'text-amber-900 dark:text-amber-300',
            'icon' => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
        ],
        'danger' => [
            'bg' => 'bg-red-50 dark:bg-red-500/10',
            'border' => 'border-red-200 dark:border-red-500/20',
            'iconBg' => 'bg-red-100 dark:bg-red-500/20',
            'iconColor' => 'text-red-600 dark:text-red-400',
            'titleColor' => 'text-red-900 dark:text-red-300',
            'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        ],
        default => [
            'bg' => 'bg-blue-50 dark:bg-blue-500/10',
            'border' => 'border-blue-200 dark:border-blue-500/20',
            'iconBg' => 'bg-blue-100 dark:bg-blue-500/20',
            'iconColor' => 'text-blue-600 dark:text-blue-400',
            'titleColor' => 'text-blue-900 dark:text-blue-300',
            'icon' => 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
        ],
    };
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="relative flex items-start gap-3 rounded-2xl border {{ $config['bg'] }} {{ $config['border'] }} px-4 py-3.5 sm:px-5 sm:py-4"
>
    <div class="flex-shrink-0 w-9 h-9 rounded-xl {{ $config['iconBg'] }} flex items-center justify-center">
        <svg class="w-5 h-5 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}" />
        </svg>
    </div>

    <div class="flex-1 min-w-0 pt-0.5">
        @if ($title)
            <p class="text-sm font-semibold {{ $config['titleColor'] }} mb-0.5">
                {{ $title }}
            </p>
        @endif

        <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            {{ $slot }}
        </div>

        @if ($actionLabel && $actionUrl)
            
             <a href="{{ $actionUrl }}"
                class="inline-flex items-center gap-1.5 mt-3 text-sm font-semibold {{ $config['titleColor'] }} hover:underline"
            >
                {{ $actionLabel }}
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        @endif
    </div>

    @if ($dismissible)
        <button
            @click="show = false"
            type="button"
            class="flex-shrink-0 -mr-1 -mt-1 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-black/5 dark:hover:bg-white/5 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>