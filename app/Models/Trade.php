<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderType;
use App\Enums\TradeDirection;
use App\Enums\TradeStatus;

class Trade extends Model
{
    protected $fillable = [
        'user_id',
        'trading_pair_id',
        'side',
        'order_type',
        'amount',
        'price',
        'total',
        'status',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:8',
            'price' => 'decimal:8',
            'total' => 'decimal:8',
            'expires_at' => 'datetime',
            'side' => TradeDirection::class,
            'order_type' => OrderType::class,
            'status' => TradeStatus::class,
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tradingPair()
    {
        return $this->belongsTo(TradingPair::class);
    }
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'reference');
    }
}
