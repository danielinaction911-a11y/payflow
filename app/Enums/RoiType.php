<?php

namespace App\Enums;

enum RoiType: string
{
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Yearly = 'yearly';
    case OneTime = 'one_time';

    public function label(): string
    {
        return match($this) {
            self::Daily => 'Daily',
            self::Weekly => 'Weekly',
            self::Monthly => 'Monthly',
            self::Yearly => 'Yearly',
            self::OneTime => 'One-time',
        };
    }

    /** Useful when the scheduler decides who's due for a payout */
    public function intervalDays(): int
    {
        return match($this) {
            self::Daily => 1,
            self::Weekly => 7,
            self::Monthly => 30,
            self::Yearly => 365,
            self::OneTime => 0,
        };
    }
}