<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    protected $fillable = ['user_id', 'ip_address', 'device', 'location', 'successful', 'logged_in_at'];

    protected function casts(): array
    {
        return [
            'successful' => 'boolean',
            'logged_in_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}