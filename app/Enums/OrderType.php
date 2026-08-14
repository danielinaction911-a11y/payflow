<?php

namespace App\Enums;

enum OrderType: string
{
    case Market = 'market';
    case Limit = 'limit';
    case StopLoss = 'stop_loss';
    case TakeProfit = 'take_profit';

    public function label(): string
    {
        return match ($this) {
            self::Market => 'Market',
            self::Limit => 'Limit',
            self::StopLoss => 'Stop Loss',
            self::TakeProfit => 'Take Profit',
        };
    }
}