<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneyRequest extends Model
{
  protected $fillable = ['requester_id', 'recipient_id', 'amount', 'message', 'expires_at', 'status'];
  protected function casts(): array
  {
    return [
      'amount' => 'decimal:2',
      'expires_at' => 'datetime',
    ];
  }
  public function requester()
  {
    return $this->belongsTo(User::class, 'requester_id');
  }
  public function recipient()
  {
    return $this->belongsTo(User::class, 'recipient_id');
  }
}
