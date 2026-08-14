<?php

namespace App\Livewire\Transfer;

use App\Enums\TransaferStatus;
use App\Models\Transfer;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class TransferHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $directionFilter = 'all'; // all | sent | received
    public ?int $viewing = null;

    #[Computed]
    public function statusOptions()
    {
        return TransaferStatus::cases();
    }

    #[Computed]
    public function transfers()
    {
        return Transfer::query()
            ->with(['sender', 'recipient'])
            ->where(function ($q) {
                $q->where('sender_id', auth()->id())
                    ->orWhere('recipient_id', auth()->id());
            })
            ->when($this->directionFilter === 'sent', fn ($q) => $q->where('sender_id', auth()->id()))
            ->when($this->directionFilter === 'received', fn ($q) => $q->where('recipient_id', auth()->id()))
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search !== '', fn ($q) => $q->where(function ($q) {
                $term = "%{$this->search}%";
                $q->where('reference', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHas('sender', fn ($q) => $q->where('username', 'like', $term))
                    ->orWhereHas('recipient', fn ($q) => $q->where('username', 'like', $term));
            }))
            ->latest()
            ->paginate(15);
    }

    #[Computed]
    public function selectedTransfer()
    {
        if (! $this->viewing) {
            return null;
        }

        return Transfer::query()
            ->with(['sender', 'recipient'])
            ->where(function ($q) {
                $q->where('sender_id', auth()->id())
                    ->orWhere('recipient_id', auth()->id());
            })
            ->find($this->viewing);
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function setDirectionFilter(string $direction): void
    {
        $this->directionFilter = $direction;
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
        return view('livewire.transfer.transfer-history')->layout('components.layouts.app', [
            'title' => 'Transfer History',
        ]);
    }
}