<?php

namespace App\Livewire\Deposit;

use App\Enums\DepositStatus;
use App\Models\Deposit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class DepositHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public ?int $viewing = null;

    #[Computed]
    public function statusOptions()
    {
        return DepositStatus::cases();
    }

    #[Computed]
    public function deposits()
    {
        return Deposit::query()
            ->where('user_id', auth()->id())
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search !== '', fn ($q) => $q->where(function ($q) {
                $q->where('transaction_id', 'like', "%{$this->search}%")
                    ->orWhere('method', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(15);
    }

    #[Computed]
    public function selectedDeposit()
    {
        if (! $this->viewing) {
            return null;
        }

        return Deposit::where('user_id', auth()->id())->find($this->viewing);
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function view(int $id): void
    {
        $this->viewing = $id;
    }

    public function closeDetails(): void
    {
        $this->viewing = null;
    }

    public function render()
    {
        return view('livewire.deposit.deposit-history')->layout('components.layouts.app', [
            'title' => 'Deposit History',
        ]);
    }
}