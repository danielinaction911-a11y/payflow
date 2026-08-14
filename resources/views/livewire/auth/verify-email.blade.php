<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="rounded-[28px] border p-6 shadow-2xl sm:p-8
    border-slate-200 bg-white
    dark:border-white/[.08] dark:bg-[#111a2d]">

    <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">
        {{ setting('site_name', 'Account') }}
    </p>
    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Verify your email</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
        Please verify your email address by clicking on the link we just emailed to you.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 rounded-xl border px-4 py-3 text-center text-sm font-medium
            border-emerald-200 bg-emerald-50 text-emerald-700
            dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3">
        <button
            wire:click="sendVerification"
            wire:loading.attr="disabled"
            wire:target="sendVerification"
            class="flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold
                primary-button
                shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400
                disabled:cursor-not-allowed disabled:opacity-70">
            <svg wire:loading wire:target="sendVerification" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span wire:loading.remove wire:target="sendVerification">{{ __('Resend verification email') }}</span>
            <span wire:loading wire:target="sendVerification">{{ __('Sending...') }}</span>
        </button>

        <button
            wire:click="logout"
            wire:loading.attr="disabled"
            wire:target="logout"
            type="button"
            class="text-sm font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition">
            {{ __('Log out') }}
        </button>
    </div>
</section>
