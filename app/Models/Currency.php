<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name', 'icon', 'symbol', 'code', 'network',
        'allow_deposit', 'allow_withdrawal', 'type', 'coingecko_id', 'status',
    ];

    protected function casts(): array
    {
        return [
            'allow_deposit' => 'boolean',
            'allow_withdrawal' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    protected static function booted()
    {
        static::deleting(function ($currency) {

            if ($currency->icon) {
                $path = public_path($currency->icon);

                if (is_file($path)) {
                    @unlink($path);
                }
            }
        });
    } 
    public function deposits()
    {
        // deposits store the currency code in the `currency` column
        return $this->hasMany(Deposit::class, 'currency', 'code');
    }
    public function withdrawals()
    {
        // withdrawals store the currency code in the `currency` column
        return $this->hasMany(Withdrawal::class, 'currency', 'code');
    }
    // Note: transfers table does not reference currencies by id in this schema.
    // If transfers are later updated to reference currencies, add a relation here.

    public function baseTradingPairs()
    {
        return $this->hasMany(TradingPair::class, 'base_currency_id');
    }
    public function quoteTradingPairs()
    {
        return $this->hasMany(TradingPair::class, 'quote_currency_id');
    }
}
