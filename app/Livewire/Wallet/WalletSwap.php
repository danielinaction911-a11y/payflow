<?php

namespace App\Livewire\Wallet;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\User;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class WalletSwap extends Component
{
    public string $mode = 'deposit'; // deposit = USD -> wallet, withdraw = wallet -> USD
    public array $wallets = [];
    public ?int $walletId = null;
    public string $usdAmount = '';
    public float $price = 0;
    public ?string $error = null;
    public ?string $success = null;

    public bool $accessBlocked = false;
    public string $blockedMessage = '';

    public function mount(?int $walletId = null, string $mode = 'deposit'): void
    {
        $this->checkAccess();

        if ($this->accessBlocked) {
            return;
        }

        $this->mode = in_array($mode, ['deposit', 'withdraw']) ? $mode : 'deposit';

        $query = Auth::user()->wallets()->with('currency')
            ->whereHas('currency', function ($q) {
                $q->whereNotNull('coingecko_id')->where('status', true);

                if ($this->mode === 'deposit') {
                    $q->where('allow_deposit', true);
                } else {
                    $q->where('allow_withdrawal', true);
                }
            });

        $this->wallets = $query->get()->map(fn($w) => [
            'wallet_id' => $w->id,
            'currency_id' => $w->currency_id,
            'code' => $w->currency->code,
            'name' => $w->currency->name,
            'icon' => $w->currency->icon ? asset($w->currency->icon) : null,
            'coingecko_id' => $w->currency->coingecko_id,
            'available' => (float) $w->available,
        ])->toArray();

        if ($walletId && collect($this->wallets)->firstWhere('wallet_id', $walletId)) {
            $this->walletId = $walletId;
        } else {
            $this->walletId = $this->wallets[0]['wallet_id'] ?? null;
        }

        $this->refreshPrice();
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

    public function switchMode(string $mode): void
    {
        $this->mode = in_array($mode, ['deposit', 'withdraw']) ? $mode : 'deposit';
        $this->usdAmount = '';
        $this->error = null;
        $this->success = null;
        $this->mount($this->walletId, $this->mode);
    }

    public function updatedWalletId(): void
    {
        $this->refreshPrice();
    }

    public function refreshPrice(): void
    {
        $target = collect($this->wallets)->firstWhere('wallet_id', $this->walletId);

        if (! $target) {
            return;
        }

        try {
            $response = Http::timeout(5)->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => $target['coingecko_id'],
                'vs_currencies' => 'usd',
            ]);

            if ($response->ok()) {
                $data = $response->json()[$target['coingecko_id']] ?? null;
                $this->price = (float) ($data['usd'] ?? 0);
            }
        } catch (\Throwable $e) {
            Log::warning('Wallet swap price fetch failed: ' . $e->getMessage());
        }
    }

    public function getSelectedWalletProperty()
    {
        return collect($this->wallets)->firstWhere('wallet_id', $this->walletId);
    }

    public function getCryptoAmountProperty(): float
    {
        if ($this->price <= 0) {
            return 0;
        }

        $usd = (float) $this->usdAmount ?: 0;

        return $usd / $this->price;
    }

    public function setPercent(int $percent): void
    {
        if ($this->mode === 'deposit') {
            $this->usdAmount = bcmul((string) Auth::user()->balance, (string) ($percent / 100), 2);
        } else {
            $wallet = $this->selectedWallet;

            if (! $wallet || $this->price <= 0) {
                return;
            }

            $usdValue = $wallet['available'] * $this->price;
            $this->usdAmount = bcmul((string) $usdValue, (string) ($percent / 100), 2);
        }
    }

    public function submit(): void
    {
        $this->checkAccess();

        if ($this->accessBlocked) {
            $this->error = $this->blockedMessage;
            return;
        }

        $this->error = null;
        $this->success = null;

        $target = $this->selectedWallet;
        $usd = (float) $this->usdAmount;

        if (! $target) {
            $this->error = 'Please select a wallet.';
            return;
        }

        if ($usd <= 0) {
            $this->error = 'Enter a valid amount.';
            return;
        }

        if ($this->price <= 0) {
            $this->error = 'Unable to fetch live price right now. Please try again.';
            return;
        }

        $this->mode === 'deposit'
            ? $this->processDeposit($target, $usd)
            : $this->processWithdraw($target, $usd);
    }

    protected function processDeposit(array $target, float $usd): void
    {
        $user = Auth::user();

        if ($usd > $user->balance) {
            $this->error = 'Insufficient USD balance.';
            return;
        }

        try {
            DB::transaction(function () use ($target, $usd, $user) {
                $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();

                if ($usd > $lockedUser->balance) {
                    throw new \RuntimeException('Insufficient USD balance.');
                }

                $wallet = Wallet::where('id', $target['wallet_id'])->lockForUpdate()->first();
                $cryptoAmount = $usd / $this->price;
                $reference = 'SWP-' . strtoupper(Str::random(12));

                app(TransactionService::class)->create([
                    'user_id' => $user->id,
                    'wallet_id' => $wallet->id,
                    'reference' => $reference,
                    'amount' => $usd,
                    'currency' => 'USD',
                    'type' => TransactionType::Exchange,
                    'direction' => TransactionDirection::Debit,
                    'status' => TransactionStatus::Completed,
                    'description' => "Swap \${$usd} USD → " . number_format($cryptoAmount, 8) . " {$target['code']} @ \${$this->price}",
                    'metadata' => [
                        'mode' => 'deposit',
                        'to_wallet_id' => $wallet->id,
                        'to_currency' => $target['code'],
                        'rate' => $this->price,
                        'crypto_amount' => $cryptoAmount,
                    ],
                ]);

                $lockedUser->decrement('balance', $usd);
                $wallet->increment('available', $cryptoAmount);
            });

            $cryptoAmount = $usd / $this->price;
            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Deposit Successful',
                message: 'Successfully deposited ' . number_format($cryptoAmount, 8) . " {$target['code']} to your wallet."
            );
            $this->usdAmount = '';
            $this->mount($this->walletId, $this->mode);
        } catch (\Throwable $e) {
            $this->error = $e instanceof \RuntimeException ? $e->getMessage() : 'An error occurred while processing your deposit.';

            if (! $e instanceof \RuntimeException) {
                Log::error('Wallet deposit swap failed: ' . $e->getMessage());
            }
        }
    }

    protected function processWithdraw(array $target, float $usd): void
    {
        $user = Auth::user();
        $cryptoNeeded = $usd / $this->price;

        try {
            DB::transaction(function () use ($target, $usd, $cryptoNeeded, $user) {
                $wallet = Wallet::where('id', $target['wallet_id'])->lockForUpdate()->first();

                if (! $wallet || $cryptoNeeded > $wallet->available) {
                    throw new \RuntimeException('Insufficient balance in selected wallet.');
                }

                $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();
                $reference = 'SWP-' . strtoupper(Str::random(12));

                app(TransactionService::class)->create([
                    'user_id' => $user->id,
                    'wallet_id' => $wallet->id,
                    'reference' => $reference,
                    'amount' => $usd,
                    'currency' => 'USD',
                    'type' => TransactionType::Exchange,
                    'direction' => TransactionDirection::Credit,
                    'status' => TransactionStatus::Completed,
                    'description' => 'Swap ' . number_format($cryptoNeeded, 8) . " {$target['code']} → \${$usd} USD @ \${$this->price}",
                    'metadata' => [
                        'mode' => 'withdraw',
                        'from_wallet_id' => $wallet->id,
                        'from_currency' => $target['code'],
                        'rate' => $this->price,
                        'crypto_amount' => $cryptoNeeded,
                    ],
                ]);

                $wallet->decrement('available', $cryptoNeeded);
                $lockedUser->increment('balance', $usd);
            });

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Withdrawal Successful',
                message: 'Successfully moved $' . number_format($usd, 2) . ' to your account balance.'
            );
            $this->usdAmount = '';
            $this->mount($this->walletId, $this->mode);
        } catch (\Throwable $e) {
            $this->error = $e instanceof \RuntimeException ? $e->getMessage() : 'An error occurred while processing your withdrawal.';

            if (! $e instanceof \RuntimeException) {
                Log::error('Wallet withdraw swap failed: ' . $e->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.wallet.wallet-swap')->layout('components.layouts.app', [
            'title' => 'Wallet Swap',
        ]);
    }
}
