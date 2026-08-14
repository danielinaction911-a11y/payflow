<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Banned = 'banned';

    public function label(): string
    {
        return match($this) {
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Banned => 'Banned',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Active => 'success',
            self::Suspended => 'warning',
            self::Banned => 'danger',
        };
    }
}