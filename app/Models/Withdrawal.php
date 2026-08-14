<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\WithdrawalStatus;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'fee',
        'currency',
        'method',
        'transaction_id',
        'status',
        'rejection_reason',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee' => 'decimal:2',
            'metadata' => 'array',
            'status' => WithdrawalStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function netAmount(): string
    {
        return bcsub($this->amount, $this->fee, 18);
    }
}
