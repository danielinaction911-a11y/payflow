<?php

namespace App\Enums;

enum SignalStatus: string
{
    case Active = 'active';
    case HitTarget = 'hit_target';
    case HitStop = 'hit_stop';
    case Expired = 'expired';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Active => 'Active',
            self::HitTarget => 'Target Hit',
            self::HitStop => 'Stop Loss Hit',
            self::Expired => 'Expired',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Active => 'primary',
            self::HitTarget => 'success',
            self::HitStop => 'danger',
            self::Expired, self::Cancelled => 'secondary',
        };
    }
}