<?php

namespace App\Livewire\Trade;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Trade;
use App\Models\TradingPair;
use App\Models\User;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Enums\{OrderType,TradeDirection,TradeStatus};

#[Layout('components.layouts.app')]
class TradeForm extends Component
{
    public ?int $pairId = null;
    public string $side = 'buy';
    public string $amount = '';
    public ?string $error = null;
    public ?string $success = null;

    public bool $accessBlocked = false;
    public string $blockedMessage = '';

    #[Computed]
    public function pairs()
    {
        return TradingPair::with(['baseCurrency', 'quoteCurrency'])->orderBy('symbol')->get();
    }

    #[Computed]
    public function selectedPair()
    {
        return $this->pairId
            ? TradingPair::with(['baseCurrency', 'quoteCurrency'])->find($this->pairId)
            : $this->pairs->first();
    }

    /**
     * The crypto (base currency) side of the pair. This is the ONLY side backed
     * by the `wallets` table — quote currency is assumed to always be USD, which
     * lives on users.balance (see quoteAvailable() below), matching how Deposit/
     * Withdraw/Transfer already work elsewhere in the app.
     */
    #[Computed]
    public function baseWallet()
    {
        return Wallet::firstOrCreate(
            ['user_id' => Auth::id(), 'currency_id' => $this->selectedPair->base_currency_id],
            ['available' => 0]
        );
    }

    #[Computed]
    public function price(): float
    {
        return (float) ($this->selectedPair->current_price ?? 0);
    }

    #[Computed]
    public function feePercent(): float
    {
        return (float) setting('trading_fee_percentage', 0.5);
    }

    #[Computed]
    public function subtotal(): float
    {
        if (! is_numeric($this->amount)) {
            return 0;
        }

        return (float) $this->amount * $this->price;
    }

    #[Computed]
    public function fee(): float
    {
        return round($this->subtotal * $this->feePercent / 100, 2);
    }

    #[Computed]
    public function total(): float
    {
        return round($this->subtotal + $this->fee, 2);
    }

    /**
     * FIX: previously read from a `wallets` row scoped to the quote currency,
     * which is never funded by Deposit/Withdraw/Transfer (those all write to
     * users.balance directly) — so it always showed 0 regardless of the user's
     * real balance. Buying now reads the real USD balance.
     */
    #[Computed]
    public function availableBalance(): float
    {
        return $this->side === 'buy'
            ? (float) auth()->user()->balance
            : (float) $this->baseWallet->available;
    }

    #[Computed]
    public function needsFunding(): bool
    {
        if (! $this->selectedPair) {
            return false;
        }

        return $this->side === 'buy'
            ? (float) auth()->user()->balance <= 0
            : (float) $this->baseWallet->available <= 0;
    }

    #[Computed]
    public function recentTrades()
    {
        return auth()->user()->trades()
            ->with('tradingPair.baseCurrency')
            ->latest()
            ->limit(20)
            ->get();
    }

    public function mount(): void
    {
        $this->checkAccess();
        $this->pairId = $this->pairs->first()?->id;
    }

    protected function checkAccess(): void
    {
        $user = Auth::user();

        if (! (bool) setting('trading_enabled', true)) {
            $this->accessBlocked = true;
            $this->blockedMessage = 'Trading is currently disabled on the platform. Please try again later.';
            return;
        }

        if ($user->trading_status === 'disabled') {
            $this->accessBlocked = true;
            $this->blockedMessage = $user->trading_message ?: 'Trading is currently disabled for your account. Please contact support.';
            return;
        }

        $this->accessBlocked = false;
        $this->blockedMessage = '';
    }

    public function selectPair(int $id): void
    {
        $this->pairId = $id;
        $this->amount = '';
        $this->error = null;
        $this->success = null;

        $this->dispatch('pair-changed', symbol: $this->selectedPair->symbol);
    }

    public function setSide(string $side): void
    {
        $this->side = in_array($side, ['buy', 'sell']) ? $side : 'buy';
        $this->amount = '';
        $this->error = null;
    }

    public function setPercent(int $percent): void
    {
        $available = $this->availableBalance;

        if ($this->side === 'buy') {
            // FIX: previously treated the whole available balance as spendable
            // on the "subtotal" (pre-fee) amount, so clicking Max (100%) built
            // a total (subtotal + fee) that exceeded the real balance by
            // exactly the fee — placeOrder() would then reject it as
            // "insufficient balance". Divide out the fee first so the final
            // total actually fits inside what's available.
            $feeMultiplier = 1 + ($this->feePercent / 100);
            $spendable = ($available * ($percent / 100)) / $feeMultiplier;
            $this->amount = $this->price > 0 ? number_format($spendable / $this->price, 8, '.', '') : '0';
        } else {
            $this->amount = number_format($available * ($percent / 100), 8, '.', '');
        }
    }

    public function refreshPrice(): void
    {
        // wire:poll target — computed properties re-read the DB on every render.
    }

