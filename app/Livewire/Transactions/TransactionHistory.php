<?php

namespace App\Livewire\Transactions;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class TransactionHistory extends Component
{
    use WithPagination;

    #[Url]
    public ?int $viewing = null;

    public string $typeFilter = 'all';
    public string $statusFilter = 'all';
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function transactions()
    {
        $query = Transaction::where('user_id', Auth::id())->latest();

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('reference', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        return $query->paginate(12);
    }

    #[Computed]
    public function selectedTransaction()
    {
        return $this->viewing
            ? Transaction::where('user_id', Auth::id())->find($this->viewing)
            : null;
    }

    public function setTypeFilter(string $type): void
    {
        $this->typeFilter = $type;
        $this->resetPage();
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
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

    #[Computed]
    public function typeOptions()
    {
        return TransactionType::cases();
    }

    #[Computed]
    public function statusOptions()
    {
        return TransactionStatus::cases();
    }

    public function render()
    {
        return view('livewire.transactions.transaction-history')->layout('components.layouts.app', [
            'title' => 'Transaction History',
        ]);
    }
}