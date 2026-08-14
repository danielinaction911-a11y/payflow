<div>
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Internal transfers</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Request money</h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Ask any verified member to send you funds.</p>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_330px]">
        <div>
            {{-- STEP 1: search + amount --}}
            @if($step === 'search')
            <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="font-semibold text-slate-900 dark:text-white">Who are you requesting from?</h2>
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
                    <button type="button" wire:click="selectRecipient({{ $user->id }})" wire:key="result-{{ $user->id }}" class="flex w-full items-center gap-3 p-3 text-left transition hover:bg-slate-50 dark:hover:bg-white/[.04]">
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
                <div class="mt-6 flex items-center gap-3 rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                    @if ($this->recipient->avatar)
                    <img src="{{ $this->recipient->profileImageUrl() }}" alt="{{ $this->recipient->name }}"
                        class="w-8 h-8 rounded-full object-cover ring-2 ring-green-400 dark:ring-green-500" />
                    @else
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-violet-400 to-sky-500 text-sm font-bold text-white">
                        {{ strtoupper(substr($this->recipient->name ?? $this->recipient->username, 0, 2)) }}
                    </span>
                    @endif
                    <div class="min-w-0 flex-1">
                        <b class="block text-sm text-slate-900 dark:text-white">{{ $this->recipient->name ?? $this->recipient->username }}</b>
                        <small class="text-xs text-slate-500 dark:text-slate-400">{{ $this->recipient->username }}</small>
                    </div>
                    <button type="button" wire:click="changeRecipient" class="shrink-0 text-xs font-semibold text-emerald-600 dark:text-emerald-500">Change</button>
                </div>
                @endif

                <label class="mt-5 block text-xs font-medium text-slate-500 dark:text-slate-400">Amount</label>
                <div class="fund-amount mt-2 flex items-center gap-2 rounded-xl border px-4 border-slate-200 bg-slate-50 focus-within:border-emerald-500 dark:border-white/10 dark:bg-white/5 dark:focus-within:border-emerald-400">
                    <span class="text-lg font-semibold text-slate-400 dark:text-slate-500">{{ setting('default_currency_symbol', '$') }}</span>
                    <input wire:model.live="amount" type="text" inputmode="decimal" placeholder="0.00" class="w-full bg-transparent py-3.5 text-xl font-bold outline-none text-slate-900 placeholder:text-slate-300 dark:text-white dark:placeholder:text-slate-600" />
                    <small class="shrink-0 text-xs font-semibold text-slate-400 dark:text-slate-500">{{ setting('default_currency', 'USD') }}</small>
                </div>
                @error('amount') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror

                <label class="mt-5 block text-xs font-medium text-slate-500 dark:text-slate-400">Message <span class="font-normal">(optional)</span></label>
                <x-ui.textarea name="message" wire:model="message" placeholder="What's this for?" :rows="3" />
                @error('message') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror

                <button wire:click="sendRequest" wire:loading.attr="disabled" wire:target="sendRequest" class="primary-button mt-6 w-full justify-center disabled:cursor-not-allowed disabled:opacity-70">
                    <span wire:loading.remove wire:target="sendRequest">Send request</span>
                    <span wire:loading wire:target="sendRequest">Sending...</span>
                </button>
            </section>
            @endif

            {{-- STEP 2: success --}}
            @if($step === 'success' && $successData)
            <div class="rounded-2xl border p-8 text-center border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90"> 
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

                <h2 class="mt-5 text-xl font-semibold text-slate-900 dark:text-white">Request sent</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    We've notified {{ $successData['recipient_name'] }} about your request for {{ money_format($successData['amount']) }}.
                </p>

                <div class="mt-6 flex flex-col gap-2">
                    <button wire:click="startOver" class="primary-button w-full justify-center">Send another request</button>
                    <a href="{{ route('home') }}" wire:navigate class="link-button">Return to dashboard</a>
                </div>
            </div>
            @endif
        </div>

        {{-- Aside: Incoming requests --}}
        <aside class="flex h-[520px] flex-col rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="border-b p-5 border-slate-200 dark:border-white/[.08]">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Incoming requests</h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Requests waiting on your response</p>
            </div>

            @if($this->incomingRequests->isNotEmpty())
            <div class="custom-scroll flex-1 divide-y divide-slate-100 overflow-y-auto dark:divide-white/[.06]">
                @foreach($this->incomingRequests as $request)
                <div wire:key="req-{{ $request->id }}" class="p-4">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-violet-400 to-sky-500 text-xs font-bold text-white">
                            {{ strtoupper(substr($request->requester->name ?? '?', 0, 2)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <b class="block truncate text-sm text-slate-900 dark:text-white">{{ $request->requester->name ?? $request->requester->username }}</b>
                            <small class="text-xs text-slate-500 dark:text-slate-400">{{ time_ago($request->created_at) }}</small>
                        </div>
                        <b class="shrink-0 text-sm text-slate-900 dark:text-white">{{ money_format($request->amount) }}</b>
                    </div>

                    @if($request->message)
                    <p class="mt-2 rounded-lg bg-slate-50 p-2.5 text-xs text-slate-600 dark:bg-white/[.03] dark:text-slate-300">{{ $request->message }}</p>
                    @endif

                    @if($decliningRequestId === $request->id)
                    <div class="mt-3 flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 p-2.5 dark:border-rose-500/20 dark:bg-rose-500/10">
                        <span class="flex-1 text-xs text-rose-600 dark:text-rose-400">Decline this request?</span>
                        <button wire:click="cancelDecline" class="rounded-md px-2 py-1 text-xs font-semibold text-slate-500">No</button>
                        <button wire:click="declineRequest({{ $request->id }})" class="rounded-md bg-rose-500 px-2 py-1 text-xs font-semibold text-white">Yes, decline</button>
                    </div>
                    @else
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <button wire:click="confirmDecline({{ $request->id }})" class="rounded-lg btn btn-danger">
                            Decline
                        </button>
                        <button wire:click="openPayModal({{ $request->id }})" class="rounded-lg !bg-emerald-500 py-2 text-xs font-semibold !text-white hover:!bg-emerald-400">
                            Pay
                        </button>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-1 flex-col items-center justify-center text-center px-6">
                <span class="grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                    <x-app-icon name="hand-coins" class="h-5 w-5 text-slate-400" />
                </span>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">No pending requests right now.</p>
            </div>
            @endif
        </aside>
    </div>

    {{-- Pay modal --}}
    <div x-show="$wire.showPayModal" x-cloak class="fixed inset-0 z-50 grid place-items-center bg-slate-900/60 p-4 backdrop-blur-sm" x-transition.opacity @click.self="$wire.closePayModal()" @keydown.escape.window="$wire.closePayModal()">
        <div x-show="$wire.showPayModal" x-transition class="w-full max-w-sm rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
            @if($this->payingRequest)
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Pay request</h3>
                <button wire:click="closePayModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-4 flex justify-between rounded-xl border p-3.5 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                <span class="text-sm text-slate-500 dark:text-slate-400">Paying {{ $this->payingRequest->requester->name ?? '' }}</span>
                <b class="text-sm text-slate-900 dark:text-white">{{ money_format($this->payingRequest->amount) }}</b>
            </div>

            @if($payError)
            <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-3 text-sm text-rose-600 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-400">
                {{ $payError }}
            </div>
            @else
            @if($this->pinRequired)
            @if($this->userHasPin)
            <div class="mt-4">
                <x-ui.password label="Transaction PIN" wire:model="pin" name="pin" placeholder="••••" error="pin" />
            </div>
            <button wire:click="confirmPay" wire:loading.attr="disabled" wire:target="confirmPay" class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="confirmPay">Confirm payment</span>
                <span wire:loading wire:target="confirmPay">Processing...</span>
            </button>
            @elseif($this->canCreatePin)
            <div class="mt-4 space-y-4">
                <x-ui.password label="New PIN" wire:model="newPin" name="newPin" placeholder="Choose a 4-digit PIN" error="newPin" />
                <x-ui.password label="Confirm PIN" wire:model="newPinConfirmation" name="newPinConfirmation" placeholder="Re-enter PIN" error="newPinConfirmation" />
            </div>
            <button wire:click="createPinForPayment" wire:loading.attr="disabled" wire:target="createPinForPayment" class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="createPinForPayment">Set PIN & continue</span>
                <span wire:loading wire:target="createPinForPayment">Saving...</span>
            </button>
            @else
            <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">You need a transaction PIN to pay this request. Contact support to have one assigned.</p>
            @endif
            @else
            <button wire:click="confirmPay" wire:loading.attr="disabled" wire:target="confirmPay" class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="confirmPay">Confirm payment</span>
                <span wire:loading wire:target="confirmPay">Processing...</span>
            </button>
            @endif
            @endif
            @endif
        </div>
    </div>
</div>