<?php

namespace App\Livewire\Wallet;

use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class WalletIndex extends Component
{
    public bool $showCreateModal = false;
    public ?int $selectedCurrencyId = null;

    public bool $accessBlocked = false;
    public string $blockedMessage = '';

    public function mount(): void
    {
        $this->checkAccess();
    }

    protected function checkAccess(): void
    {
        if (! (bool) setting('wallets_enabled', true)) {
            $this->accessBlocked = true;
            $this->blockedMessage = 'Wallets are currently disabled on the platform. Please try again later.';
            return;
        }

        $this->accessBlocked = false;
        $this->blockedMessage = '';
    }

    #[Computed]
    public function wallets()
    {
        return Wallet::with('currency')
            ->where('user_id', Auth::id())
            ->get()
            ->sortBy(fn ($wallet) => $wallet->currency->code ?? '');
    }

    #[Computed]
    public function availableCurrencies()
    {
        $existingCurrencyIds = $this->wallets->pluck('currency_id');

        return Currency::where('status', true)
            ->whereNotIn('id', $existingCurrencyIds)
            ->orderBy('name')
            ->get();
    }

    public function openCreateModal(): void
    {
        $this->selectedCurrencyId = null;
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function createWallet(): void
    {
        $this->checkAccess();

        if ($this->accessBlocked) {
            return;
        }

        $this->validate([
            'selectedCurrencyId' => 'required|exists:currencies,id',
        ]);

        $exists = Wallet::where('user_id', Auth::id())
            ->where('currency_id', $this->selectedCurrencyId)
            ->exists();

        if ($exists) {
            $this->addError('selectedCurrencyId', 'You already have a wallet for this currency.');
            return;
        }

        Wallet::create([
            'user_id' => Auth::id(),
            'currency_id' => $this->selectedCurrencyId,
            'balance' => 0,
            'is_primary' => $this->wallets->isEmpty(),
        ]);

        unset($this->wallets, $this->availableCurrencies);
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Wallet created',
            message: "Your wallet has been created."
        );

        $this->showCreateModal = false;
        $this->selectedCurrencyId = null;
    }

    public function render()
    {
        return view('livewire.wallet.wallet-index')->layout('components.layouts.app', [
            'title' => 'Wallets',
        ]);
    }
}