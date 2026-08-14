<?php

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralCommission;
use App\Services\MailService;
use App\Services\TransactionService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $country = '';
    public string $phone = '';
    public string $referral_code = '';

    private ?MailService $mailService = null;
    private ?TransactionService $transactionService = null;

    public function mount(?MailService $mailService = null, ?TransactionService $transactionService = null): void
    {
        $this->mailService = $mailService ?? app(MailService::class);
        $this->transactionService = $transactionService ?? app(TransactionService::class);
        $this->referral_code = request()->query('referral') ?? '';
    }

    private function ensureServices(): void
    {
        $this->mailService ??= app(MailService::class);
        $this->transactionService ??= app(TransactionService::class);
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $this->ensureServices();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'country' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $baseUsername = Str::slug($validated['name']);
        $username = $baseUsername . rand(1000, 9999);
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . rand(1000, 9999);
        }
        $validated['username'] = $username;

        $referringUser = null;
        if (! empty($validated['referral_code'])) {
            $referringUser = User::where('referral_code', $validated['referral_code'])->first();
        }
        unset($validated['referral_code']);

        $signupBonus = (float) setting('referral_bonus_signup', 0);

        $validated['referral_code'] = Str::upper(Str::random(10));
        $validated['balance'] = $signupBonus;
        $validated['referred_by'] = $referringUser?->id;

        DB::beginTransaction();

        try {
            event(new Registered(($user = User::create($validated))));

            if (setting('require_email_verification', 0)) {
                $this->mailService->sendTemplate($user, 'email_verification', [
                    'name' => $user->name ?: $user->email,
                    'email' => $user->email,
                    'username' => $user->username,
                    'verification_url' => URL::temporarySignedRoute(
                        'verification.verify',
                        now()->addMinutes(config('auth.verification.expire', 60)),
                        [
                            'id' => $user->id,
                            'hash' => sha1($user->email),
                        ]
                    ),
                ]);
            } else {
                $this->mailService->sendTemplate($user, 'account_created', [
                    'name' => $user->name ?: $user->email,
                    'email' => $user->email,
                    'username' => $user->username,
                    'login_url' => route('login'),
                ]);
            }

            // Referral bonus — now properly logged in referrals + referral_commissions
            if ($referringUser && setting('referral_enabled', 0)) {
                $referral = Referral::create([
                    'referrer_id' => $referringUser->id,
                    'referred_id' => $user->id,
                    'level' => 1,
                ]);

                $referralAmount = (float) setting('referral_level_1_percentage', 0);

                if ($referralAmount > 0) {
                    $referringUser->increment('balance', $referralAmount);
                    $referringUser->refresh();

                    $transaction = $this->transactionService->create([
                        'reference' => 'referral-' . Str::random(12),
                        'user_id' => $referringUser->id,
                        'amount' => $referralAmount,
                        'type' => \App\Enums\TransactionType::ReferralCredit,
                        'direction' => \App\Enums\TransactionDirection::Credit,
                        'description' => 'Referral bonus from ' . $user->email,
                        'status' => \App\Enums\TransactionStatus::Completed,
                        'metadata' => [
                            'referred_user_id' => $user->id,
                            'referred_user_email' => $user->email,
                        ],
                    ]);

                    ReferralCommission::create([
                        'referral_id' => $referral->id,
                        'source_transaction_id' => $transaction->id,
                        'amount' => $referralAmount,
                        'status' => 'paid',
                    ]);

                    $this->mailService->sendTemplate($referringUser, 'referral_bonus', [
                        'name' => $referringUser->name ?: $referringUser->email,
                        'amount' => $referralAmount,
                        'new_balance' => $referringUser->balance,
                        'referred_user_email' => $user->email,
                        'dashboard_url' => route('home'),
                    ]);
                }
            }

            if ($signupBonus > 0) {
                $this->transactionService->create([
                    'reference' => 'bonus-' . Str::random(12),
                    'user_id' => $user->id,
                    'amount' => $signupBonus,
                    'type' => \App\Enums\TransactionType::Bonus,
                    'direction' => \App\Enums\TransactionDirection::Credit,
                    'description' => 'New user signup bonus',
                    'status' => \App\Enums\TransactionStatus::Completed,
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            $this->addError('register', 'Something went wrong while creating your account. Please try again.');
            return;
        }

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<section class="rounded-[28px] border p-6 shadow-2xl sm:p-8
    border-slate-200 bg-white
    dark:border-white/[.08] dark:bg-[#111a2d]">

    <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">
        {{ setting('site_name', 'Account') }}
    </p>
    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Create an account</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Enter your details below to create your account.</p>

    <x-auth-session-status class="mt-4 text-center" :status="session('status')" />

    <form wire:submit="register" class="mt-6 flex flex-col">
        <x-ui.input
            wire:model="name"
            id="name"
            label="{{ __('Full name') }}"
            type="text"
            name="name"
            required
            autofocus
            autocomplete="name"
            placeholder="Full name"
            error="name" />

        <x-ui.input
            wire:model="email"
            id="email"
            label="{{ __('Email address') }}"
            type="email"
            name="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
            error="email" />

        <div class="grid grid-cols-2 gap-4">
            <x-ui.input
                wire:model="phone"
                id="phone"
                label="{{ __('Phone number') }}"
                type="tel"
                name="phone"
                required
                autocomplete="tel"
                placeholder="+1 234 567 8900"
                error="phone" />

            <x-ui.select
                wire:model="country"
                id="country"
                label="{{ __('Country') }}"
                name="country"
                placeholder="Select country"
                error="country"
                :autodetect="true">
                @foreach(getCountries() as $country)
                <option value="{{ $country['name'] }}" {{ old('country') === $country['name'] ? 'selected' : '' }}>
                    {{ $country['name'] }}
                </option>
                @endforeach
            </x-ui.select>
        </div> 
        <x-ui.input
            wire:model="referral_code"
            id="referral_code"
            label="{{ __('Referral Code (Optional)') }}"
            type="text"
            name="referral_code"
            autocomplete="off"
            error="referral_code" />

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
            wire:target="register"
            class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold
                primary-button
                shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400
                disabled:cursor-not-allowed disabled:opacity-70">
            <svg wire:loading wire:target="register" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span wire:loading.remove wire:target="register">{{ __('Create account') }}</span>
            <span wire:loading wire:target="register">{{ __('Creating account...') }}</span>
        </button>
    </form>

    <div class="my-6 border-t border-slate-200 dark:border-white/[.08]"></div>

    <p class="text-center text-sm text-slate-500 dark:text-slate-400">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-emerald-600 dark:text-emerald-500">
            Log in
        </a>
    </p>
</section>