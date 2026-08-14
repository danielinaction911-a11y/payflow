<div>
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Account holder</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Your profile</h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Keep your personal details and verification information up to date.</p>
        </div>
        <button wire:click="saveProfile" wire:loading.attr="disabled" wire:target="saveProfile" class="primary-button disabled:cursor-not-allowed disabled:opacity-50">
            <svg wire:loading.remove wire:target="saveProfile" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6 9 17l-5-5"></path>
            </svg>
            <span wire:loading.remove wire:target="saveProfile">Save changes</span>
            <span wire:loading wire:target="saveProfile">Saving...</span>
        </button>
    </section>

    @if(auth()->user()->status && auth()->user()->status !== 'active')
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-500/20 dark:bg-rose-500/10">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 shrink-0 text-rose-500">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" x2="12" y1="8" y2="12"></line>
            <line x1="12" x2="12.01" y1="16" y2="16"></line>
        </svg>
        <div>
            <b class="text-sm text-rose-700 dark:text-rose-400">Account {{ ucfirst(auth()->user()->status) }}</b>
            <p class="mt-0.5 text-xs text-rose-600 dark:text-rose-400/80">Your account has restrictions. Contact support for assistance.</p>
        </div>
    </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[340px_minmax(0,1fr)]">
        {{-- Left column --}}
        <div class="space-y-6">
            <section class="rounded-2xl border p-6 text-center border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <div class="relative mx-auto w-fit">
                    @if($newAvatar)
                    <img src="{{ $newAvatar->temporaryUrl() }}" alt="New avatar preview" class="mx-auto h-20 w-20 rounded-[26px] object-cover shadow-lg">
                    <button
                        type="button"
                        wire:click="$set('newAvatar', null)"
                        class="absolute -top-1.5 -left-1.5 grid h-6 w-6 place-items-center rounded-full border-2 border-white bg-rose-500 text-white shadow-lg hover:bg-rose-400 dark:border-[#111a2d]"
                        title="Cancel photo change">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                    @elseif(auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar)))
                    <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="mx-auto h-20 w-20 rounded-[26px] object-cover shadow-lg">
                    @else
                    <span class="mx-auto grid h-20 w-20 place-items-center rounded-[26px] bg-gradient-to-br from-sky-400 to-violet-500 text-xl font-bold text-white shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                    @endif

                    <label class="absolute -bottom-1.5 -right-1.5 grid h-8 w-8 cursor-pointer place-items-center rounded-full border-2 border-white bg-emerald-500 text-white shadow-lg hover:bg-emerald-400 dark:border-[#111a2d]">
                        <input type="file" wire:model="newAvatar" class="hidden" accept="image/*">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                        </svg>
                    </label>
                </div>

                @if($newAvatar)
                <p class="mt-2 text-xs text-emerald-600 dark:text-emerald-500">New photo selected — click "Save changes" to apply.</p>
                @endif
                <div wire:loading wire:target="newAvatar" class="mt-2 text-xs text-slate-400">Uploading...</div>
                @error('newAvatar') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror

                <h2 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ auth()->user()->username }}</p>

                @if($this->kycStatusMeta)
                <span class="mt-4 inline-flex items-center gap-1 rounded-lg px-2 py-1 text-xs font-semibold {{ $this->kycStatusMeta['badge_class'] }}">
                    <x-app-icon name="shield-check" class="h-3.5 w-3.5" /> {{ $this->kycStatusMeta['label'] }}
                </span>
                @else
                <span class="mt-4 inline-flex items-center gap-1 rounded-lg bg-slate-500/10 px-2 py-1 text-xs font-semibold text-slate-500 dark:text-slate-400">
                    Not verified
                </span>
                @endif

                <div class="my-6 border-t border-slate-200 dark:border-white/[.08]"></div>

                <div class="space-y-3 text-left text-sm">
                    <div class="flex justify-between gap-4"><span class="text-slate-500 dark:text-slate-400">Member since</span><span class="text-right font-medium text-slate-900 dark:text-white">{{ auth()->user()->created_at->format('F Y') }}</span></div>
                    <div class="flex justify-between gap-4"><span class="text-slate-500 dark:text-slate-400">Email status</span><span class="text-right font-medium {{ auth()->user()->email_verified_at ? 'text-emerald-600 dark:text-emerald-500' : 'text-amber-600 dark:text-amber-400' }}">{{ auth()->user()->email_verified_at ? 'Verified' : 'Unverified' }}</span></div>
                    <div class="flex justify-between gap-4"><span class="text-slate-500 dark:text-slate-400">KYC status</span><span class="text-right font-medium text-slate-900 dark:text-white">{{ $this->kycStatusMeta['label'] ?? 'Not Submitted' }}</span></div>
                    <div class="flex justify-between gap-4"><span class="text-slate-500 dark:text-slate-400">2FA</span><span class="text-right font-medium {{ auth()->user()->two_factor_confirmed_at ? 'text-emerald-600 dark:text-emerald-500' : 'text-slate-400' }}">{{ auth()->user()->two_factor_confirmed_at ? 'Enabled' : 'Disabled' }}</span></div>
                </div>

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                <div class="mt-4 rounded-2xl border border-amber-500/20 bg-amber-500/5 p-4 backdrop-blur-sm">
                    <div class="flex-1">
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Verify your email address to secure your account and unlock all platform
                            features.
                        </p>
                        @if(! auth()->user()->email_verified_at)
                        <button wire:click="resendVerification" wire:loading.attr="disabled" wire:target="resendVerification" class="mt-4 w-full btn btn-outline-secondary disabled:cursor-not-allowed disabled:opacity-50">
                            <span wire:loading.remove wire:target="resendVerification">Resend verification email</span>
                            <span wire:loading wire:target="resendVerification">Sending...</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endif
            </section>

            {{-- Referral --}}
            <section class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="font-semibold text-slate-900 dark:text-white">Referral code</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Share this to earn referral rewards.</p>
                <div class="mt-4">
                    <x-ui.copy-value :value="auth()->user()->referral_code" />
                </div>
            </section>

            {{-- Appearance --}}
            <section
                x-data="{ appearance: $flux.appearance }"
                x-effect="appearance = $flux.appearance"
                class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="font-semibold text-slate-900 dark:text-white">Appearance</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Your current interface follows your selected theme.</p>

                <div class="theme-picker">
                    <button type="button" wire:click="updateTheme('dark')" @click="$flux.appearance = 'dark'" class="theme-option" :class="appearance === 'dark' ? 'is-active' : ''">
                        <div class="theme-check" x-show="appearance === 'dark'" x-cloak>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"></path>
                            </svg>
                        </div>
                        <div class="theme-preview" style="background:#0d1526">
                            <i class="block h-2 w-8 rounded bg-white/70"></i>
                            <i class="mt-2 block h-5 rounded bg-emerald-500/40"></i>
                        </div>
                        <p class="theme-label" :class="appearance === 'dark' ? 'is-active' : ''">Dark</p>
                    </button>

                    <button type="button" wire:click="updateTheme('light')" @click="$flux.appearance = 'light'" class="theme-option" :class="appearance === 'light' ? 'is-active' : ''">
                        <div class="theme-check" x-show="appearance === 'light'" x-cloak>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"></path>
                            </svg>
                        </div>
                        <div class="theme-preview" style="background:#f1f5f9">
                            <i class="block h-2 w-8 rounded bg-slate-600/50"></i>
                            <i class="mt-2 block h-5 rounded bg-emerald-300/70"></i>
                        </div>
                        <p class="theme-label" :class="appearance === 'light' ? 'is-active' : ''">Light</p>
                    </button>
                </div>

                <div class="my-6 border-t border-slate-200 dark:border-white/[.08]"></div> 

                <livewire:settings.delete-user-form />
            </section>
        </div>

        {{-- Right column --}}
        <div class="space-y-6">
            <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="font-semibold text-slate-900 dark:text-white">Personal information</h2>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <x-ui.input label="Full name" wire:model="name" name="name" error="name" />
                    <x-ui.input label="Username" wire:model="username" name="username" error="username" />
                    <x-ui.input label="Email address" type="email" wire:model="email" name="email" error="email" />
                    <x-ui.input label="Phone number" wire:model="phone" name="phone" error="phone" />
                    <x-ui.select
                        wire:model="country"
                        id="country"
                        label="{{ __('Country') }}"
                        name="country"
                        placeholder="Select country"
                        error="country">
                        @foreach(getCountries() as $country)
                        <option value="{{ $country['name'] }}" {{ old('country', $user->country ?? '') === $country['name'] ? 'selected' : '' }}>
                            {{ $country['name'] }}
                        </option>
                        @endforeach
                    </x-ui.select>
                    <x-ui.input label="State" wire:model="state" name="state" error="state" />
                    <x-ui.input label="City" wire:model="city" name="city" error="city" />
                    <x-ui.input label="Address" wire:model="address" name="address" error="address" />
                </div>

                @if($email !== auth()->user()->email)
                <p class="mt-3 text-xs text-amber-600 dark:text-amber-400">Changing your email will require re-verification.</p>
                @endif
            </section>

            {{-- Restrictions --}}
            @if(count($this->restrictions) > 0)
            <section class="rounded-2xl border p-5 sm:p-6 border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/[.06]">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 dark:text-amber-400">
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    </svg>
                    <h2 class="font-semibold text-amber-800 dark:text-amber-300">Account restrictions</h2>
                </div>
                <div class="mt-4 space-y-3">
                    @foreach($this->restrictions as $restriction)
                    <div class="rounded-xl border border-amber-200 bg-white p-3.5 dark:border-amber-500/15 dark:bg-white/[.03]">
                        <b class="text-sm text-slate-900 dark:text-white">{{ $restriction['label'] }}</b>
                        <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">{{ $restriction['message'] }}</p>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Limits & fees --}}
            <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="font-semibold text-slate-900 dark:text-white">Limits & fees</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Your current transaction limits and applicable fees.</p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Transfer limits</p>
                        <div class="mt-2 space-y-1.5 text-xs">
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Daily</span><b class="text-slate-900 dark:text-white">{{ money_format(auth()->user()->daily_transfer_limit ?? 0) }}</b></div>
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Weekly</span><b class="text-slate-900 dark:text-white">{{ money_format(auth()->user()->weekly_transfer_limit ?? 0) }}</b></div>
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Monthly</span><b class="text-slate-900 dark:text-white">{{ money_format(auth()->user()->monthly_transfer_limit ?? 0) }}</b></div>
                        </div>
                    </div>

                    <div class="rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Withdrawal limits</p>
                        <div class="mt-2 space-y-1.5 text-xs">
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Daily</span><b class="text-slate-900 dark:text-white">{{ money_format(auth()->user()->daily_withdrawal_limit ?? 0) }}</b></div>
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Weekly</span><b class="text-slate-900 dark:text-white">{{ money_format(auth()->user()->weekly_withdrawal_limit ?? 0) }}</b></div>
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Monthly</span><b class="text-slate-900 dark:text-white">{{ money_format(auth()->user()->monthly_withdrawal_limit ?? 0) }}</b></div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- KYC verification --}}
            <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="font-semibold text-slate-900 dark:text-white">Identity verification</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Verify your identity to unlock higher limits.</p>

                @if($this->latestKycDocument && $this->kycStatusMeta)
                <div class="mt-4 flex items-center gap-3 rounded-xl border p-4 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $this->kycStatusMeta['icon_class'] }}">
                        <x-app-icon name="shield-check" class="h-4 w-4" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <b class="block text-sm text-slate-900 dark:text-white">{{ $this->latestKycDocument->kyc->type ?? 'Document' }}</b>
                        <small class="text-xs text-slate-500 dark:text-slate-400">Submitted {{ time_ago($this->latestKycDocument->submitted_at ?? $this->latestKycDocument->created_at) }}</small>
                        @if($this->latestKycDocument->status === \App\Enums\KycStatus::Rejected && $this->latestKycDocument->rejection_reason)
                        <p class="mt-1 text-xs text-rose-500">{{ $this->latestKycDocument->rejection_reason }}</p>
                        @endif
                    </div>
                    <span class="shrink-0 rounded-md px-2 py-1 text-[10px] font-semibold {{ $this->kycStatusMeta['badge_class'] }}">
                        {{ $this->kycStatusMeta['label'] }}
                    </span>
                </div>
                @endif

                @if(! $this->latestKycDocument || $this->latestKycDocument->status === \App\Enums\KycStatus::Rejected)
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @foreach($this->kycTypes as $kyc)
                    <button wire:click="openKycModal({{ $kyc->id }})" class="flex items-center gap-3 rounded-xl border p-3.5 text-left border-slate-200 bg-slate-50 hover:bg-slate-100 dark:border-white/[.08] dark:bg-white/[.03] dark:hover:bg-white/[.06]">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-sky-500/12 text-sky-500">
                            <x-app-icon name="file-text" class="h-4 w-4" />
                        </span>
                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $kyc->type }}</span>
                    </button>
                    @endforeach
                </div>
                @endif
            </section>
        </div>
    </div> 

    {{-- KYC submission modal --}}
    <div x-show="$wire.showKycModal" x-cloak class="fixed inset-0 z-50 grid place-items-center bg-slate-900/60 p-4 backdrop-blur-sm" x-transition.opacity @click.self="$wire.closeKycModal()" @keydown.escape.window="$wire.closeKycModal()">
        <div x-show="$wire.showKycModal" x-transition class="w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Verify with {{ $this->selectedKyc->type ?? '' }}</h3>
                <button wire:click="closeKycModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            @if($this->selectedKyc?->required_fields)
            <div class="mt-5 space-y-4">
                @foreach($this->selectedKyc->required_fields as $field)
                @if($field['type'] === 'file')
                <div>
                    <x-ui.label>{{ $field['label'] }}</x-ui.label>
                    <input type="file" wire:model="kycUploads.{{ $field['name'] }}" class="mt-2 w-full rounded-xl border px-3 py-2.5 text-sm border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white">
                    <div wire:loading wire:target="kycUploads.{{ $field['name'] }}" class="mt-1 text-xs text-slate-400">Uploading...</div>
                    @error('kycUploads.' . $field['name']) <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                @else
                <x-ui.input :label="$field['label']" wire:model="kycFields.{{ $field['name'] }}" :error="'kycFields.' . $field['name']" />
                @endif
                @endforeach
            </div>
            @endif

            <button wire:click="submitKyc" wire:loading.attr="disabled" wire:target="submitKyc" class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-50">
                <span wire:loading.remove wire:target="submitKyc">Submit for verification</span>
                <span wire:loading wire:target="submitKyc">Submitting...</span>
            </button>
        </div>
    </div> 
</div>