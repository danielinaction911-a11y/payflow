<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityAlert extends Model
{
    protected $fillable = ['user_id', 'type', 'description', 'resolved_at'];

    protected function casts(): array
    {
        return ['resolved_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}