<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <x-ui.loading />
    <div class="min-h-screen p-4 sm:p-8
    bg-slate-50 text-slate-900
    dark:bg-[#090f1e] dark:text-slate-100">

        <div class="mx-auto flex max-w-6xl justify-between">
            <a class="flex items-center gap-2 text-sm font-semibold" wire:navigate href="{{ route('home') }}">
                <x-app-logo />
            </a>
            <x-ui.theme-switch class="rounded-xl p-2" />
        </div>

        <div class="mx-auto grid min-h-[calc(100vh-100px)] max-w-6xl items-center gap-12 py-10 lg:grid-cols-[1fr_440px]">
            <section class="hidden lg:block">
                <span class="text-xs font-semibold uppercase tracking-[.2em] text-emerald-600 dark:text-emerald-500">
                    Secure wealth platform
                </span>

                <h1 class="mt-4 max-w-lg text-5xl font-semibold leading-tight tracking-tight text-slate-900 dark:text-white">
                    A confident place for every financial move.
                </h1>

                <p class="mt-5 max-w-md text-base leading-relaxed text-slate-600 dark:text-slate-400">
                    Manage cash, trade assets, and build long-term wealth from one beautifully protected account.
                </p>

                <div class="mt-10 flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
                    <svg
                        xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-emerald-600 dark:text-emerald-500 shrink-0" aria-hidden="true">
                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                    Bank-grade security with clear, human controls.
                </div>
            </section>
            {{ $slot }}
        </div>
    </div>
    @fluxScripts
</body>

</html>