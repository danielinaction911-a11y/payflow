<?php

use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use PragmaRX\Google2FA\Google2FA;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string')]
    public string $code = '';

    public bool $useRecoveryCode = false;

    #[Validate('required|string')]
    public string $recoveryCode = '';

    public function mount(): void
    {
        if (! Session::has('login.id')) {
            $this->redirect(route('login'), navigate: true);
        }
    }

    public function verifyCode(): void
    {
        $this->validate(['code' => 'required|string']);

        $user = User::find(Session::get('login.id'));

        if (! $user) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->two_factor_secret,
            $this->code
        );

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => __('The provided two-factor code was invalid.'),
            ]);
        }

        $this->completeLogin($user);
    }

    public function verifyRecoveryCode(): void
    {
        $this->validate(['recoveryCode' => 'required|string']);

        $user = User::find(Session::get('login.id'));

        if (! $user) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $recoveryCodes = $user->two_factor_recovery_codes;

        if (is_string($recoveryCodes)) {
            $recoveryCodes = json_decode($recoveryCodes, true) ?? [];
        }

        if (! is_array($recoveryCodes)) {
            $recoveryCodes = [];
        }

        $matchedCode = collect($recoveryCodes)->first(
            fn ($recoveryCode) => hash_equals($recoveryCode, $this->recoveryCode)
        );

        if (! $matchedCode) {
            throw ValidationException::withMessages([
                'recoveryCode' => __('The provided recovery code was invalid.'),
            ]);
        }

        // Recovery codes are single-use — remove the one just used.
        $remaining = array_values(array_diff($recoveryCodes, [$matchedCode]));
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($remaining)),
        ])->save();

        $this->completeLogin($user);
    }

    protected function completeLogin(User $user): void
    {
        Auth::login($user, Session::pull('login.remember', false));

        Session::forget('login.id');
        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }

    public function toggleRecoveryMode(): void
    {
        $this->useRecoveryCode = ! $this->useRecoveryCode;
        $this->resetErrorBag();
        $this->code = '';
        $this->recoveryCode = '';
    }
}; ?>

<section class="rounded-[28px] border p-6 shadow-2xl sm:p-8
    border-slate-200 bg-white
    dark:border-white/[.08] dark:bg-[#111a2d]">

    <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">
        {{ setting('site_name', 'Account') }}
    </p>

    <div class="mt-2 flex items-center gap-3">
        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-emerald-500/12 text-emerald-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path></svg>
        </span>
        <div>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Two-factor check</h2>
        </div>
    </div>

    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
        @if(! $useRecoveryCode)
            Enter the 6-digit code from your authenticator app to continue.
        @else
            Enter one of your recovery codes to continue.
        @endif
    </p>

    <x-auth-session-status class="mt-4 text-center" :status="session('status')" />

    @if(! $useRecoveryCode)
        <form wire:submit="verifyCode" class="mt-6 flex flex-col gap-4">
            <x-ui.input
                wire:model="code"
                label="{{ __('Authentication code') }}"
                type="text"
                name="code"
                inputmode="numeric"
                autocomplete="one-time-code"
                required
                autofocus
                placeholder="123456"
                error="code" />

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold
                    primary-button
                    shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400
                    disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="verifyCode">{{ __('Verify & continue') }}</span>
                <span wire:loading wire:target="verifyCode">{{ __('Verifying...') }}</span>
            </button>
        </form>
    @else
        <form wire:submit="verifyRecoveryCode" class="mt-6 flex flex-col gap-4">
            <x-ui.input
                wire:model="recoveryCode"
                label="{{ __('Recovery code') }}"
                type="text"
                name="recoveryCode"
                autocomplete="off"
                required
                autofocus
                placeholder="xxxxx-xxxxx"
                error="recoveryCode" />

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold
                    primary-button
                    shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400
                    disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="verifyRecoveryCode">{{ __('Verify & continue') }}</span>
                <span wire:loading wire:target="verifyRecoveryCode">{{ __('Verifying...') }}</span>
            </button>
        </form>
    @endif

    <div class="my-6 border-t border-slate-200 dark:border-white/[.08]"></div>

    <p class="text-center text-sm text-slate-500 dark:text-slate-400">
        <button wire:click="toggleRecoveryMode" class="font-semibold text-emerald-600 dark:text-emerald-500">
            {{ $useRecoveryCode ? 'Use an authentication code instead' : 'Use a recovery code instead' }}
        </button>
    </p>

    <p class="mt-4 text-center text-sm text-slate-500 dark:text-slate-400">
        <a class="font-semibold text-emerald-600 dark:text-emerald-500" href="{{ route('login') }}" wire:navigate>
            ← Back to login
        </a>
    </p>
</section>