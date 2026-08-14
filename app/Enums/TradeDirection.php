<?php

namespace App\Enums;

enum TradeDirection: string
{
    case Buy = 'buy';
    case Sell = 'sell';

    public function label(): string
    {
        return match($this) {
            self::Buy => 'Buy / Long',
            self::Sell => 'Sell / Short',
        };
    }
}