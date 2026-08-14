<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\TradingPair;
use Illuminate\Database\Seeder;

class TradingPairSeeder extends Seeder
{
    public function run(): void
    {
        $usdt = Currency::where('code', 'USDT')->first();

        $pairs = [
            ['base' => 'BTC', 'price' => 66332.80, 'change' => 2.48],
            ['base' => 'ETH', 'price' => 3544.11, 'change' => 1.72],
            ['base' => 'SOL', 'price' => 146.22, 'change' => 4.98],
            ['base' => 'DOGE', 'price' => 0.1421, 'change' => -1.15],
            ['base' => 'XRP', 'price' => 0.6231, 'change' => 0.84],
        ];

        foreach ($pairs as $pair) {
            $base = Currency::where('code', $pair['base'])->first();

            if (! $base || ! $usdt) {
                continue;
            }

            TradingPair::updateOrCreate(
                ['symbol' => $pair['base'] . 'USDT'],
                [
                    'base_currency_id' => $base->id,
                    'quote_currency_id' => $usdt->id,
                    'current_price' => $pair['price'],
                    'change_24h_percent' => $pair['change'],
                ]
            );
        }
    }
}