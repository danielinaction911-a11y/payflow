<div>
    <section class="mb-7">
        <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Account protection</p>
        <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Privacy & Security</h1>
        <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Manage your login security, transaction PIN, and connected sessions.</p>
    </section>
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
        <div class="space-y-6">

            {{-- Transaction PIN --}}
            <section class="rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <div class="flex items-center gap-3 border-b p-5 border-slate-200 dark:border-white/[.08]">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-500/12 text-emerald-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Transaction PIN</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Required to authorize withdrawals</p>
                    </div>
                </div>

                <div class="p-5">
                    @if($this->userHasPin)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-300">Your PIN is set and active.</p>
                            @if(auth()->user()->pin_update_at)
                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Last changed {{ time_ago(auth()->user()->pin_update_at) }}</p>
                            @endif
                        </div>
                        <button wire:click="openPinModal" class="primary-button">
                            Change PIN
                        </button>
                    </div>
                    @elseif($this->canCreatePin)
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-600 dark:text-slate-300">You haven't set a transaction PIN yet.</p>
                        <button wire:click="openPinModal" class="primary-button">
                            Create PIN
                        </button>
                    </div>
                    @else
                    <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3.5 dark:border-amber-500/20 dark:bg-amber-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 shrink-0 text-amber-600 dark:text-amber-400">
                            <path d="M12 9v4"></path>
                            <path d="M12 17h.01"></path>
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                        </svg>
                        <p class="text-sm text-amber-800 dark:text-amber-300">You don't have a transaction PIN, and self-service PIN creation is currently disabled. Please contact support to have one assigned.</p>
                    </div>
                    @endif
                </div>
            </section>

            {{-- Password --}}
            <section class="rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <div class="flex items-center gap-3 border-b p-5 border-slate-200 dark:border-white/[.08]">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-sky-500/12 text-sky-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Password</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Update the password used to sign in</p>
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-600 dark:text-slate-300">Keep your account secure with a strong password.</p>
                        <button wire:click="openPasswordModal" class="primary-button">Change password</button>
                    </div>
                </div>
            </section>

            {{-- Two-Factor Authentication --}}
            @if (setting('two_factor_authentication', 1))
            <livewire:security.two-factor-authentication />
            @endif

            {{-- Login activity --}}
            <section class="rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <div class="border-b p-5 border-slate-200 dark:border-white/[.08]">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Login activity</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Recent sign-ins to your account</p>
                </div>

                @forelse($this->loginActivities as $activity)
                <div class="flex items-center gap-3 border-b p-4 last:border-b-0 border-slate-100 dark:border-white/[.06]">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-slate-100 dark:bg-white/[.06]">
                        @if(in_array($activity->device_type, ['mobile', 'tablet']))
                        {{-- Phone icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500 dark:text-slate-400">
                            <rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect>
                            <path d="M12 18h.01"></path>
                        </svg>
                        @else
                        {{-- Monitor/desktop icon (also the fallback for rows recorded before device_type existed) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500 dark:text-slate-400">
                            <rect width="20" height="14" x="2" y="3" rx="2"></rect>
                            <line x1="8" x2="16" y1="21" y2="21"></line>
                            <line x1="12" x2="12" y1="17" y2="21"></line>
                        </svg>
                        @endif
                    </span>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <b class="truncate text-sm text-slate-900 dark:text-white">{{ $activity->device ?? 'Unknown device' }}</b>
                            @if($activity->ip_address === $this->currentSessionIp)
                            <span class="shrink-0 rounded-md bg-emerald-500/12 px-1.5 py-0.5 text-[9px] font-bold text-emerald-600 dark:text-emerald-400">This device</span>
                            @endif
                        </div>
                        <small class="block truncate text-xs text-slate-500 dark:text-slate-400">
                            {{ $activity->ip_address }}{{ $activity->location ? ' · ' . $activity->location : '' }} · {{ time_ago($activity->logged_in_at) }}
                        </small>
                    </div>

                    @if($activity->ip_address !== $this->currentSessionIp)
                    <button wire:click="confirmLogout({{ $activity->id }})" class="session-logout-btn">
                        Log out
                    </button>
                    @endif
                </div>
                @empty
                <x-ui.empty-state
                    icon="clock"
                    title="No login activity yet"
                    subtitle="Sign-ins to your account will show up here." />
                @endforelse
            </section>
        </div>

        {{-- Security alerts sidebar --}}
        <aside class="h-max rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Security alerts</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Recent security-related events</p>

            {{-- Capped height + scroll: this list can grow unbounded over an account's
                 lifetime (every PIN change, password change, remote logout, etc. adds
                 one), so it shouldn't be allowed to push the rest of the page down. --}}
            <div class="mt-4 max-h-[420px] space-y-4 overflow-y-auto pr-1">
                @forelse($this->securityAlerts as $alert)
                <div class="flex gap-3">
                    <span class="mt-1 grid h-7 w-7 shrink-0 place-items-center rounded-lg {{ $alert->resolved_at ? 'bg-emerald-500/12 text-emerald-500' : 'bg-amber-500/12 text-amber-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v4"></path>
                            <path d="M12 17h.01"></path>
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-slate-700 dark:text-slate-300">{{ $alert->description }}</p>
                        <small class="text-[10px] text-slate-400 dark:text-slate-500">{{ time_ago($alert->created_at) }}</small>
                    </div>
                </div>
                @empty
                <x-ui.empty-state
                    icon="shield"
                    title="No security alerts"
                    subtitle="You're all caught up. New events will appear here."
                    compact />
                @endforelse
            </div>
        </aside>
    </div>

    {{-- PIN modal --}}
    <div x-show="$wire.showPinModal" x-cloak class="fixed inset-0 z-50 grid place-items-center bg-slate-900/60 p-4 backdrop-blur-sm" x-transition.opacity @click.self="$wire.closePinModal()" @keydown.escape.window="$wire.closePinModal()">
        <div x-show="$wire.showPinModal" x-transition class="w-full max-w-sm rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $this->userHasPin ? 'Change PIN' : 'Create PIN' }}</h3>
                <button wire:click="closePinModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-5 space-y-4">
                @if($this->userHasPin && ! $this->currentPinVerified)
                <div wire:key="pin-step-current" class="space-y-3">
                    <x-ui.pin-input
                        label="Current PIN"
                        wireModel="currentPin"
                        error="currentPin" />
                    <button wire:click="verifyCurrentPin" wire:loading.attr="disabled" wire:target="verifyCurrentPin" class="primary-button w-full justify-center disabled:cursor-not-allowed disabled:opacity-50">
                        <span wire:loading.remove wire:target="verifyCurrentPin">Continue</span>
                        <span wire:loading wire:target="verifyCurrentPin">Checking...</span>
                    </button>
                </div>
                @else
                <div wire:key="pin-step-new" class="space-y-3">
                    <x-ui.pin-input
                        label="New PIN"
                        wireModel="newPin"
                        error="newPin" />
                    <x-ui.pin-input
                        label="Confirm new PIN"
                        wireModel="newPinConfirmation"
                        error="newPinConfirmation" />
                </div>
                @endif
            </div>

            @if($this->userHasPin ? $this->currentPinVerified : true)
            <button wire:click="savePin" wire:loading.attr="disabled" wire:target="savePin" class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-50">
                <span wire:loading.remove wire:target="savePin">Save PIN</span>
                <span wire:loading wire:target="savePin">Saving...</span>
            </button>
            @endif
        </div>
    </div>

    {{-- Password modal --}}
    <div x-show="$wire.showPasswordModal" x-cloak class="fixed inset-0 z-50 grid place-items-center bg-slate-900/60 p-4 backdrop-blur-sm" x-transition.opacity @click.self="$wire.closePasswordModal()" @keydown.escape.window="$wire.closePasswordModal()">
        <div x-show="$wire.showPasswordModal" x-transition class="w-full max-w-sm rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Change password</h3>
                <button wire:click="closePasswordModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-5 space-y-4">
                @if(! $this->currentPasswordVerified)
                <div wire:key="password-step-current" class="space-y-3">
                    <x-ui.password label="Current password" wire:model="currentPassword" name="currentPassword" placeholder="Enter your current password" error="currentPassword" />
                    <button wire:click="verifyCurrentPassword" wire:loading.attr="disabled" wire:target="verifyCurrentPassword" class="primary-button w-full justify-center disabled:cursor-not-allowed disabled:opacity-50">
                        <span wire:loading.remove wire:target="verifyCurrentPassword">Continue</span>
                        <span wire:loading wire:target="verifyCurrentPassword">Checking...</span>
                    </button>
                </div>
                @else
                <div wire:key="password-step-new" class="space-y-3">
                    <x-ui.password label="New password" wire:model="newPassword" name="newPassword" placeholder="Enter a new password" error="newPassword" />
                    <x-ui.password label="Confirm new password" wire:model="newPasswordConfirmation" name="newPasswordConfirmation" placeholder="Re-enter the new password" error="newPasswordConfirmation" />
                </div>
                @endif
            </div>

            @if($this->currentPasswordVerified)
            <button wire:click="savePassword" wire:loading.attr="disabled" wire:target="savePassword" class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-50">
                <span wire:loading.remove wire:target="savePassword">Save password</span>
                <span wire:loading wire:target="savePassword">Saving...</span>
            </button>
            @endif
        </div>
    </div>

    {{-- Password-gated logout modal --}}
    <div x-show="$wire.showLogoutModal" x-cloak class="fixed inset-0 z-50 grid place-items-center bg-slate-900/60 p-4 backdrop-blur-sm" x-transition.opacity @click.self="$wire.closeLogoutModal()" @keydown.escape.window="$wire.closeLogoutModal()">
        <div x-show="$wire.showLogoutModal" x-transition class="w-full max-w-sm rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-rose-500/12 text-rose-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect>
                        <path d="M12 18h.01"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Log out this device?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Confirm your password to continue.</p>
                </div>
            </div>

            <div class="mt-5">
                <x-ui.password label="Password" wire:model="logoutPassword" name="logoutPassword" placeholder="Enter your password" error="logoutPassword" />
            </div>

            <div class="mt-5 flex gap-2">
                <button wire:click="closeLogoutModal" class="flex-1 rounded-xl border py-2.5 text-sm font-semibold border-slate-200 bg-slate-50 hover:bg-slate-100 dark:border-white/[.08] dark:bg-white/[.035] dark:hover:bg-white/[.06] dark:text-white">
                    Cancel
                </button>
                <button wire:click="revokeSession" wire:loading.attr="disabled" wire:target="revokeSession" class="flex-1 rounded-xl py-2.5 text-sm font-semibold !bg-rose-500 !text-white hover:!bg-rose-400 disabled:cursor-not-allowed disabled:opacity-70">
                    <span wire:loading.remove wire:target="revokeSession">Log out device</span>
                    <span wire:loading wire:target="revokeSession">Logging out...</span>
                </button>
            </div>
        </div>
    </div>
</div>