<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GatewaySeeder extends Seeder
{
    /**
     * Seeds the `gateways` table with the exact payment gateway
     * configurations provided (Paystack, Bank Transfer, Crypto, Gift
     * Card, CashApp, PayPal Manual, Zelle, Venmo).
     *
     * Uses updateOrInsert() keyed on `code` so the seeder is safely
     * re-runnable without throwing duplicate-key errors.
     */
    public function run(): void
    {
        $gateways = [
            [
                'id'             => 1,
                'name'           => 'Paystack',
                'code'           => 'paystack',
                'logo'           => 'images/gateway/paystack.png',
                'type'           => 'auto',
                'status'         => 1,
                'min_amount'     => 100.00,
                'max_amount'     => 1000000.00,
                'fixed_fee'      => 0.00,
                'percent_fee'    => 1.50,
                'currency'       => 'NGN',
                'credentials'    => [
                    'public_key' => 'pk_test_xxx',
                    'secret_key' => 'sk_test_xxx',
                ],
                'payment_fields' => null,
                'instructions'   => [
                    'title' => 'Pay with Paystack',
                    'steps' => [
                        'Click deposit',
                        'Complete payment on Paystack',
                        'Wallet will be credited instantly',
                    ],
                ],
                'created_at'     => '2026-04-13 12:33:50',
                'updated_at'     => '2026-04-13 12:33:50',
            ],
            [
                'id'             => 2,
                'name'           => 'Bank Transfer',
                'code'           => 'bank',
                'logo'           => 'images/gateway/online-payment.png',
                'type'           => 'manual',
                'status'         => 1,
                'min_amount'     => 500.00,
                'max_amount'     => 5000000.00,
                'fixed_fee'      => 0.00,
                'percent_fee'    => 0.00,
                'currency'       => 'NGN',
                'credentials'    => null,
                'payment_fields' => [
                    ['name' => 'account_name',   'label' => 'Sender Name',              'type' => 'text', 'required' => true],
                    ['name' => 'transaction_id', 'label' => 'Transaction Reference',    'type' => 'text', 'required' => true],
                    ['name' => 'proof',          'label' => 'Upload Payment Proof',     'type' => 'file', 'required' => true],
                ],
                'instructions'   => [
                    'title'   => 'Bank Transfer Instructions',
                    'steps'   => [
                        'Transfer to the account below',
                        'Upload proof of payment',
                        'Wait for admin approval',
                    ],
                    'details' => [
                        'bank_name'      => 'Access Bank',
                        'account_name'   => 'Fan Platform Ltd',
                        'account_number' => '1234567890',
                    ],
                ],
                'created_at'     => '2026-04-13 12:34:02',
                'updated_at'     => '2026-04-13 12:34:02',
            ],
            [
                'id'             => 3,
                'name'           => 'Crypto',
                'code'           => 'crypto',
                'logo'           => 'images/gateway/bitcoin.png',
                'type'           => 'manual',
                'status'         => 1,
                'min_amount'     => 50.00,
                'max_amount'     => 100000.00,
                'fixed_fee'      => 0.00,
                'percent_fee'    => 0.00,
                'currency'       => 'USD',
                'credentials'    => null,
                'payment_fields' => [
                    ['name' => 'tx_hash', 'label' => 'Transaction Hash', 'type' => 'text', 'required' => true],
                ],
                'instructions'   => [
                    'title'   => 'Crypto Payment',
                    'steps'   => [
                        'Send crypto to wallet below',
                        'Paste your transaction hash',
                        'Wait for confirmation',
                    ],
                    'details' => [
                        'wallet_address' => '0xABC123XYZ',
                        'network'        => 'USDT (TRC20)',
                    ],
                ],
                'created_at'     => '2026-04-13 12:34:16',
                'updated_at'     => '2026-04-13 12:34:16',
            ],
            [
                'id'             => 4,
                'name'           => 'Gift Card',
                'code'           => 'giftcard',
                'logo'           => 'images/gateway/gift-card.png',
                'type'           => 'manual',
                'status'         => 1,
                'min_amount'     => 50.00,
                'max_amount'     => 5000.00,
                'fixed_fee'      => 0.00,
                'percent_fee'    => 0.00,
                'currency'       => 'USD',
                'credentials'    => null,
                'payment_fields' => [
                    ['name' => 'card_type',   'label' => 'Card Type (Amazon, iTunes, Steam)', 'type' => 'text',     'required' => true],
                    ['name' => 'card_amount', 'label' => 'Card Amount',                        'type' => 'text',     'required' => true],
                    ['name' => 'card_code',   'label' => 'Card Code',                          'type' => 'textarea', 'required' => true],
                    ['name' => 'receipt',     'label' => 'Upload Receipt / Image',             'type' => 'file',     'required' => true],
                ],
                'instructions'   => [
                    'title'   => 'Gift Card Payment Instructions',
                    'steps'   => [
                        'Purchase a valid gift card',
                        'Ensure the card is unused',
                        'Enter card details below',
                        'Upload proof of purchase',
                        'Wait for verification',
                    ],
                    'details' => [
                        'accepted_cards'   => 'Amazon, iTunes, Steam, Google Play',
                        'region'           => 'US / UK preferred',
                        'processing_time'  => '1 - 24 hours',
                    ],
                ],
                'created_at'     => '2026-04-13 15:41:54',
                'updated_at'     => '2026-04-13 15:41:54',
            ],
            [
                'id'             => 5,
                'name'           => 'CashApp',
                'code'           => 'cashapp',
                'logo'           => 'images/gateway/cashapp.png',
                'type'           => 'manual',
                'status'         => 1,
                'min_amount'     => 10.00,
                'max_amount'     => 5000.00,
                'fixed_fee'      => 0.00,
                'percent_fee'    => 0.00,
                'currency'       => 'USD',
                'credentials'    => null,
                'payment_fields' => [
                    ['name' => 'sender_tag', 'label' => 'Your CashApp Tag', 'type' => 'text', 'required' => true],
                    ['name' => 'proof',      'label' => 'Upload Proof',     'type' => 'file', 'required' => true],
                ],
                'instructions'   => [
                    'steps'   => [
                        'Send payment to $YourTag',
                        'Upload proof',
                        'Wait for approval',
                    ],
                    'details' => [
                        'cash_tag' => '$YourTag',
                    ],
                ],
                'created_at'     => '2026-04-13 15:42:07',
                'updated_at'     => '2026-04-13 15:42:07',
            ],
            [
                'id'             => 6,
                'name'           => 'PayPal Manual',
                'code'           => 'paypal',
                'logo'           => 'images/gateway/paypal.png',
                'type'           => 'manual',
                'status'         => 1,
                'min_amount'     => 10.00,
                'max_amount'     => 5000.00,
                'fixed_fee'      => 0.00,
                'percent_fee'    => 0.00,
                'currency'       => 'USD',
                'credentials'    => null,
                'payment_fields' => [
                    ['name' => 'sender_email',   'label' => 'Your PayPal Email',        'type' => 'text', 'required' => true],
                    ['name' => 'transaction_id', 'label' => 'Transaction ID',            'type' => 'text', 'required' => true],
                    ['name' => 'proof',          'label' => 'Upload Payment Screenshot', 'type' => 'file', 'required' => true],
                ],
                'instructions'   => [
                    'title'   => 'PayPal Payment Instructions',
                    'steps'   => [
                        'Send payment to our PayPal email',
                        'Use Friends & Family (if allowed)',
                        'Copy the transaction ID',
                        'Upload payment proof',
                        'Submit and wait for approval',
                    ],
                    'details' => [
                        'paypal_email'    => 'your-paypal@email.com',
                        'currency'        => 'USD',
                        'processing_time' => '5 mins - 6 hours',
                    ],
                ],
                'created_at'     => '2026-04-13 15:50:35',
                'updated_at'     => '2026-04-13 15:50:35',
            ],
            [
                'id'             => 7,
                'name'           => 'Zelle',
                'code'           => 'zelle',
                'logo'           => 'images/gateway/zelle.png',
                'type'           => 'manual',
                'status'         => 1,
                'min_amount'     => 10.00,
                'max_amount'     => 14440.00,
                'fixed_fee'      => 0.00,
                'percent_fee'    => 0.00,
                'currency'       => 'USD',
                'credentials'    => [],
                'payment_fields' => [],
                'instructions'   => [
                    'title' => 'Zelle Payment Instructions',
                    'steps' => [
                        'Send payment to our Zelle email or phone number',
                        'Ensure correct recipient before sending',
                        'Take a screenshot of the payment',
                        'Upload proof of payment',
                        'Wait for admin approval',
                    ],
                    'details' => [
                        'zelle_email'     => 'your-email@bank.com',
                        'zelle_phone'     => '+1234567890',
                        'processing_time' => 'Instant - 2 hours',
                    ],
                ],
                'created_at'     => '2026-04-25 16:35:27',
                'updated_at'     => '2026-06-05 07:47:41',
            ],
            [
                'id'             => 8,
                'name'           => 'Venmo',
                'code'           => 'venmo',
                'logo'           => 'images/gateway/venmo.png',
                'type'           => 'manual',
                'status'         => 1,
                'min_amount'     => 30.00,
                'max_amount'     => 2340.00,
                'fixed_fee'      => 0.00,
                'percent_fee'    => 0.00,
                'currency'       => 'USD',
                'credentials'    => null,
                'payment_fields' => null,
                'instructions'   => [
                    'title' => 'Venmo Payment Instructions',
                    'steps' => [
                        'Send payment to our Venmo username',
                        "Do NOT select 'Goods & Services'",
                        'Take screenshot of payment',
                        'Upload proof of payment',
                        'Wait for confirmation',
                    ],
                    'details' => [
                        'venmo_username'  => '@yourusername',
                        'full_name'       => 'Your Business Name',
                        'processing_time' => 'Instant - 3 hours',
                    ],
                ],
                'created_at'     => '2026-04-25 16:35:35',
                'updated_at'     => '2026-04-25 16:35:40',
            ],
        ];

        foreach ($gateways as $gateway) {
            $gateway['credentials']    = $gateway['credentials']    === null ? null : json_encode($gateway['credentials']);
            $gateway['payment_fields'] = $gateway['payment_fields'] === null ? null : json_encode($gateway['payment_fields']);
            $gateway['instructions']   = json_encode($gateway['instructions']); 

            DB::table('gateways')->updateOrInsert(
                ['code' => $gateway['code']],
                $gateway
            );
        }
    }
}