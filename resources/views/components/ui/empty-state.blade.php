@props([
    'icon' => 'inbox',   // 'inbox' | 'search' | 'bell' | 'shield' | 'clock'
    'title',
    'subtitle' => null,
    'compact' => false,  // tighter padding for use inside small panels/dropdowns
])

@php
    $icons = [
        'inbox' => '<path d="M22 12h-6l-2 3h-4l-2-3H2"></path><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11Z"></path>',
        'search' => '<path d="m21 21-4.34-4.34"></path><circle cx="11" cy="11" r="8"></circle>',
        'bell' => '<path d="M10.268 21a2 2 0 0 0 3.464 0"></path><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>',
        'shield' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.79 17 5 19 5a1 1 0 0 1 1 1z"></path>',
        'clock' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
    ];
    $path = $icons[$icon] ?? $icons['inbox'];
@endphp

<div class="empty-state {{ $compact ? 'empty-state-compact' : '' }}">
    <span class="empty-state-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $compact ? 18 : 22 }}" height="{{ $compact ? 18 : 22 }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
            {!! $path !!}
        </svg>
    </span>
    <p class="empty-state-title">{{ $title }}</p>
    @if($subtitle)
        <p class="empty-state-subtitle">{{ $subtitle }}</p>
    @endif
</div>