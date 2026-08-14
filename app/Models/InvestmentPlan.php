<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\RoiType;

class InvestmentPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'min_amount',
        'max_amount',
        'roi_percentage',
        'duration_days',
        'roi_type',
        'features',
        'is_popular',
        'capital_back',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'roi_percentage' => 'decimal:2',
            'features' => 'array',
            'is_popular' => 'boolean',
            'capital_back' => 'boolean',
            'roi_type' => RoiType::class,
        ];
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function calculateExpectedReturn(float $amount): float
    {
        return match ($this->roi_type) {
            RoiType::Daily => round($amount + ($amount * $this->roi_percentage / 100 * $this->duration_days), 2),
            RoiType::Weekly => round($amount + ($amount * $this->roi_percentage / 100 * ceil($this->duration_days / 7)), 2),
            RoiType::Yearly => round($amount + ($amount * $this->roi_percentage / 100 * ceil($this->duration_days / 365)), 2),
            RoiType::Monthly, RoiType::OneTime => round($amount + ($amount * $this->roi_percentage / 100), 2),
        };
    }
}
