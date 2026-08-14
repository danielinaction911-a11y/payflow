<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>
<section class="rounded-[28px] border p-6 shadow-2xl sm:p-8
    border-slate-200 bg-white
    dark:border-white/[.08] dark:bg-[#111a2d]">

    <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">
        {{ setting('site_name', 'Account') }}
    </p>
    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Forgot password</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Enter your email to receive a password reset link.</p>

    <x-auth-session-status class="mt-4 text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="mt-6 flex flex-col gap-6">
        <x-ui.input
            wire:model="email"
            label="{{ __('Email Address') }}"
            type="email"
            name="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
            error="email" />

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="sendPasswordResetLink"
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold
                primary-button
                shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400
                disabled:cursor-not-allowed disabled:opacity-70">
            <svg wire:loading wire:target="sendPasswordResetLink" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span wire:loading.remove wire:target="sendPasswordResetLink">{{ __('Email password reset link') }}</span>
            <span wire:loading wire:target="sendPasswordResetLink">{{ __('Sending link...') }}</span>
        </button>
    </form>

    <div class="my-6 border-t border-slate-200 dark:border-white/[.08]"></div>

    <p class="text-center text-sm text-slate-500 dark:text-slate-400">
        Remembered your password?
        <x-text-link href="{{ route('login') }}" class="font-semibold text-emerald-600 dark:text-emerald-500">
            Log in
        </x-text-link>
    </p>
</section>
