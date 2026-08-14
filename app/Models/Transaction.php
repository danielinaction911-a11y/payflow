<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Trade;
use App\Models\Transfer;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'type',
        'reference',
        'direction',
        'amount',
        'fee',
        'currency',
        'status',
        'description',
        'failed_reason',
        'metadata',
    ]; 

    protected function casts(): array
    {
        return [ 
            'metadata' => 'array',
            'type' => TransactionType::class,
            'direction' => TransactionDirection::class,
            'status' => TransactionStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function hasRelatedReference(): bool
    {
        return $this->reference !== null && (
            Deposit::where('transaction_id', $this->reference)->exists()
            || Withdrawal::where('transaction_id', $this->reference)->exists()
            || Trade::where('id', $this->reference)->exists()
            || Transfer::where('reference', $this->reference)->exists()
        );
    }

    protected static function booted(): void
    {
        static::deleting(function (Transaction $transaction) {
            return ! $transaction->hasRelatedReference();
        });
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', TransactionStatus::Completed);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', TransactionStatus::Failed);
    }

    public function scopePending($query)
    {
        return $query->where('status', TransactionStatus::Pending);
    }
}
