<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public string $activeTab = 'dashboard';

    #[Computed]
    public function user()
    {
        return auth()->user();
    }

    #[Computed]
    public function netWorth()
    {
        return (float) $this->user->balance + (float) $this->user->profit_balance;
    }

    #[Computed]
    public function recentTransactions()
    {
        return Transaction::where('user_id', auth()->id())
            ->latest()
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function liveActivity()
    {
        return Transaction::with('user')
            ->where('status', 'completed')
            ->latest()
            ->limit(4)
            ->get();
    }

    #[Computed]
    public function recentTrades()
    {
        return auth()->user()->trades()
            ->with('tradingPair.baseCurrency')
            ->latest()
            ->limit(15)
            ->get();
    }

    #[Computed]
    public function investmentAllocation()
    {
        return auth()->user()->investments()
            ->with('plan')
            ->latest()
            ->get();
    }

    #[Computed]
    public function totalInvested()
    {
        return $this->investmentAllocation->sum('total');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('components.layouts.app', [
            'title' => 'Dashboard',
        ]);
    }
}
