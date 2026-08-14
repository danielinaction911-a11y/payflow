<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

         $user = Auth::user();

        // Only enforce 2FA if the platform setting is on AND this specific
        // user has actually confirmed 2FA on their own account. A user with
        // a secret but no confirmed_at timestamp hasn't finished setup, so
        // they should not be challenged.
        if (
            setting('two_factor_authentication', true)
            && $user->two_factor_secret
            && $user->two_factor_confirmed_at
        ) {
            // Log them back out of the "full" authenticated session — they
            // are only considered a "pending 2FA" user until they pass the
            // challenge, matching Fortify's own two-factor flow semantics.
            Auth::logout();

            Session::put('login.id', $user->getKey());
            Session::put('login.remember', $this->remember);

            $this->redirect(route('two-factor.login'), navigate: true);
            return;
        }
        
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<section class="rounded-[28px] border p-6 shadow-2xl sm:p-8
    border-slate-200 bg-white
    dark:border-white/[.08] dark:bg-[#111a2d]">

    <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">
        {{ setting('site_title', 'Account') }}
    </p>
    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Welcome back</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Enter your details to access your account.</p>

    <x-auth-session-status class="mt-4 text-center" :status="session('status')" />

    <form wire:submit="login" class="mt-6 flex flex-col gap-4">
        <x-ui.input
            wire:model="email"
            label="{{ __('Email address') }}"
            type="email"
            name="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
            error="email" />

        <x-ui.password
            wire:model="password"
            label="{{ __('Password') }}"
            name="password"
            required
            autocomplete="current-password"
            placeholder="Password"
            error="password" />

        <div class="flex items-center justify-between">
            <flux:checkbox wire:model="remember" label="{{ __('Remember me') }}" />

            @if (Route::has('password.request'))
            <a class="text-xs font-semibold text-emerald-600 dark:text-emerald-500"
                href="{{ route('password.request') }}"
                wire:navigate>
                {{ __('Forgot your password?') }}
            </a>
            @endif
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold
                primary-button
                shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400
                disabled:cursor-not-allowed disabled:opacity-70">
            <span wire:loading.remove wire:target="login">{{ __('Log in') }}</span>
            <span wire:loading wire:target="login">{{ __('Logging in...') }}</span>
        </button>
    </form>

    <div class="my-6 border-t border-slate-200 dark:border-white/[.08]"></div>

    <p class="text-center text-sm text-slate-500 dark:text-slate-400">
        New to {{ setting('site_name', 'us') }}? 
        <a class="font-semibold text-emerald-600 dark:text-emerald-500"
            href="{{ route('register') }}"
            wire:navigate>
            Create account
        </a>
    </p>
</section>