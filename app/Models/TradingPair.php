<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradingPair extends Model
{
protected $fillable = [
        'symbol', 'base_currency_id', 'quote_currency_id',
        'current_price', 'change_24h_percent',
    ];

    protected function casts(): array
    {
        return [
            'current_price' => 'decimal:8',
            'change_24h_percent' => 'decimal:2',
        ];
    } 
   public function baseCurrency()
   {
      return $this->belongsTo(Currency::class, 'base_currency_id');
   }
   
   public function quoteCurrency()
   {
      return $this->belongsTo(Currency::class, 'quote_currency_id');
   }
   public function trades()
   {
      return $this->hasMany(Trade::class);
   }
   public function watchedBy()
   {
      return $this->belongsToMany(User::class, 'watchlists');
   }
}
