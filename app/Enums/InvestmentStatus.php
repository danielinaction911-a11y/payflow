<?php

namespace App\Enums;

enum InvestmentStatus: string
{
    case Active = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function badgeColor(): string
    {
        return match($this) {
            self::Active => 'primary',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}