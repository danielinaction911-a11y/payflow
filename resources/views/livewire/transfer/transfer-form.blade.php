<div>
    @if ($accessBlocked)
    <div class="max-w-md mx-auto text-center py-16 px-6">
        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
            Transfers Unavailable
        </h2>

        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            {{ $blockedMessage }}
        </p>

        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-medium">
            Back to Dashboard
        </a>
    </div>
    @else
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-500">Internal transfers</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Send money</h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Send securely to any verified member, in seconds.</p>
        </div>
    </section>

    {{-- STEP 1: Search recipient + amount + message --}}
    @if($step === 'search')
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_330px]">
        <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="font-semibold text-slate-900 dark:text-white">Recipient details</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Search by username, email, or name.</p>

            @if(! $this->recipient)
            <label class="mt-6 block text-xs font-medium text-slate-500 dark:text-slate-400">Recipient</label>
            <div class="mt-2 flex items-center gap-2 rounded-xl border px-3 border-slate-200 bg-slate-50 focus-within:border-emerald-500 dark:border-white/[.09] dark:bg-white/[.045] dark:focus-within:border-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 dark:text-slate-500">
                    <path d="m21 21-4.34-4.34"></path>
                    <circle cx="11" cy="11" r="8"></circle>
                </svg>
                <input
                    wire:model.live.debounce.400ms="query"
                    type="text"
                    placeholder="name@example.com or @username"
                    class="w-full bg-transparent py-3 text-sm outline-none text-slate-900 placeholder:text-slate-400 dark:text-white dark:placeholder:text-slate-500" />
            </div>
            @error('recipientId') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror

            @if(mb_strlen(trim($query)) >= 2)
            <div class="mt-2 divide-y divide-slate-100 overflow-hidden rounded-xl border border-slate-200 dark:divide-white/[.06] dark:border-white/[.08]">
                @forelse($this->searchResults as $user)
                <button
                    type="button"
                    wire:click="selectRecipient({{ $user->id }})"
                    wire:key="result-{{ $user->id }}"
                    class="flex w-full items-center gap-3 p-3 text-left transition hover:bg-slate-50 dark:hover:bg-white/[.04]">
                    @if ($user->avatar)
                    <img src="{{ $user->profileImageUrl() }}" alt="{{ $user->name }}"
                        class="w-8 h-8 rounded-full object-cover ring-2 ring-green-400 dark:ring-green-500" />
                    @else
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-violet-400 to-sky-500 text-xs font-bold text-white">
                        {{ strtoupper(substr($user->name ?? $user->username, 0, 2)) }}
                    </span>
                    @endif
                    <span class="min-w-0 flex-1">
                        <b class="block truncate text-sm text-slate-900 dark:text-white">{{ $user->name ?? $user->username }}</b>
                        <small class="block truncate text-slate-500 dark:text-slate-400">{{ $user->username }} &middot; {{ $user->email }}</small>
                    </span>
                    @if($user->email_verified_at)
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-emerald-500">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                    @endif
                </button>
                @empty
                <x-ui.empty-state
                    icon="search"
                    title="No members found"
                    :subtitle="'No results match “' . $query . '”. Try a different username or email.'"
                    compact />
                @endforelse
            </div>
            @endif
            @else
            <div class="recipient-card mt-6">
                <div class="recipient-avatar">
                    @if ($this->recipient->avatar)
                    <img
                        src="{{ $this->recipient->profileImageUrl() }}"
                        alt="{{ $this->recipient->name }}"
                        class="recipient-avatar-img" />
                    @else
                    <span class="recipient-avatar-fallback">
                        {{ strtoupper(substr($this->recipient->name ?? $this->recipient->username, 0, 2)) }}
                    </span>
                    @endif
                </div>

                <div class="recipient-info">
                    <b class="recipient-name">{{ $this->recipient->name ?? $this->recipient->username }}</b>
                    <div class="recipient-meta">
                        <span>{{ $this->recipient->username }}</span>
                        <span class="recipient-dot hidden sm:inline">&middot;</span>
                        <span class="recipient-status hidden sm:inline {{ $this->recipient->email_verified_at ? 'is-verified' : '' }}">
                            {{ $this->recipient->email_verified_at ? 'Verified member' : 'Member' }}
                        </span>
                    </div>
                </div>

                <button type="button" wire:click="changeRecipient" class="recipient-change">Change</button>
            </div>
            @endif

            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mt-3">Amount</label>
            <div class="fund-amount mt-2 flex items-center gap-2 rounded-xl border px-4 border-slate-200 bg-slate-50 focus-within:border-orange-500 dark:border-white/10 dark:bg-white/5 dark:focus-within:border-orange-400">
                <span class="text-lg font-semibold text-slate-400 dark:text-slate-500">{{ setting('default_currency_symbol', '$') }}</span>
                <input
                    wire:model.live="amount"
                    type="text"
                    inputmode="decimal"
                    placeholder="0.00"
                    class="w-full bg-transparent py-3.5 text-xl font-bold outline-none text-slate-900 placeholder:text-slate-300 dark:text-white dark:placeholder:text-slate-600" />
                <small class="shrink-0 text-xs font-semibold text-slate-400 dark:text-slate-500">{{ setting('default_currency', 'USD') }}</small>
            </div>
            @error('amount') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror

            <label class="mt-5 block text-xs font-medium text-slate-500 dark:text-slate-400">
                Message <span class="font-normal">(optional)</span>
            </label>
            <x-ui.textarea name="description" wire:model="description" placeholder="What's this for?" :rows="3" />
            @error('description') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </section>

        <aside class="transfer-protect rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="secure-callout">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-500/12 text-emerald-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="16" r="1"></circle>
                        <rect x="3" y="10" width="18" height="12" rx="2"></rect>
                        <path d="M7 10V7a5 5 0 0 1 10 0v3"></path>
                    </svg>
                </span>
                <span>
                    <b class="block text-sm text-slate-900 dark:text-white">Protected transfer</b>
                    <small class="text-slate-500 dark:text-slate-400">Member-to-member only</small>
                </span>
            </div>
            <p>Transfers are instant, free, and protected with multi-factor confirmation. Money can only be sent to registered users.</p>

            <button wire:click="proceedToPin" wire:loading.attr="disabled" wire:target="proceedToPin" class="primary-button">
                <span wire:loading.remove wire:target="proceedToPin">Continue</span>
                <span wire:loading wire:target="proceedToPin">Please wait...</span>
            </button>
            <button href="{{ route('transfer.history') }}" wire:navigate class="link-button text-center" style="margin-top:13px; width:100%">View transfer history</button>
        </aside>
    </div>
    @endif

    {{-- STEP 2: PIN --}}
    @if($step === 'pin')
    <div class="mx-auto max-w-md">
        <button wire:click="backToSearch" class="mb-4 flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
            Back
        </button>

        <section class="rounded-2xl border p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">

            @if($this->userHasPin)
            {{-- User has a PIN — just confirm it --}}
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Confirm your PIN</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Enter your 4-digit transaction PIN to authorize this transfer.</p>

            <div class="mt-5">
                <x-ui.pin-input
                    label="Transaction PIN"
                    wireModel="pin"
                    error="pin" />
            </div>

            @error('submit') <p class="mt-3 text-sm text-rose-500">{{ $message }}</p> @enderror

            <button wire:click="submit" wire:loading.attr="disabled" wire:target="submit" class="mt-5 flex primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="submit">Confirm transfer</span>
                <span wire:loading wire:target="submit">Processing...</span>
            </button>

            @elseif($this->canCreatePin)
            {{-- No PIN yet, but self-service creation is allowed --}}
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Create a transaction PIN</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">You don't have a PIN yet. Set one now to continue with your transfer.</p>

            <div class="mt-5 space-y-5">
                <x-ui.pin-input
                    label="New PIN"
                    wireModel="newPin"
                    error="newPin" />
                <x-ui.pin-input
                    label="Confirm PIN"
                    wireModel="newPinConfirmation"
                    error="newPinConfirmation" />
            </div>

            <button wire:click="createPin" wire:loading.attr="disabled" wire:target="createPin" class="mt-5 flex primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-70">
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
                    You don't have a transaction PIN set up, and self-service PIN creation is currently disabled.
                    Please contact support to have a PIN assigned to your account before you can send money.
                </p>

                <a href="{{ Route::has('support.index') ? route('support.index') : '#' }}" class="primary-button mt-5 w-full justify-center">
                    Contact support
                </a>
            </div>
            @endif
        </section>
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

        <h2 class="mt-5 text-xl font-semibold text-slate-900 dark:text-white">Money sent</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Your transfer to {{ $successData['recipient_name'] }} was completed instantly.
        </p>

        <div class="mt-6 space-y-2 rounded-xl border p-4 text-left text-sm border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
            <x-ui.copy-value label="Reference" :value="$successData['reference']" />
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Recipient</span>
                <b class="text-slate-900 dark:text-white">{{ $successData['recipient_name'] }} ({{ $successData['recipient_username'] }})</b>
            </div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Amount</span><b class="text-slate-900 dark:text-white">{{ money_format($successData['amount']) }}</b></div>
            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Status</span><b class="text-emerald-500">Completed</b></div>
        </div>

        <div class="mt-6 flex flex-col gap-2">
            <a href="{{ route('home') }}" wire:navigate class="primary-button w-full justify-center">Return to dashboard</a>
            <button wire:click="startOver" class="link-button">Send another transfer</button>
        </div>
    </div>
    @endif
    @endif
</div>