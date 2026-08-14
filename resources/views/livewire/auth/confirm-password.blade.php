<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<section class="rounded-[28px] border p-6 shadow-2xl sm:p-8
    border-slate-200 bg-white
    dark:border-white/[.08] dark:bg-[#111a2d]">

    <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">
        {{ setting('site_name', 'Account') }}
    </p>
    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Confirm password</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <x-auth-session-status class="mt-4 text-center" :status="session('status')" />

    <form wire:submit="confirmPassword" class="mt-6 flex flex-col gap-6">
        <x-ui.password
            wire:model="password"
            id="password"
            label="{{ __('Password') }}"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Password"
            error="password" />

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="confirmPassword"
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold
                primary-button
                shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400
                disabled:cursor-not-allowed disabled:opacity-70">
            <svg wire:loading wire:target="confirmPassword" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span wire:loading.remove wire:target="confirmPassword">{{ __('Confirm') }}</span>
            <span wire:loading wire:target="confirmPassword">{{ __('Confirming...') }}</span>
        </button>
    </form>
</section>
