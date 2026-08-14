<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitLog extends Model
{
  protected $fillable = ['investment_id', 'user_id', 'amount', 'status', 'paid_at'];

  protected function casts(): array
  {
    return [
      'amount' => 'decimal:2',
      'paid_at' => 'datetime',
    ];
  }

  public function investment()
  {
    return $this->belongsTo(Investment::class);
  }

  // convenience: reach the user through the investment
  public function user()
  {
    return $this->hasOneThrough(User::class, Investment::class, 'id', 'id', 'investment_id', 'user_id');
  }
}
