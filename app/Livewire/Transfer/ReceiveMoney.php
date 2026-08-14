<?php

namespace App\Livewire\Transfer;

use App\Enums\TransaferStatus;
use App\Models\Transfer;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

#[Layout('components.layouts.app')]
class ReceiveMoney extends Component
{
    public string $username;
    public string $qrSvg;

    public function mount(): void
    {
        $user = auth()->user();

        $this->username = $user->username;
        $this->qrSvg = QrCode::size(220)
            ->margin(0)
            ->generate($this->username);
    }

    #[Computed]
    public function recentIncoming()
    {
        return Transfer::with('sender')
            ->where('recipient_id', auth()->id())
            ->where('status', TransaferStatus::Completed->value)
            ->latest()
            ->limit(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.transfer.receive-money')->layout('components.layouts.app', [
            'title' => 'Receive Money',
        ]);
    }
}