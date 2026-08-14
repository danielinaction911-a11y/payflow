<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body>
    <x-ui.loading />
    <div
        x-data="{ sidebarOpen: false }"
        class="min-h-screen overflow-x-hidden font-sans transition-colors duration-300
            bg-slate-50 text-slate-900
            dark:bg-[#090f1e] dark:text-slate-100">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -left-40 -top-44 h-[32rem] w-[32rem] rounded-full bg-emerald-500/[.075] blur-[120px]"></div>
            <div class="absolute -right-20 top-72 h-80 w-80 rounded-full bg-blue-500/[.055] blur-[100px]"></div>
        </div>

        <div
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
            x-show="sidebarOpen" x-cloak
            x-transition.opacity
            @click="sidebarOpen = false"></div>

        <x-layouts.app.sidebar />

        <main class="relative min-h-screen transition-[margin] duration-300 lg:ml-[258px]">
            <x-layouts.app.header />
            <livewire:layout.live-activity-ticker />
            @php
            $user = auth()->user();
            $kycVerified = in_array($user->kyc_status, ['approved', 'verified']);
            @endphp

            @if (setting('require_kyc', true) && ! $kycVerified)
            <div class="mb-2 mt-6 px-4 sm:px-6 lg:px-9">
                <x-ui.alert
                    type="warning"
                    title="Verify your identity"
                    action-label="Complete KYC"
                    :action-url="route('profile.index')">
                    Complete your identity verification to unlock full access to your account. This only takes a few minutes.
                </x-ui.alert>
            </div>
            @endif
            <div class="mx-auto max-w-[1540px] px-4 pb-28 pt-6 sm:px-6 lg:px-9 lg:pb-12 lg:pt-8">
                {{ $slot }}
            </div>
        </main>

        <x-layouts.app.bottom-nav />
    </div>
    <x-ui.toast />
    <x-live-chat />
    @fluxScripts
</body>

</html>