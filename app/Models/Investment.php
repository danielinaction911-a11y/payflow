<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\InvestmentStatus;

class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'investment_plan_id',
        'amount_invested',
        'roi_percentage',
        'expected_total_return',
        'total_paid_out',
        'status',
        'starts_at',
        'ends_at',
        'last_profit_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_invested' => 'decimal:2',
            'roi_percentage' => 'decimal:2',
            'expected_total_return' => 'decimal:2',
            'total_paid_out' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'last_profit_at' => 'datetime',
            'status' => InvestmentStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'investment_plan_id');
    }

    public function profitLogs()
    {
        return $this->hasMany(ProfitLog::class);
    }

    public function getProgressPercentAttribute()
    {
        if (!$this->starts_at || !$this->ends_at) {
            return 0;
        }

        $start = strtotime($this->starts_at);
        $end = strtotime($this->ends_at);
        $now = now()->timestamp;

        if ($now <= $start) {
            return 0;
        }

        if ($now >= $end) {
            return 100;
        }

        $progress = (($now - $start) / ($end - $start)) * 100;

        return round($progress, 0);
    }

    public function getDaysLeftAttribute()
    {
        return max(
            0,
            (int) now()->diffInDays($this->ends_at, false)
        );
    }

    public function progressPercent(): float
    {
        if (!$this->starts_at || !$this->ends_at) return 0;

        $total = $this->starts_at->diffInSeconds($this->ends_at);
        $elapsed = $this->starts_at->diffInSeconds(now());

        if ($total <= 0) return 100;

        return min(100, max(0, ($elapsed / $total) * 100));
    }

    public function daysLeft(): int
    {
        if (!$this->ends_at) return 0;
        return max(0, now()->diffInDays($this->ends_at, false) * -1);
    }

    public function dailyEarnings(): string
    {
        $totalDays = $this->starts_at->diffInDays($this->ends_at);
        if ($totalDays <= 0) return '0.00';
        return bcdiv($this->expected_total_return, $totalDays, 8);
    }

    public function remainingReturn(): string
    {
        return bcsub($this->expected_total_return, $this->total_paid_out, 8);
    }
}
