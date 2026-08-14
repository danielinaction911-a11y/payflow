<div>
    @if ($accessBlocked)
    <div class="max-w-md mx-auto text-center py-16 px-6">
        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
            Deposit Unavailable
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
        <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Fund your account</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Deposit funds</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Choose a secure funding method for your {{ setting('site_name', 'account') }} balance.</p>
    </section>

    {{-- STEP 1: Select method + amount --}}
    @if($step === 'select')
    <div class="grid gap-6 grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,330px)]">
        <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Choose a deposit method</h2>

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
                    {{ $gatewayId === $gateway->id ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600 dark:bg-white/10 dark:text-slate-300' }}">
                        {{ strtoupper(substr($gateway->name, 0, 1)) }}
                    </span>
                    @endif

                    <span class="min-w-0 flex-1">
                        <b class="block text-sm text-slate-900 dark:text-white">{{ $gateway->name }}</b>
                        <small class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $gateway->type === 'auto' ? 'Usually instant' : ($gateway->instructions['details']['processing_time'] ?? 'Manual review') }}
                        </small>
                    </span>
                    <em class="h-4 w-4 shrink-0 rounded-full border {{ $gatewayId === $gateway->id ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300 dark:border-white/20' }}"></em>
                </button>
                @endforeach
            </div>

            @error('gatewayId') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror

            <div class="my-6 border-t border-slate-200 dark:border-white/[.08]"></div>

            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400">Deposit amount</label>
            <div class="fund-amount mt-2 flex items-center gap-2 rounded-xl border px-4 border-slate-200 bg-slate-50 focus-within:border-emerald-500 dark:border-white/10 dark:bg-white/5 dark:focus-within:border-emerald-400">
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
                @foreach([100, 500, 1000, 5000] as $shortcut)
                <button type="button" wire:click="setAmount('{{ $shortcut }}')"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 dark:text-slate-300 dark:bg-white/[.06] dark:hover:bg-white/10">
                    ${{ number_format($shortcut) }}
                </button>
                @endforeach
            </div>

            <div class="mt-6 flex gap-3 rounded-xl border p-3.5 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-500/12 text-emerald-500">
                    <x-app-icon name="shield-check" class="h-4 w-4" />
                </span>
                <span>
                    <b class="block text-sm text-slate-900 dark:text-white">Protected transfer</b>
                    <small class="block text-xs text-slate-500 dark:text-slate-400">Your transfer details are encrypted and monitored.</small>
                </span>
            </div>
        </section>

        <aside class="panel review-panel h-max rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90 w-full lg:w-auto min-w-0">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Review</h2>

            <div class="mt-4 space-y-3 review-list">
                <div class="review-row">
                    <span class="text-slate-500 dark:text-slate-400">Method</span>
                    <b class="text-slate-900 dark:text-white">{{ $this->selectedGateway->name ?? '—' }}</b>
                </div>
                <div class="review-row">
                    <span class="text-slate-500 dark:text-slate-400">Processing fee</span>
                    <b class="text-slate-900 dark:text-white">{{ $this->fee > 0 ? money_format($this->fee) : 'Free' }}</b>
                </div>
                <div class="review-row">
                    <span class="text-slate-500 dark:text-slate-400">Estimated arrival</span>
                    <b class="text-slate-900 dark:text-white">{{ $this->selectedGateway->instructions['details']['processing_time'] ?? ($this->selectedGateway->type === 'auto' ? 'Instant' : '1-24 hours') }}</b>
                </div>
            </div>

            <div class="mt-4 flex justify-between border-t pt-4 text-base font-semibold border-slate-200 dark:border-white/[.08]">
                <span class="text-slate-900 dark:text-white">Total</span>
                <b class="text-slate-900 dark:text-white">{{ money_format($this->total) }}</b>
            </div>

            <button wire:click="proceedToFields" wire:loading.attr="disabled" class="primary-button mt-5 w-full justify-center">
                Continue to deposit
            </button>
            <button href="{{ route('deposit.history') }}" wire:navigate class="link-button text-center" style="margin-top:13px; width:100%">View deposit history</button>
        </aside>
    </div>
    @endif

    {{-- STEP 2: Dynamic gateway-specific fields --}}
    @if($step === 'fields' && $this->selectedGateway)
    <div class="grid gap-6 grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,330px)]">
        <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <button wire:click="backToSelect" class="mb-4 flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
                Back
            </button>

            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                {{ $this->selectedGateway->instructions['title'] ?? $this->selectedGateway->name . ' Payment' }}
            </h2>

            @if(!empty($this->selectedGateway->instructions['steps']))
            <ol class="mt-3 space-y-1.5 text-sm text-slate-500 dark:text-slate-400 list-decimal list-inside">
                @foreach($this->selectedGateway->instructions['steps'] as $step)
                <li>{{ $step }}</li>
                @endforeach
            </ol>
            @endif

            @if(!empty($this->selectedGateway->instructions['details']))
            <div class="mt-4 space-y-3 rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                @foreach($this->selectedGateway->instructions['details'] as $label => $value)
                <x-ui.copy-value :label="ucwords(str_replace('_', ' ', $label))" :value="$value" />
                @endforeach
            </div>

            @if($this->selectedGateway->code === 'crypto' && !empty($this->selectedGateway->instructions['details']['wallet_address']))
            @php $walletAddress = $this->selectedGateway->instructions['details']['wallet_address']; @endphp
            <div class="mt-4 flex flex-col items-center gap-3 rounded-xl border p-5 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($walletAddress) }}"
                    alt="{{ __('Wallet QR code') }}"
                    class="h-[180px] w-[180px] max-w-full rounded-xl bg-white p-2 shadow-sm">
                <p class="text-center text-xs text-slate-500 dark:text-slate-400">Scan to copy the wallet address, or use the button above.</p>
            </div>
            @endif
            @endif

            @if($this->selectedGateway->payment_fields)
            <div class="mt-6 space-y-5">
                @foreach($this->selectedGateway->payment_fields as $field)
                @if($field['type'] === 'file')
                <div>
                    <x-ui.label>{{ $field['label'] }}</x-ui.label>
                    <input type="file" wire:model="uploads.{{ $field['name'] }}"
                        class="mt-2 w-full rounded-xl border px-3 py-2.5 text-sm border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white" />
                    <div wire:loading wire:target="uploads.{{ $field['name'] }}" class="mt-1 text-xs text-slate-400">Uploading...</div>
                    @error('uploads.' . $field['name']) <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                @elseif($field['type'] === 'textarea')
                <x-ui.textarea
                    :label="$field['label']"
                    wire:model="fields.{{ $field['name'] }}"
                    :error="'fields.' . $field['name']" />
                @else
                <x-ui.input
                    :label="$field['label']"
                    type="text"
                    wire:model="fields.{{ $field['name'] }}"
                    :error="'fields.' . $field['name']" />
                @endif
                @endforeach
            </div>
            @endif

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
                <span class="text-slate-900 dark:text-white">Total</span>
                <b class="text-slate-900 dark:text-white">{{ money_format($this->total) }}</b>
            </div>

            <button wire:click="submit" wire:loading.attr="disabled" wire:target="submit" class="primary-button mt-5 w-full justify-center">
                <span wire:loading.remove wire:target="submit">Submit deposit</span>
                <span wire:loading wire:target="submit">Processing...</span>
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

        <h2 class="mt-5 text-xl font-semibold text-slate-900 dark:text-white">Deposit submitted</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Your deposit is pending confirmation. We'll notify you once it's approved.
        </p>

        <div class="mt-6 space-y-2 rounded-xl border p-4 text-left text-sm border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Reference</span><b class="text-slate-900 dark:text-white">{{ $successData['reference'] }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Method</span><b class="text-slate-900 dark:text-white">{{ $successData['gateway'] }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Amount</span><b class="text-slate-900 dark:text-white">{{ money_format($successData['amount']) }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Total</span><b class="text-slate-900 dark:text-white">{{ money_format($successData['total']) }}</b></div>
        </div>

        <div class="mt-6 flex flex-col gap-2">
            <a href="{{ route('home') }}" wire:navigate class="primary-button w-full justify-center">Return to dashboard</a>
            <button wire:click="startOver" class="link-button">Make another deposit</button>
        </div>
    </div>
    @endif

    @endif
</div>