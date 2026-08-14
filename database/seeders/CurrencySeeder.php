<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'US Dollar',
                'icon' => null,
                'symbol' => '$',
                'code' => 'USD',
                'network' => null,
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'fiat',
                'coingecko_id' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Bitcoin',
                'icon' => null,
                'symbol' => '₿',
                'code' => 'BTC',
                'network' => 'Bitcoin',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'bitcoin',
                'status' => 'active',
            ],
            [
                'name' => 'Ethereum',
                'icon' => null,
                'symbol' => 'Ξ',
                'code' => 'ETH',
                'network' => 'ERC20',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'ethereum',
                'status' => 'active',
            ],
            [
                'name' => 'Tether (TRC20)',
                'icon' => null,
                'symbol' => '₮',
                'code' => 'USDT',
                'network' => 'TRC20',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'tether',
                'status' => 'active',
            ],
            [
                'name' => 'Tether (ERC20)',
                'icon' => null,
                'symbol' => '₮',
                'code' => 'USDT',
                'network' => 'ERC20',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'tether',
                'status' => 'active',
            ],
            [
                'name' => 'BNB',
                'icon' => null,
                'symbol' => 'BNB',
                'code' => 'BNB',
                'network' => 'BEP20',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'binancecoin',
                'status' => 'active',
            ],
            [
                'name' => 'Solana',
                'icon' => null,
                'symbol' => 'SOL',
                'code' => 'SOL',
                'network' => 'Solana',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'solana',
                'status' => 'active',
            ],
            [
                'name' => 'Dogecoin',
                'icon' => null,
                'symbol' => 'Ð',
                'code' => 'DOGE',
                'network' => 'Dogecoin',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'dogecoin',
                'status' => 'active',
            ],
            [
                'name' => 'Ripple',
                'icon' => null,
                'symbol' => 'XRP',
                'code' => 'XRP',
                'network' => 'XRP Ledger',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'ripple',
                'status' => 'active',
            ],
            [
                'name' => 'Litecoin',
                'icon' => null,
                'symbol' => 'Ł',
                'code' => 'LTC',
                'network' => 'Litecoin',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'litecoin',
                'status' => 'active',
            ],
            [
                'name' => 'Tron',
                'icon' => null,
                'symbol' => 'TRX',
                'code' => 'TRX',
                'network' => 'Tron',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'tron',
                'status' => 'active',
            ],
            [
                'name' => 'USD Coin',
                'icon' => null,
                'symbol' => 'USDC',
                'code' => 'USDC',
                'network' => 'ERC20',
                'allow_deposit' => true,
                'allow_withdrawal' => true,
                'type' => 'crypto',
                'coingecko_id' => 'usd-coin',
                'status' => 'active',
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code'], 'network' => $currency['network']],
                $currency
            );
        }
    }
}