    public function placeOrder(): void
    {
        $this->checkAccess();

        if ($this->accessBlocked) {
            $this->error = $this->blockedMessage;
            return;
        }

        $this->error = null;
        $this->success = null;

        $pair = $this->selectedPair;

        if (! $pair || $this->price <= 0) {
            $this->error = 'This trading pair is currently unavailable.';
            return;
        }

        if (! is_numeric($this->amount) || (float) $this->amount <= 0) {
            $this->error = 'Enter a valid amount.';
            return;
        }

        $amount = (float) $this->amount;
        $side = $this->side;
        $feePercent = $this->feePercent;

        try {
            DB::transaction(function () use ($pair, $amount, $side, $feePercent) {
                // Lock the USD balance row (on users) and the crypto wallet row
                // for the duration of the transaction — same race-condition
                // protection pattern used in Withdraw/Transfer.
                $user = User::where('id', Auth::id())->lockForUpdate()->first();

                $baseWallet = Wallet::where('user_id', Auth::id())
                    ->where('currency_id', $pair->base_currency_id)
                    ->lockForUpdate()
                    ->first() ?? Wallet::create(['user_id' => Auth::id(), 'currency_id' => $pair->base_currency_id, 'available' => 0]);

                $price = (float) TradingPair::where('id', $pair->id)->value('current_price');
                $subtotal = $amount * $price;
                $fee = round($subtotal * $feePercent / 100, 2);
                $total = round($subtotal + $fee, 2);

                $baseCode = $pair->baseCurrency->code;
                $quoteCode = $pair->quoteCurrency->code;
                $reference = 'TRD-' . strtoupper(Str::random(10));

                if ($side === TradeDirection::Buy) {
                    if ((float) $user->balance < $total) {
                        throw new \RuntimeException("Insufficient {$quoteCode} balance.");
                    }

                    $user->decrement('balance', $total);
                    $baseWallet->increment('available', $amount);
                } else {
                    if ((float) $baseWallet->available < $amount) {
                        throw new \RuntimeException("Insufficient {$baseCode} balance.");
                    }

                    $baseWallet->decrement('available', $amount);
                    $user->increment('balance', $subtotal - $fee);
                }

                $trade = Trade::create([
                    'user_id' => Auth::id(),
                    'trading_pair_id' => $pair->id,
                    'side' => $side,
                    'order_type' => OrderType::Market,
                    'amount' => $amount,
                    'price' => $price,
                    'total' => $total,
                    'status' => TradeStatus::Filled,
                ]);

                // Log BOTH legs of the trade, matching the Transfer pattern —
                // otherwise the crypto wallet's transaction history would show
                // nothing at all for trades, only the USD side would.
                if ($side === TradeDirection::Buy) {
                    // Leg 1: USD debited (wallet_id null — USD isn't wallet-backed)
                    app(TransactionService::class)->create([
                        'user_id' => Auth::id(),
                        'wallet_id' => null,
                        'reference' => $reference . '-1',
                        'amount' => $total,
                        'fee' => $fee,
                        'currency' => $quoteCode,
                        'type' => TransactionType::Trade,
                        'direction' => TransactionDirection::Debit,
                        'description' => "Bought {$amount} {$baseCode} @ " . money_format($price) . "/{$baseCode}",
                        'status' => TransactionStatus::Completed,
                        'metadata' => ['trade_id' => $trade->id, 'pair' => $pair->symbol, 'side' => TradeDirection::Buy],
                    ]);

                    // Leg 2: crypto credited
                    app(TransactionService::class)->create([
                        'user_id' => Auth::id(),
                        'wallet_id' => $baseWallet->id,
                        'reference' => $reference . '-2',
                        'amount' => $amount,
                        'fee' => 0,
                        'currency' => $baseCode,
                        'type' => TransactionType::Trade,
                        'direction' => TransactionDirection::Credit,
                        'description' => "Received {$amount} {$baseCode} from buying {$pair->symbol}",
                        'status' => TransactionStatus::Completed,
                        'metadata' => ['trade_id' => $trade->id, 'pair' => $pair->symbol, 'side' => TradeDirection::Buy],
                    ]);
                } else {
                    // Leg 1: crypto debited
                    app(TransactionService::class)->create([
                        'user_id' => Auth::id(),
                        'wallet_id' => $baseWallet->id,
                        'reference' => $reference . '-1',
                        'amount' => $amount,
                        'fee' => 0,
                        'currency' => $baseCode,
                        'type' => TransactionType::Trade,
                        'direction' => TransactionDirection::Debit,
                        'description' => "Sold {$amount} {$baseCode} @ " . money_format($price) . "/{$baseCode}",
                        'status' => TransactionStatus::Completed,
                        'metadata' => ['trade_id' => $trade->id, 'pair' => $pair->symbol, 'side' => TradeDirection::Sell],
                    ]);

                    // Leg 2: USD credited (wallet_id null — USD isn't wallet-backed)
                    app(TransactionService::class)->create([
                        'user_id' => Auth::id(),
                        'wallet_id' => null,
                        'reference' => $reference . '-2',
                        'amount' => $subtotal - $fee,
                        'fee' => $fee,
                        'currency' => $quoteCode,
                        'type' => TransactionType::Trade,
                        'direction' => TransactionDirection::Credit,
                        'description' => "Received " . money_format($subtotal - $fee) . " from selling {$amount} {$baseCode}",
                        'status' => TransactionStatus::Completed,
                        'metadata' => ['trade_id' => $trade->id, 'pair' => $pair->symbol, 'side' => TradeDirection::Sell],
                    ]);
                }
            });

            // The DB transaction above modified a freshly-locked User instance,
            // not the cached Auth::user() singleton — refresh it so computed
            // properties (availableBalance, etc.) reflect the new balance
            // immediately instead of on the next full page load.
            auth()->user()->refresh();

            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Trade Completed',
                message: ucfirst($side) . ' order for ' . number_format($amount, 8) . ' ' . $pair->baseCurrency->code . ' filled successfully.'
            );
            $this->amount = '';

            unset($this->baseWallet, $this->availableBalance, $this->needsFunding, $this->recentTrades);
        } catch (\Throwable $e) {
            $this->error = $e instanceof \RuntimeException ? $e->getMessage() : 'Trade could not be completed. Please try again.';

            if (! $e instanceof \RuntimeException) {
                report($e);
            }
        }
    }

    public function render()
    {
        return view('livewire.trade.trade-form')->layout('components.layouts.app', [
            'title' => 'Trade',
        ]);
    }
}
