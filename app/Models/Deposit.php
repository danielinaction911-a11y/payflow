<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Enums\TransactionStatus;
use App\Enums\DepositStatus;

class Deposit extends Model
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
            'status' => DepositStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'reference', 'transaction_id');
    }

    public function approve(): void
    {
        DB::transaction(function () {

            $this->user()->increment(
                'balance',
                $this->amount
            );

            $this->update([
                'status' => DepositStatus::Confirmed,
            ]);

            if ($this->transaction) {
                $this->transaction->update([
                    'status' => TransactionStatus::Completed,
                ]);
            }

            Notification::create([
                'trid' => 'deposit_approved_' . $this->id,
                'user_id' => $this->user_id,
                'subject' => 'Deposit Approved',
                'message' => 'Congratulations! Your deposit has been approved and your balance has been updated.',
            ]);
        });
    }

    public function reject(?string $reason = null): void
    {
        DB::transaction(function () use ($reason) {

            $this->update([
                'status' => DepositStatus::Rejected,
            ]);

            if ($this->transaction) {
                $this->transaction->update([
                    'status' => TransactionStatus::Failed,
                    'failed_reason' => $reason,
                ]);
            }

            Notification::create([
                'trid' => 'deposit_rejected_' . $this->id,
                'user_id' => $this->user_id,
                'subject' => 'Deposit Rejected',
                'message' => 'Your deposit has been rejected.'
                    . ($reason ? ' Reason: ' . $reason : ''),
            ]);
        });
    }
}
