<section class="rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
    <div class="flex items-center gap-3 border-b p-5 border-slate-200 dark:border-white/[.08]">
        <span class="grid h-10 w-10 place-items-center rounded-xl bg-sky-500/12 text-sky-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path></svg>
        </span>
        <div>
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Two-factor authentication</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Add an extra layer of security to your account</p>
        </div>
    </div>

    <div class="p-5">
        @if(! auth()->user()->two_factor_confirmed_at && ! $enabling)
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-600 dark:text-slate-300">Two-factor authentication is currently disabled.</p>
                <button wire:click="enable" wire:loading.attr="disabled" wire:target="enable" class="primary-button">Enable 2FA</button>
            </div>

        @elseif($enabling)
            <div class="flex flex-col items-center text-center">
                <p class="text-sm text-slate-600 dark:text-slate-300">Scan this QR code with your authenticator app, then enter the 6-digit code below.</p>

                <div class="mt-4 rounded-xl bg-white p-4">
                    {!! $qrCodeSvg !!}
                </div>

                <div class="mt-4 w-full max-w-xs">
                    <x-ui.input label="Verification code" wire:model="confirmationCode" name="confirmationCode" placeholder="123456" />
                    @if($error)
                        <p class="mt-1.5 text-xs text-rose-500">{{ $error }}</p>
                    @endif
                </div>

                <button wire:click="confirm" wire:loading.attr="disabled" wire:target="confirm" class="primary-button mt-4">
                    <span wire:loading.remove wire:target="confirm">Confirm & activate</span>
                    <span wire:loading wire:target="confirm">Verifying...</span>
                </button>
            </div>

        @elseif($recoveryCodes)
            <div>
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-500">Two-factor authentication enabled</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Save these recovery codes somewhere safe. Each can be used once if you lose access to your authenticator app.</p>
                <div class="mt-3 grid grid-cols-2 gap-2 rounded-xl border p-4 font-mono text-xs border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03] dark:text-slate-300">
                    @foreach($recoveryCodes as $code)
                        <span>{{ $code }}</span>
                    @endforeach
                </div>
                <button wire:click="$set('recoveryCodes', null)" class="mt-4 text-xs font-semibold text-emerald-600 dark:text-emerald-500">I've saved these codes</button>
            </div>

        @else
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-emerald-600 dark:text-emerald-500">Two-factor authentication is enabled.</p>
                <button wire:click="disable" wire:loading.attr="disabled" wire:target="disable" class="rounded-xl border px-4 py-2 text-xs font-semibold text-rose-500 border-rose-200 hover:bg-rose-50 dark:border-rose-500/20 dark:hover:bg-rose-500/10">
                    Disable
                </button>
            </div>
        @endif
    </div>
</section>