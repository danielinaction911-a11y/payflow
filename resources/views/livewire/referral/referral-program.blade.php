<div x-data="{ copied: false }">
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">Share {{ setting('site_name', 'us') }}</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Referral program</h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Invite people you trust and earn rewards when they start investing.</p>
        </div>
    </section>

    {{-- Hero --}}
    <section class="relative overflow-hidden rounded-2xl border border-emerald-400/20 bg-[#112b29] p-6 sm:p-8">
        <div class="absolute -right-10 -top-20 h-60 w-60 rounded-full bg-emerald-400/15 blur-3xl"></div>

        <div class="relative max-w-xl">
            <span class="text-xs font-semibold uppercase tracking-[.18em] text-emerald-300">Earn together</span>
            <h2 class="mt-2 text-2xl font-semibold text-white">
                Give ${{ (int) setting('referral_bonus_signup', 25) }}. Get ${{ (int) setting('referral_bonus_signup', 25) }}.
            </h2>
            <p class="mt-2 text-sm leading-relaxed text-emerald-100/70">
                When your friend funds an account with $250 or more, you both receive investment credit.
            </p>

            <div
                x-data="{
        copied: false,
        copy() {
            const text = '{{ $this->referralLink }}';
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => this.flash());
            } else {
                // Fallback for contexts without the Clipboard API
                // (non-HTTPS, or some embedded/in-app browsers).
                const el = document.createElement('textarea');
                el.value = text;
                el.style.position = 'fixed';
                el.style.opacity = '0';
                document.body.appendChild(el);
                el.focus();
                el.select();
                try { document.execCommand('copy'); this.flash(); } catch (e) {}
                document.body.removeChild(el);
            }
        },
        flash() {
            this.copied = true;
            setTimeout(() => this.copied = false, 1800);
        },
    }"
                class="mt-6 flex max-w-md flex-col gap-2 rounded-xl border border-white/10 bg-white/[.08] p-1.5 sm:flex-row sm:items-center sm:gap-0">
                <code class="min-w-0 flex-1 truncate px-3 text-sm text-white">{{ $this->referralLink }}</code>
                <button
                    @click="copy()"
                    class="shrink-0 rounded-lg bg-emerald-500 px-3 py-2.5 text-xs font-semibold text-white hover:bg-emerald-400 sm:py-2">
                    <span x-show="!copied">Copy link</span>
                    <span x-show="copied" x-cloak>Copied!</span>
                </button>
            </div>
        </div>
    </section>

    {{-- Stat cards --}}
    <div class="mt-6 grid gap-5 md:grid-cols-3">
        <div class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Referral earnings</p>
                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ money_format($this->totalEarnings) }}</p>
                </div>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-emerald-500/12 text-emerald-500">
                    <x-app-icon name="gift" class="h-[17px] w-[17px]" />
                </span>
            </div>
            <div class="mt-5 flex justify-between">
                <small class="text-slate-500 dark:text-slate-400">All time commission</small>
                <small class="font-semibold text-emerald-600 dark:text-emerald-500">+{{ money_format($this->earningsThisMonth) }} this month</small>
            </div>
        </div>

        <div class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Joined members</p>
                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $this->joinedCount }}</p>
                </div>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-sky-500/12 text-sky-500">
                    <x-app-icon name="user-round" class="h-[17px] w-[17px]" />
                </span>
            </div>
            <div class="mt-5 flex justify-between">
                <small class="text-slate-500 dark:text-slate-400">From your link</small>
                <small class="font-semibold text-emerald-600 dark:text-emerald-500">+{{ $this->joinedThisMonth }} this month</small>
            </div>
        </div>

        <div class="rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <div class="flex justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pending commission</p>
                    <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ money_format($this->pendingCommission) }}</p>
                </div>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-violet-500/12 text-violet-500">
                    <x-app-icon name="sparkles" class="h-[17px] w-[17px]" />
                </span>
            </div>
            <div class="mt-5 flex justify-between">
                <small class="text-slate-500 dark:text-slate-400">{{ $this->invitesToNextLevel }} invites to unlock</small>
                <small class="font-semibold text-emerald-600 dark:text-emerald-500">Level {{ $this->currentLevel + 1 }}</small>
            </div>
        </div>
    </div>

    {{-- Referred members list --}}
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
        <div class="border-b p-5 border-slate-200 dark:border-white/[.08]">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Your referrals</h2>
        </div>

        @forelse($this->myReferrals as $referral)
        <div class="flex items-center gap-3 border-b p-4 last:border-b-0 border-slate-100 dark:border-white/[.06]">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-sky-400 to-violet-500 text-xs font-bold text-white">
                {{ strtoupper(substr($referral->referred->name ?? '?', 0, 2)) }}
            </span>
            <div class="min-w-0 flex-1">
                <b class="truncate text-sm text-slate-900 dark:text-white">{{ $referral->referred->name ?? 'Deleted user' }}</b>
                <small class="block text-xs text-slate-500 dark:text-slate-400">Joined {{ time_ago($referral->created_at) }} · Level {{ $referral->level }}</small>
            </div>
            <span class="shrink-0 rounded-md bg-emerald-500/12 px-2 py-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
                {{ money_format($referral->commissions->where('status', 'paid')->sum('amount')) }} earned
            </span>
        </div>
        @empty
        <div class="flex flex-col items-center p-10 text-center">
            <span class="grid h-12 w-12 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                <x-app-icon name="user-round" class="h-5 w-5 text-slate-400" />
            </span>
            <b class="mt-3 text-sm text-slate-900 dark:text-white">No referrals yet</b>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Share your link above to start earning.</p>
        </div>
        @endforelse
    </section>

    {{-- Recent commission activity --}}
    @if($this->recentCommissions->isNotEmpty())
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
        <div class="border-b p-5 border-slate-200 dark:border-white/[.08]">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Commission activity</h2>
        </div>

        @foreach($this->recentCommissions as $commission)
        <div class="flex items-center gap-3 border-b p-4 last:border-b-0 border-slate-100 dark:border-white/[.06]">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl {{ $commission->status === 'paid' ? 'bg-emerald-500/12 text-emerald-500' : 'bg-amber-500/12 text-amber-500' }}">
                <x-app-icon name="gift" class="h-4 w-4" />
            </span>
            <div class="min-w-0 flex-1">
                <b class="truncate text-sm text-slate-900 dark:text-white">Commission from {{ $commission->referral->referred->name ?? 'referral' }}</b>
                <small class="block text-xs text-slate-500 dark:text-slate-400">{{ time_ago($commission->created_at) }}</small>
            </div>
            <div class="shrink-0 text-right">
                <b class="block text-sm text-emerald-600 dark:text-emerald-500">+{{ money_format($commission->amount) }}</b>
                <span class="text-[10px] font-semibold {{ $commission->status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                    {{ ucfirst($commission->status) }}
                </span>
            </div>
        </div>
        @endforeach
    </section>
    @endif
</div>