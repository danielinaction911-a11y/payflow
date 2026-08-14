<?php

namespace App\Enums;

enum TradeStatus: string
{
    case Open = 'open';
    case Filled = 'filled';
    case HitTarget = 'hit_target';
    case HitStop = 'hit_stop';
    case Expired = 'expired';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Filled => 'Filled',
            self::HitTarget => 'Hit Target',
            self::HitStop => 'Hit Stop',
            self::Expired => 'Expired',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Open => 'warning',
            self::Filled, self::HitTarget => 'success',
            self::HitStop => 'danger',
            self::Expired => 'gray',
            self::Cancelled => 'danger',
        };
    }
}