<?php

namespace App\Enums;

enum TransaferStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed'; 

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Completed => 'success',
            self::Failed => 'danger', 
        };
    }
}
