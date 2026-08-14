<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawGateway extends Model
{
    protected $fillable = [
        'name',
        'code',
        'logo',
        'details',
        'min_amount',
        'max_amount',
        'fixed_fee',
        'percent_fee',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'fixed_fee' => 'decimal:2',
            'percent_fee' => 'decimal:2',
            'details' => 'array',
        ];
    }

    public function calculateFee(float $amount): float
    {
        return round($this->fixed_fee + ($amount * $this->percent_fee / 100), 2);
    }

    public function calculateTotal(float $amount): float
    {
        return round($amount + $this->calculateFee($amount), 2);
    }

    public function withdraws()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
