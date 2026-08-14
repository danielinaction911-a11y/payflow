<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\KycStatus;

class KycDocument extends Model
{
    protected $fillable = [
        'user_id',
        'kyc_id', 
        'required_fields',
        'status', 
        'rejection_reason'
    ];

    protected function casts(): array
    {
        return [
            'required_fields' => 'array',  
            'status' =>  KycStatus::class
        ];
    }

    public function kyc()
    {
        return $this->belongsTo(Kyc::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
