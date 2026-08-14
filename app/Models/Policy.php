<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Policy extends Model
{
   protected $fillable = [
        'title', 'slug', 'type', 'content', 'version',
        'is_active', 'sort_order', 'effective_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'effective_date' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($policy) {
            if (empty($policy->slug)) {
                $policy->slug = Str::slug($policy->title);
            }
        });
    }
}
