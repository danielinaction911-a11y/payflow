<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Gateway extends Model
{
    protected $fillable = [
        'name',
        'code',
        'logo',
        'type',
        'status',
        'min_amount',
        'max_amount',
        'fixed_fee',
        'percent_fee',
        'currency',
        'credentials',
        'payment_fields',
        'instructions'
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'fixed_fee' => 'decimal:2',
            'percent_fee' => 'decimal:2',
            'credentials' => 'array',
            'payment_fields' => 'array',
            'instructions' => 'array',
        ];
    }

    protected static function booted(): void
    {
        // clean up the logo file when a gateway is deleted
        static::deleting(function (Gateway $gateway) {
            if ($gateway->logo) {
                $path = public_path($gateway->logo);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
        });
    }

    public function calculateFee(float $amount): float
    {
        return round($this->fixed_fee + ($amount * $this->percent_fee / 100), 2);
    }

    public function calculateTotal(float $amount): float
    {
        return round($amount + $this->calculateFee($amount), 2);
    }
}
