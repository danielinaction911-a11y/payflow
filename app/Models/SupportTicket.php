<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = ['user_id', 'subject', 'priority', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
    public function latestReply()
    {
        return $this->hasOne(TicketReply::class)->latestOfMany();
    }
}
