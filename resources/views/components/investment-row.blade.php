@props(['investment'])

@php
    $statusClasses = match($investment->status) {
        \App\Enums\InvestmentStatus::Active => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
        \App\Enums\InvestmentStatus::Completed => 'bg-sky-500/10 text-sky-600 dark:text-sky-400',
        \App\Enums\InvestmentStatus::Cancelled => 'bg-rose-500/10 text-rose-600 dark:text-rose-400',
        default => 'bg-amber-500/10 text-amber-600 dark:text-amber-400', // covers a null status column
    };

    $progress = 0;
    if ($investment->starts_at && $investment->ends_at) { 
         $totalDays = $investment->starts_at->diffInDays($investment->ends_at) ?: 1;
         $elapsedDays = min($investment->starts_at->diffInDays(now()), $totalDays);
         $progress = round(($elapsedDays / $totalDays) * 100);
    }
@endphp

<div class="rounded-xl border p-3.5 border-slate-200 bg-slate-50 dark:border-white/[.08] dark:bg-white/[.03]">
    {{-- Plan name + status --}}
    <div class="flex items-center justify-between gap-2">
        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $investment->plan->name ?? 'Plan' }}</span>
        <span class="shrink-0 rounded-md px-2 py-1 text-[10px] font-semibold {{ $statusClasses }}">
            {{ $investment->status ? ucfirst($investment->status->value) : 'Pending' }}
        </span>
    </div>

    {{-- Amount invested / expected return / ROI --}}
    <div class="mt-3 grid grid-cols-3 gap-2 text-center">
        <div>
            <p class="text-[10px] text-slate-500 dark:text-slate-400">Invested</p>
            <p class="mt-0.5 text-xs font-semibold text-slate-900 dark:text-white">{{ money_format($investment->amount_invested ?? 0) }}</p>
        </div>
        <div>
            <p class="text-[10px] text-slate-500 dark:text-slate-400">Expected return</p>
            <p class="mt-0.5 text-xs font-semibold text-emerald-600 dark:text-emerald-500">{{ money_format($investment->expected_total_return ?? 0) }}</p>
        </div>
        <div>
            <p class="text-[10px] text-slate-500 dark:text-slate-400">ROI</p>
            <p class="mt-0.5 text-xs font-semibold text-slate-900 dark:text-white">{{ rtrim(rtrim(number_format($investment->roi_percentage ?? 0, 2), '0'), '.') }}%</p>
        </div>
    </div>

    {{-- Time progress --}}
    @if($investment->status === \App\Enums\InvestmentStatus::Active && $investment->starts_at && $investment->ends_at)
    <div class="mt-3">
        <div class="h-1.5 overflow-hidden rounded-full bg-slate-200 dark:bg-white/[.08]">
            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $progress }}%"></div>
        </div>
        <div class="mt-1 flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400">
            <span>{{ $progress }}% complete</span>
            <span>Ends {{ $investment->ends_at->format('M j, Y') }}</span>
        </div>
    </div>
    @endif

    {{-- Paid out so far + last payout --}}
    <div class="mt-3 flex items-center justify-between border-t pt-2.5 text-[11px] border-slate-200 dark:border-white/[.06]">
        <span class="text-slate-500 dark:text-slate-400">
            Paid out: <b class="font-semibold text-slate-700 dark:text-slate-300">{{ money_format($investment->total_paid_out ?? 0) }}</b>
        </span>
        @if($investment->last_profit_at)
        <span class="text-slate-500 dark:text-slate-400">Last payout {{ time_ago($investment->last_profit_at) }}</span>
        @endif
    </div>
</div>