<?php

namespace Database\Seeders;

use App\Models\WithdrawGateway;
use Illuminate\Database\Seeder;

class WithdrawGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Bank Transfer',
                'code' => 'bank_transfer',
                'logo' => null,
                'details' => [
                    ['name' => 'bank_name', 'label' => 'Bank Name', 'type' => 'text', 'required' => true],
                    ['name' => 'account_name', 'label' => 'Account Holder Name', 'type' => 'text', 'required' => true],
                    ['name' => 'account_number', 'label' => 'Account Number', 'type' => 'text', 'required' => true],
                    ['name' => 'routing_number', 'label' => 'Routing / SWIFT Code', 'type' => 'text', 'required' => false],
                ],
                'min_amount' => 20,
                'max_amount' => 10000,
                'fixed_fee' => 1.5,
                'percent_fee' => 1.0,
                'currency' => 'USD',
                'status' => true,
            ],
            [
                'name' => 'USDT (TRC20)',
                'code' => 'usdt_trc20',
                'logo' => null,
                'details' => [
                    ['name' => 'wallet_address', 'label' => 'USDT Wallet Address (TRC20)', 'type' => 'text', 'required' => true],
                ],
                'min_amount' => 10,
                'max_amount' => 50000,
                'fixed_fee' => 1,
                'percent_fee' => 0.5,
                'currency' => 'USDT',
                'status' => true,
            ],
            [
                'name' => 'USDT (ERC20)',
                'code' => 'usdt_erc20',
                'logo' => null,
                'details' => [
                    ['name' => 'wallet_address', 'label' => 'USDT Wallet Address (ERC20)', 'type' => 'text', 'required' => true],
                ],
                'min_amount' => 20,
                'max_amount' => 50000,
                'fixed_fee' => 3,
                'percent_fee' => 0.5,
                'currency' => 'USDT',
                'status' => true,
            ],
            [
                'name' => 'Bitcoin',
                'code' => 'bitcoin',
                'logo' => null,
                'details' => [
                    ['name' => 'wallet_address', 'label' => 'BTC Wallet Address', 'type' => 'text', 'required' => true],
                ],
                'min_amount' => 15,
                'max_amount' => 100000,
                'fixed_fee' => 2,
                'percent_fee' => 0.8,
                'currency' => 'BTC',
                'status' => true,
            ],
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'logo' => null,
                'details' => [
                    ['name' => 'paypal_email', 'label' => 'PayPal Email Address', 'type' => 'text', 'required' => true],
                ],
                'min_amount' => 10,
                'max_amount' => 5000,
                'fixed_fee' => 0.5,
                'percent_fee' => 2.5,
                'currency' => 'USD',
                'status' => true,
            ],
            [
                'name' => 'Skrill',
                'code' => 'skrill',
                'logo' => null,
                'details' => [
                    ['name' => 'skrill_email', 'label' => 'Skrill Email Address', 'type' => 'text', 'required' => true],
                ],
                'min_amount' => 10,
                'max_amount' => 5000,
                'fixed_fee' => 0.5,
                'percent_fee' => 2.0,
                'currency' => 'USD',
                'status' => true,
            ],
        ];

        foreach ($gateways as $gateway) {
            WithdrawGateway::updateOrCreate(['code' => $gateway['code']], $gateway);
        }
    }
}