<div>
    @if ($accessBlocked)
    <div class="max-w-md mx-auto text-center py-16 px-6">
        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
            Withdrawal Unavailable
        </h2>

        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            {{ $blockedMessage }}
        </p>

        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-medium">
            Back to Dashboard
        </a>
    </div>
    @else
    <section class="mb-7">
        <p class="text-xs font-semibold uppercase tracking-[.16em] text-orange-600 dark:text-orange-400">Cash out</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Withdraw funds</h1>
    </section>

    {{-- STEP 1: Select method + amount --}}
    @if($step === 'select')
    <div class="balance-source-grid">
        <button
            type="button"
            wire:click="selectBalanceSource('balance')"
            class="balance-source {{ $balanceSource === 'balance' ? 'selected' : '' }}">
            <span class="balance-source-top">
                <small>Main balance</small>
                <em></em>
            </span>
            <b>{{ money_format(auth()->user()->balance) }}</b>
        </button>

        <button
            type="button"
            wire:click="selectBalanceSource('profit_balance')"
            class="balance-source {{ $balanceSource === 'profit_balance' ? 'selected' : '' }}">
            <span class="balance-source-top">
                <small>Profit balance</small>
                <em></em>
            </span>
            <b>{{ money_format(auth()->user()->profit_balance) }}</b>
        </button>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_330px]">
        <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Choose a withdrawal method</h2>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 method-grid">
                @foreach($this->gateways as $gateway)
                <button
                    type="button"
                    wire:click="selectGateway({{ $gateway->id }})"
                    class="flex items-center gap-3 rounded-xl border p-3.5 text-left transition method
                                {{ $gatewayId === $gateway->id
                                    ? 'selected'
                                    : '' }}">
                    @if($gateway->logo && file_exists(public_path($gateway->logo)))
                    <span class="grid h-10 w-10 shrink-0 place-items-center overflow-hidden rounded-xl bg-white dark:bg-white/10">
                        <img src="{{ asset($gateway->logo) }}" alt="{{ $gateway->name }}" class="h-full w-full object-contain p-1.5">
                    </span>
                    @else
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-sm font-bold
                                    {{ $gatewayId === $gateway->id ? 'bg-orange-500 text-white' : 'bg-slate-200 text-slate-600 dark:bg-white/10 dark:text-slate-300' }}">
                        {{ strtoupper(substr($gateway->name, 0, 1)) }}
                    </span>
                    @endif

                    <span class="min-w-0 flex-1">
                        <b class="block text-sm text-slate-900 dark:text-white">{{ $gateway->name }}</b>
                        <small class="text-xs text-slate-500 dark:text-slate-400">
                            Fee: {{ $gateway->fixed_fee > 0 ? money_format($gateway->fixed_fee, $gateway->currency) . ' + ' : '' }}{{ $gateway->percent_fee }}%
                        </small>
                    </span>
                    <em class="h-4 w-4 shrink-0 rounded-full border {{ $gatewayId === $gateway->id ? 'border-orange-500 bg-orange-500' : 'border-slate-300 dark:border-white/20' }}"></em>
                </button>
                @endforeach
            </div>

            @error('gatewayId') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror

            <div class="my-6 border-t border-slate-200 dark:border-white/[.08]"></div>

            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400">Withdrawal amount</label>
            <div class="fund-amount mt-2 flex items-center gap-2 rounded-xl border px-4 border-slate-200 bg-slate-50 focus-within:border-orange-500 dark:border-white/10 dark:bg-white/5 dark:focus-within:border-orange-400">
                <span class="text-lg font-semibold text-slate-400 dark:text-slate-500">$</span>
                <input
                    wire:model.live="amount"
                    type="text"
                    inputmode="decimal"
                    placeholder="0.00"
                    class="w-full bg-transparent py-3.5 text-xl font-bold outline-none text-slate-900 placeholder:text-slate-300 dark:text-white dark:placeholder:text-slate-600" />
                <small class="shrink-0 text-xs font-semibold text-slate-400 dark:text-slate-500">{{ $this->selectedGateway->currency ?? 'USD' }}</small>
            </div>
            @error('amount') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror

            <div class="mt-3 flex flex-wrap gap-2 amount-shortcuts">
                @foreach([50, 100, 500, 1000] as $shortcut)
                <button type="button" wire:click="setAmount('{{ $shortcut }}')"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 dark:text-slate-300 dark:bg-white/[.06] dark:hover:bg-white/10">
                    ${{ number_format($shortcut) }}
                </button>
                @endforeach
            </div>
        </section>

        <aside class="h-max rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Review</h2>

            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Method</span><b class="text-slate-900 dark:text-white">{{ $this->selectedGateway->name ?? '—' }}</b></div>
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Processing fee</span><b class="text-slate-900 dark:text-white">{{ $this->fee > 0 ? money_format($this->fee) : 'Free' }}</b></div>
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Available balance</span><b class="text-slate-900 dark:text-white">{{ money_format($this->availableBalance) }}</b></div>
            </div>

            <div class="mt-4 flex justify-between border-t pt-4 text-base font-semibold border-slate-200 dark:border-white/[.08]">
                <span class="text-slate-900 dark:text-white">You'll be debited</span>
                <b class="text-slate-900 dark:text-white">{{ money_format($this->total) }}</b>
            </div>

            {{-- FIX: was calling proceedToPin (validates fields that don't exist yet on this step) --}}
            <button wire:click="proceedToDetails" wire:loading.attr="disabled" wire:target="proceedToDetails" class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold !bg-orange-500 !text-white shadow-lg shadow-orange-500/20 transition hover:!bg-orange-400 disabled:cursor-not-allowed disabled:opacity-70">
                Continue
            </button>
            <button href="{{ route('withdraw.history') }}" wire:navigate class="link-button text-center" style="margin-top:13px; width:100%">View withdrawal history</button>
        </aside>
    </div>
    @endif


    @if($step === 'pin')
    <div class="mx-auto max-w-md">
        <button wire:click="backToDetails" class="mb-4 flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
            Back
        </button>

        <section class="rounded-2xl border p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">

            @if($this->userHasPin)
            {{-- User has a PIN — just confirm it --}}
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Confirm your PIN</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Enter your 4-digit transaction PIN to authorize this withdrawal.</p>

            <div class="mt-5">
                <x-ui.pin-input
                    label="Transaction PIN"
                    wireModel="pin"
                    error="pin" />
            </div>

            @error('submit') <p class="mt-3 text-sm text-rose-500">{{ $message }}</p> @enderror

            <button wire:click="submit" wire:loading.attr="disabled" wire:target="submit" class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold !bg-orange-500 !text-white shadow-lg shadow-orange-500/20 transition hover:!bg-orange-400 disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="submit">Confirm withdrawal</span>
                <span wire:loading wire:target="submit">Processing...</span>
            </button>

            @elseif($this->canCreatePin)
            {{-- No PIN yet, but self-service creation is allowed --}}
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Create a transaction PIN</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">You don't have a PIN yet. Set one now to continue with your withdrawal.</p>

            <div class="mt-5 space-y-5 ">
                <x-ui.pin-input
                    label="New PIN"
                    wireModel="newPin"
                    error="newPin" />
                <x-ui.pin-input
                    label="Confirm PIN"
                    wireModel="newPinConfirmation"
                    error="newPinConfirmation" />
            </div>

            <button wire:click="createPin" wire:loading.attr="disabled" wire:target="createPin" class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold !bg-emerald-500 !text-white shadow-lg shadow-emerald-500/20 transition hover:!bg-emerald-400 disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="createPin">Set PIN & continue</span>
                <span wire:loading wire:target="createPin">Saving...</span>
            </button>

            @else
            {{-- No PIN, and self-service creation is disabled --}}
            <div class="text-center">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-rose-500/12 text-rose-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" x2="12" y1="8" y2="12"></line>
                        <line x1="12" x2="12.01" y1="16" y2="16"></line>
                    </svg>
                </span>
                <h2 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">Transaction PIN required</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    You don't have a withdrawal PIN set up, and self-service PIN creation is currently disabled.
                    Please contact support to have a PIN assigned to your account before you can withdraw funds.
                </p>

                <a href="{{ Route::has('support.index') ? route('support.index') : '#' }}" class="primary-button mt-5 w-full justify-center">
                    Contact support
                </a>
            </div>
            @endif
        </section>
    </div>
    @endif

    {{-- STEP 2: Destination details --}}
    @if($step === 'details' && $this->selectedGateway)
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_330px]">
        <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <button wire:click="backToSelect" class="mb-4 flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
                Back
            </button>

            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Where should we send it?</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Enter your {{ $this->selectedGateway->name }} details below.</p>

            @if($this->selectedGateway->details)
            <div class="mt-5 space-y-5">
                @foreach($this->selectedGateway->details as $field)
                <x-ui.input
                    :label="$field['label']"
                    type="text"
                    wire:model="fields.{{ $field['name'] }}"
                    :error="'fields.' . $field['name']" />
                @endforeach
            </div>
            @endif

            {{-- REMOVED: duplicate PIN field. PIN is collected exclusively on the dedicated 'pin' step,
                 which also handles the "no PIN yet" / "create PIN" / "PIN disabled" cases. Asking for it
                 here too meant a second, redundant PIN prompt in the flow. --}}

            @error('submit') <p class="mt-4 text-sm text-rose-500">{{ $message }}</p> @enderror
        </section>

        <aside class="h-max rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Review</h2>

            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Method</span><b class="text-slate-900 dark:text-white">{{ $this->selectedGateway->name }}</b></div>
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Amount</span><b class="text-slate-900 dark:text-white">{{ money_format($amount) }}</b></div>
                <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Fee</span><b class="text-slate-900 dark:text-white">{{ $this->fee > 0 ? money_format($this->fee) : 'Free' }}</b></div>
            </div>

            <div class="mt-4 flex justify-between border-t pt-4 text-base font-semibold border-slate-200 dark:border-white/[.08]">
                <span class="text-slate-900 dark:text-white">Total debit</span>
                <b class="text-slate-900 dark:text-white">{{ money_format($this->total) }}</b>
            </div>

            {{-- FIX: was calling submit() directly, which requires a PIN to already exist and skips
                 the create-PIN / PIN-required-disabled flows entirely. proceedToPin() validates the
                 destination fields, then routes to the 'pin' step (or straight to processing if PIN
                 isn't required by settings). --}}
            <button wire:click="proceedToPin" wire:loading.attr="disabled" wire:target="proceedToPin" class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold !bg-orange-500 !text-white shadow-lg shadow-orange-500/20 transition hover:!bg-orange-400 disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="proceedToPin">Continue</span>
                <span wire:loading wire:target="proceedToPin">Please wait...</span>
            </button>
        </aside>
    </div>
    @endif

    {{-- STEP 3: Success --}}
    @if($step === 'success' && $successData)
    <div class="mx-auto max-w-lg rounded-2xl border p-8 text-center border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
        <span
            x-data="{ show: true }"
            x-init="$nextTick(() => show = true)"
            class="success-check"
            :class="{ 'success-check-animate': show }">
            <svg class="success-check-circle" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="29" stroke="currentColor" stroke-width="2.5" />
            </svg>
            <svg class="success-check-mark" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6 9 17l-5-5"></path>
            </svg>
        </span>

        <h2 class="mt-5 text-xl font-semibold text-slate-900 dark:text-white">Withdrawal submitted</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Your withdrawal is pending review. Funds have been reserved from your balance.
        </p>

        <div class="mt-6 space-y-2 rounded-xl border p-4 text-left text-sm border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <x-ui.copy-value label="Reference" :value="$successData['reference']" />
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Method</span><b class="text-slate-900 dark:text-white">{{ $successData['gateway'] }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Amount</span><b class="text-slate-900 dark:text-white">{{ money_format($successData['amount']) }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Total debited</span><b class="text-slate-900 dark:text-white">{{ money_format($successData['total']) }}</b></div>
        </div>

        <div class="mt-6 flex flex-col gap-2">
            <a href="{{ route('home') }}" wire:navigate class="primary-button w-full justify-center">Return to dashboard</a>
            <button wire:click="startOver" class="link-button">Make another withdrawal</button>
        </div>
    </div>
    @endif

    @endif
</div>