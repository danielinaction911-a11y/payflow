<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{ 
    protected $fillable = [
        'user_id',
        'currency_id',
        'is_primary',
        'available',
        'locked',
    ];
    protected $casts = [
        'available' => 'decimal:18',
        'locked' => 'decimal:18',
    ];
    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function availableBalance(): string
    {
        return bcsub((string) ($this->available ?? '0'), (string) ($this->locked ?? '0'), 18);
    }

    public function hasSufficientBalance(string $amount): bool
    {
        return bccomp($this->availableBalance(), $amount, 18) >= 0;
    }

    public function totalBalance(): string
    {
        return bcadd((string) ($this->available ?? '0'), (string) ($this->locked ?? '0'), 18);
    }

    public function hasRelatedRecords(): bool
    {
        // Consider a wallet related if it has transactions or any non-zero balance
        $hasTx = $this->transactions()->exists();
        $total = $this->totalBalance();

        return $hasTx || bccomp($total, '0', 18) > 0 || (bool) $this->is_primary;
    }

    protected static function booted(): void
    {
        static::deleting(function (Wallet $wallet) {
            return ! $wallet->hasRelatedRecords();
        });
    }
}
