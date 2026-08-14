<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCommission extends Model
{
    protected $fillable = ['referral_id', 'source_transaction_id', 'amount', 'status'];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }
    public function sourceTransaction()
    {
        return $this->belongsTo(Transaction::class, 'source_transaction_id');
    }
}
