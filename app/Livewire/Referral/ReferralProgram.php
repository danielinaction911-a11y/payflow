<?php

namespace App\Livewire\Referral;

use App\Models\Referral;
use App\Models\ReferralCommission;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ReferralProgram extends Component
{
    #[Computed]
    public function referralLink(): string
    {
        return rtrim(url('register/?referral=' . auth()->user()->referral_code));
    }

    #[Computed]
    public function myReferrals()
    {
        return Referral::with('referred')
            ->where('referrer_id', Auth::id())
            ->latest()
            ->get();
    }

    #[Computed]
    public function myReferralIds()
    {
        return $this->myReferrals->pluck('id');
    }

    #[Computed]
    public function totalEarnings()
    {
        return ReferralCommission::whereIn('referral_id', $this->myReferralIds)
            ->where('status', 'paid')
            ->sum('amount');
    }

    #[Computed]
    public function earningsThisMonth()
    {
        return ReferralCommission::whereIn('referral_id', $this->myReferralIds)
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    #[Computed]
    public function joinedCount()
    {
        return $this->myReferrals->count();
    }

    #[Computed]
    public function joinedThisMonth()
    {
        return $this->myReferrals->filter(fn ($r) => $r->created_at->isSameMonth(now()))->count();
    }

    #[Computed]
    public function pendingCommission()
    {
        return ReferralCommission::whereIn('referral_id', $this->myReferralIds)
            ->where('status', 'pending')
            ->sum('amount');
    }

    #[Computed]
    public function currentLevel(): int
    {
        // Simple tiering: every 10 successful referrals bumps a level.
        return intdiv($this->joinedCount, 10) + 1;
    }

    #[Computed]
    public function invitesToNextLevel(): int
    {
        $remainder = $this->joinedCount % 10;
        return $remainder === 0 ? 10 : 10 - $remainder;
    }

    #[Computed]
    public function recentCommissions()
    {
        return ReferralCommission::with('referral.referred')
            ->whereIn('referral_id', $this->myReferralIds)
            ->latest()
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('livewire.referral.referral-program')->layout('components.layouts.app', [
            'title' => 'Referral Program',
        ]);
    }
}