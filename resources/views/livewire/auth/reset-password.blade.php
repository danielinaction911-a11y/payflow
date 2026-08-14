<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PasswordReset) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<section class="rounded-[28px] border p-6 shadow-2xl sm:p-8
    border-slate-200 bg-white
    dark:border-white/[.08] dark:bg-[#111a2d]">

    <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">
        {{ setting('site_name', 'Account') }}
    </p>
    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Reset password</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Please enter your new password below.</p>

    <x-auth-session-status class="mt-4 text-center" :status="session('status')" />

    <form wire:submit="resetPassword" class="mt-6 flex flex-col gap-6">
        <x-ui.input
            wire:model="email"
            id="email"
            label="{{ __('Email') }}"
            type="email"
            name="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
            error="email" />

        <x-ui.password
            wire:model="password"
            id="password"
            label="{{ __('Password') }}"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Password"
            error="password" />

        <x-ui.password
            wire:model="password_confirmation"
            id="password_confirmation"
            label="{{ __('Confirm password') }}"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Confirm password"
            error="password_confirmation" />

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="resetPassword"
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold
                primary-button
                shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400
                disabled:cursor-not-allowed disabled:opacity-70">
            <svg wire:loading wire:target="resetPassword" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span wire:loading.remove wire:target="resetPassword">{{ __('Reset password') }}</span>
            <span wire:loading wire:target="resetPassword">{{ __('Resetting...') }}</span>
        </button>
    </form>
</section>
