<?php

namespace App\Livewire\Investment;

use App\Models\Investment;
use App\Models\ProfitLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class InvestmentHistory extends Component
{
    use WithPagination;

    #[Url]
    public ?int $viewing = null;

    public string $statusFilter = 'all';

    #[Computed]
    public function investments()
    {
        $query = Investment::with('plan')
            ->where('user_id', Auth::id())
            ->latest();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->paginate(10, pageName: 'investments-page');
    }

    #[Computed]
    public function selectedInvestment()
    {
        return $this->viewing
            ? Investment::with('plan')->where('user_id', Auth::id())->find($this->viewing)
            : null;
    }

    #[Computed]
    public function profitLogs()
    {
        if (! $this->selectedInvestment) {
            return null;
        }

        return ProfitLog::where('investment_id', $this->selectedInvestment->id)
            ->latest('paid_at')
            ->paginate(8, pageName: 'profits-page');
    }

    #[Computed]
    public function totalPaidOut()
    {
        if (! $this->selectedInvestment) {
            return 0;
        }

        return ProfitLog::where('investment_id', $this->selectedInvestment->id)
            ->where('status', 'paid')
            ->sum('amount');
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage('investments-page');
    }

    public function view(int $investmentId): void
    {
        $this->viewing = $investmentId;
        $this->resetPage('profits-page');
    }

    public function closeDetails(): void
    {
        $this->viewing = null;
    }

    public function render()
    {
        return view('livewire.investment.investment-history')->layout('components.layouts.app', [
            'title' => 'Investment History',
        ]);
    }
}