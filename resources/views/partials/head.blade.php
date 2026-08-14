<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ setting('site_title', config('app.name', 'Laravel')) . (isset($title) && $title ? ' - ' . $title : '') }}
</title>

<meta name="description" content="{{ $description ?? setting('seo_meta_description', setting('site_description', '')) }}">
@if(setting('seo_meta_keywords'))
<meta name="keywords" content="{{ setting('seo_meta_keywords') }}">
@endif

{{-- Favicon --}}
@if(setting('site_favicon'))
<link rel="icon" type="image/png" href="{{ asset(setting('site_favicon')) }}">
@else
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
@endif

{{-- Open Graph / Social sharing --}}
<meta property="og:title" content="{{ $title ?? setting('seo_meta_title', setting('site_title', 'App')) }}">
<meta property="og:description" content="{{ $description ?? setting('seo_meta_description', setting('site_description', '')) }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
@if(setting('seo_og_image'))
<meta property="og:image" content="{{ asset(setting('seo_og_image')) }}">
@endif

{{-- Twitter card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? setting('seo_meta_title', setting('site_title', 'App')) }}">
<meta name="twitter:description" content="{{ $description ?? setting('seo_meta_description', setting('site_description', '')) }}">
@if(setting('seo_og_image'))
<meta name="twitter:image" content="{{ asset(setting('seo_og_image')) }}">
@endif

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

@php
$theme = auth()->check() && auth()->user()->default_theme
? auth()->user()->default_theme
: setting('default_theme', 'dark');
@endphp

<script>
    (function() {
        const root = document.documentElement;
        const theme = @json($theme);

        localStorage.setItem('flux.appearance', theme);

        root.classList.toggle('dark', theme === 'dark');
        root.classList.toggle('light', theme === 'light');
        root.style.colorScheme = theme === 'dark' ? 'dark' : 'light';

        if (window.__fluxAppearance) {
            window.__fluxAppearance = theme;
        }
    })();
</script>  

@fluxAppearance

{{-- Google Analytics --}}
@if(setting('google_analytics_id'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', '{{ setting('
        google_analytics_id ') }}');
</script>
@endif