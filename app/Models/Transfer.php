<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
   use HasFactory;

   protected $fillable = [
      'sender_id',
      'recipient_id',
      'amount',
      'description',
      'reference',
      'status',
   ];

   protected $casts = [
      'amount' => 'decimal:2',
      'status' => \App\Enums\TransaferStatus::class,
   ];

   public function sender()
   {
      return $this->belongsTo(User::class, 'sender_id');
   }
   public function recipient()
   {
      return $this->belongsTo(User::class, 'recipient_id');
   }
   public function currency()
   {
      return $this->belongsTo(Currency::class);
   }
   public function transaction()
   {
      return $this->morphOne(Transaction::class, 'reference');
   }
}
