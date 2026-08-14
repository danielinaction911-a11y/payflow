<?php

namespace App\Enums;

enum KycStatus: string
{
    case Unverified = 'unverified';
    case Rejected = 'rejected';
    case Pending = 'pending';
    case Verified = 'approved';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Unverified => 'secondary',
            self::Pending => 'warning',
            self::Verified => 'success',
            self::Rejected => 'danger',
        };
    }

    public function statusIcon()
    {
        return match ($this) {
            self::Pending => 'fas fa-clock',
            self::Verified => 'fas fa-check',
            self::Unverified => 'fas fa-times',
            self::Rejected => 'fas fa-times',
        };
    }
}
