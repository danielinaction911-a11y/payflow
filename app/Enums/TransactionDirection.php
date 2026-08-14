<?php

namespace App\Enums;

enum TransactionDirection: string
{
    case Debit = 'debit';
    case Credit = 'credit';

    public function badgeColor(): string
    {
        return match($this) {
            self::Debit => 'success',
            self::Credit => 'danger',
        };
    }
}
