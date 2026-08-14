<?php

namespace App\Filament\Admin\Resources\GatewayResource;

class GatewayPresets
{
    public static function options(): array
    {
        return [
            'custom' => 'Custom (start blank)',
            'paystack' => 'Paystack',
            'bank' => 'Bank Transfer',
            'crypto' => 'Crypto',
            'giftcard' => 'Gift Card',
            'cashapp' => 'CashApp',
            'paypal' => 'PayPal Manual',
            'zelle' => 'Zelle',
            'venmo' => 'Venmo',
        ];
    }

    public static function get(string $preset): ?array
    {
        return match ($preset) {
            'paystack' => [
                'name' => 'Paystack',
                'code' => 'paystack',
                'type' => 'auto',
                'currency' => 'NGN',
                'min_amount' => 100,
                'max_amount' => 1000000,
                'percent_fee' => 1.5,
                'credentials' => [
                    ['key' => 'public_key', 'value' => ''],
                    ['key' => 'secret_key', 'value' => ''],
                ],
                'payment_fields' => [],
                'instructions_title' => 'Pay with Paystack',
                'instructions_steps' => [
                    ['step' => 'Click deposit'],
                    ['step' => 'Complete payment on Paystack'],
                    ['step' => 'Wallet will be credited instantly'],
                ],
                'instructions_details' => [],
            ],
            'bank' => [
                'name' => 'Bank Transfer',
                'code' => 'bank',
                'type' => 'manual',
                'currency' => 'NGN',
                'min_amount' => 500,
                'max_amount' => 5000000,
                'credentials' => [],
                'payment_fields' => [
                    ['name' => 'account_name', 'label' => 'Sender Name', 'type' => 'text', 'required' => true],
                    ['name' => 'transaction_id', 'label' => 'Transaction Reference', 'type' => 'text', 'required' => true],
                    ['name' => 'proof', 'label' => 'Upload Payment Proof', 'type' => 'file', 'required' => true],
                ],
                'instructions_title' => 'Bank Transfer Instructions',
                'instructions_steps' => [
                    ['step' => 'Transfer to the account below'],
                    ['step' => 'Upload proof of payment'],
                    ['step' => 'Wait for admin approval'],
                ],
                'instructions_details' => [
                    ['key' => 'bank_name', 'value' => 'Access Bank'],
                    ['key' => 'account_name', 'value' => 'Fan Platform Ltd'],
                    ['key' => 'account_number', 'value' => '1234567890'],
                ],
            ],
            'crypto' => [
                'name' => 'Crypto',
                'code' => 'crypto',
                'type' => 'manual',
                'currency' => 'USD',
                'min_amount' => 50,
                'max_amount' => 100000,
                'credentials' => [],
                'payment_fields' => [
                    ['name' => 'tx_hash', 'label' => 'Transaction Hash', 'type' => 'text', 'required' => true],
                ],
                'instructions_title' => 'Crypto Payment',
                'instructions_steps' => [
                    ['step' => 'Send crypto to wallet below'],
                    ['step' => 'Paste your transaction hash'],
                    ['step' => 'Wait for confirmation'],
                ],
                'instructions_details' => [
                    ['key' => 'wallet_address', 'value' => '0xABC123XYZ'],
                    ['key' => 'network', 'value' => 'USDT (TRC20)'],
                ],
            ],
            'giftcard' => [
                'name' => 'Gift Card',
                'code' => 'giftcard',
                'type' => 'manual',
                'currency' => 'USD',
                'min_amount' => 50,
                'max_amount' => 5000,
                'credentials' => [],
                'payment_fields' => [
                    ['name' => 'card_type', 'label' => 'Card Type (Amazon, iTunes, Steam)', 'type' => 'text', 'required' => true],
                    ['name' => 'card_amount', 'label' => 'Card Amount', 'type' => 'text', 'required' => true],
                    ['name' => 'card_code', 'label' => 'Card Code', 'type' => 'textarea', 'required' => true],
                    ['name' => 'receipt', 'label' => 'Upload Receipt / Image', 'type' => 'file', 'required' => true],
                ],
                'instructions_title' => 'Gift Card Payment Instructions',
                'instructions_steps' => [
                    ['step' => 'Purchase a valid gift card'],
                    ['step' => 'Ensure the card is unused'],
                    ['step' => 'Enter card details below'],
                    ['step' => 'Upload proof of purchase'],
                    ['step' => 'Wait for verification'],
                ],
                'instructions_details' => [
                    ['key' => 'accepted_cards', 'value' => 'Amazon, iTunes, Steam, Google Play'],
                    ['key' => 'region', 'value' => 'US / UK preferred'],
                    ['key' => 'processing_time', 'value' => '1 - 24 hours'],
                ],
            ],
            'cashapp' => [
                'name' => 'CashApp',
                'code' => 'cashapp',
                'type' => 'manual',
                'currency' => 'USD',
                'min_amount' => 10,
                'max_amount' => 5000,
                'credentials' => [],
                'payment_fields' => [
                    ['name' => 'sender_tag', 'label' => 'Your CashApp Tag', 'type' => 'text', 'required' => true],
                    ['name' => 'proof', 'label' => 'Upload Proof', 'type' => 'file', 'required' => true],
                ],
                'instructions_title' => '',
                'instructions_steps' => [
                    ['step' => 'Send payment to $YourTag'],
                    ['step' => 'Upload proof'],
                    ['step' => 'Wait for approval'],
                ],
                'instructions_details' => [
                    ['key' => 'cash_tag', 'value' => '$YourTag'],
                ],
            ],
            'paypal' => [
                'name' => 'PayPal Manual',
                'code' => 'paypal',
                'type' => 'manual',
                'currency' => 'USD',
                'min_amount' => 10,
                'max_amount' => 5000,
                'credentials' => [],
                'payment_fields' => [
                    ['name' => 'sender_email', 'label' => 'Your PayPal Email', 'type' => 'text', 'required' => true],
                    ['name' => 'transaction_id', 'label' => 'Transaction ID', 'type' => 'text', 'required' => true],
                    ['name' => 'proof', 'label' => 'Upload Payment Screenshot', 'type' => 'file', 'required' => true],
                ],
                'instructions_title' => 'PayPal Payment Instructions',
                'instructions_steps' => [
                    ['step' => 'Send payment to our PayPal email'],
                    ['step' => 'Use Friends & Family (if allowed)'],
                    ['step' => 'Copy the transaction ID'],
                    ['step' => 'Upload payment proof'],
                    ['step' => 'Submit and wait for approval'],
                ],
                'instructions_details' => [
                    ['key' => 'paypal_email', 'value' => 'your-paypal@email.com'],
                    ['key' => 'currency', 'value' => 'USD'],
                    ['key' => 'processing_time', 'value' => '5 mins - 6 hours'],
                ],
            ],
            'zelle' => [
                'name' => 'Zelle',
                'code' => 'zelle',
                'type' => 'manual',
                'currency' => 'USD',
                'min_amount' => 10,
                'max_amount' => 14440,
                'credentials' => [],
                'payment_fields' => [],
                'instructions_title' => 'Zelle Payment Instructions',
                'instructions_steps' => [
                    ['step' => 'Send payment to our Zelle email or phone number'],
                    ['step' => 'Ensure correct recipient before sending'],
                    ['step' => 'Take a screenshot of the payment'],
                    ['step' => 'Upload proof of payment'],
                    ['step' => 'Wait for admin approval'],
                ],
                'instructions_details' => [
                    ['key' => 'zelle_email', 'value' => 'your-email@bank.com'],
                    ['key' => 'zelle_phone', 'value' => '+1234567890'],
                    ['key' => 'processing_time', 'value' => 'Instant - 2 hours'],
                ],
            ],
            'venmo' => [
                'name' => 'Venmo',
                'code' => 'venmo',
                'type' => 'manual',
                'currency' => 'USD',
                'min_amount' => 30,
                'max_amount' => 2340,
                'credentials' => [],
                'payment_fields' => [],
                'instructions_title' => 'Venmo Payment Instructions',
                'instructions_steps' => [
                    ['step' => 'Send payment to our Venmo username'],
                    ['step' => "Do NOT select 'Goods & Services'"],
                    ['step' => 'Take screenshot of payment'],
                    ['step' => 'Upload proof of payment'],
                    ['step' => 'Wait for confirmation'],
                ],
                'instructions_details' => [
                    ['key' => 'venmo_username', 'value' => '@yourusername'],
                    ['key' => 'full_name', 'value' => 'Your Business Name'],
                    ['key' => 'processing_time', 'value' => 'Instant - 3 hours'],
                ],
            ],
            default => null,
        };
    }
    
}