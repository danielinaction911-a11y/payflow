<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $fillable = [
        'type',
        'status',
        'required_fields'
    ];

    protected function casts(): array
    {
        return [
            'required_fields' => 'array',
        ];
    }

    public function documents()
    {
        return $this->hasMany(KycDocument::class);
    }
}