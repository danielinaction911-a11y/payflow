<?php

use App\Models\Policy;
use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Get a setting value by key, with automatic type casting.
     * Usage: setting('site_name'), setting('min_deposit_amount', 10)
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('policy_content')) {
    /**
     * Get a policy's HTML content by slug.
     * Usage: policy_content('terms-of-service')
     */
    function policy_content(string $slug, ?string $default = null): ?string
    {
        return cache()->rememberForever("policy:{$slug}", function () use ($slug) {
            return Policy::where('slug', $slug)->where('is_active', true)->value('content');
        }) ?? $default;
    }
}

if (! function_exists('money_format')) {
    /**
     * Format a numeric amount as currency.
     * Usage: money_format(1234.5) -> "$1,234.50"
     */
    function money_format(float|int|string $amount, string $currency = 'USD', int $decimals = 2): string
    {
        $symbols = [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'BTC' => '₿', 'ETH' => 'Ξ',
        ];

        $symbol = $symbols[$currency] ?? $currency . ' ';

        return $symbol . number_format((float) $amount, $decimals);
    }
}

if (! function_exists('percentage_format')) {
    /**
     * Format a number as a percentage with +/- sign for gains/losses.
     * Usage: percentage_format(4.25) -> "+4.25%", percentage_format(-2.1) -> "-2.10%"
     */
    function percentage_format(float|int $value, int $decimals = 2): string
    {
        $sign = $value > 0 ? '+' : '';

        return $sign . number_format($value, $decimals) . '%';
    }
}

if (! function_exists('masked_account')) {
    /**
     * Mask a card/account number, showing only the last N digits.
     * Usage: masked_account('4242424242424242') -> "•••• •••• •••• 4242"
     */
    function masked_account(string $number, int $visibleDigits = 4): string
    {
        $last = substr($number, -$visibleDigits);

        return str_repeat('•', max(strlen($number) - $visibleDigits, 0)) . $last;
    }
}

if (! function_exists('transaction_reference')) {
    /**
     * Generate a unique transaction reference code.
     * Usage: transaction_reference('DEP') -> "DEP-8F3K2A9X"
     */
    function transaction_reference(string $prefix = 'TXN'): string
    {
        return strtoupper($prefix) . '-' . strtoupper(\Illuminate\Support\Str::random(8));
    }
}

if (! function_exists('status_color')) {
    /**
     * Map a status string to a Flux/Tailwind color name for badges.
     * Usage: status_color('approved') -> "green"
     */
    function status_color(string $status): string
    {
        return match (strtolower($status)) {
            'approved', 'completed', 'active', 'paid', 'accepted' => 'green',
            'pending', 'processing' => 'orange',
            'rejected', 'failed', 'cancelled', 'declined', 'banned', 'suspended' => 'red',
            'expired', 'closed' => 'zinc',
            default => 'blue',
        };
    }
}

if (! function_exists('time_ago')) {
    /**
     * Human-readable relative time.
     * Usage: time_ago($transaction->created_at) -> "3 minutes ago"
     */
    function time_ago(\Carbon\Carbon|string|null $date): ?string
    {
        if (! $date) {
            return null;
        }

        return \Carbon\Carbon::parse($date)->diffForHumans();
    }
}


if (!function_exists('getCountries')) {
    function getCountries()
    {
        return json_decode(
            file_get_contents(resource_path('views/json/country.json')),
            true
        );
    }
}

if (!function_exists('gettimezone')) {
    function gettimezone()
    {
        return json_decode(
            file_get_contents(resource_path('views/json/timeZone.json')),
            true
        );
    }
}

if (! function_exists('smart_amount')) {
    /**
     * Format a decimal amount by trimming insignificant trailing zeros,
     * while always keeping at least 2 decimal places.
     *
     * 10.000000000000000000  -> "10.00"
     * 1234.500000000000000000 -> "1,234.50"
     * 0.009400000000000000   -> "0.0094"
     * 0.123456780000000000   -> "0.12345678"
     */
    function smart_amount(float|int|string $amount): string
    {
        // Normalize to a plain decimal string, no scientific notation.
        $amount = number_format((float) $amount, 18, '.', '');

        // Trim trailing zeros, then a trailing dot if left bare.
        $trimmed = rtrim(rtrim($amount, '0'), '.');

        [$whole, $decimal] = array_pad(explode('.', $trimmed), 2, '');

        // Always show at least 2 decimal places.
        if (strlen($decimal) < 2) {
            $decimal = str_pad($decimal, 2, '0');
        }

        $whole = number_format((float) $whole, 0);

        return $whole . '.' . $decimal;
    }
}

if (! function_exists('currency_symbol')) {
    /**
     * Resolve a currency symbol without hand-maintaining a list of every
     * fiat currency. Crypto isn't real ISO 4217, so it still needs a small
     * manual map — but every standard fiat currency (USD, EUR, GBP, NGN,
     * JPY, ... all ~180 of them) comes free from PHP's intl extension.
     */
    function currency_symbol(string $currency): string
    {
        static $crypto = [
            'BTC' => '₿',
            'ETH' => 'Ξ',
            'USDT' => '',
            'USDC' => '',
        ];
 
        $currency = strtoupper($currency);
 
        if (array_key_exists($currency, $crypto)) {
            return $crypto[$currency];
        }
 
        if (! class_exists(\NumberFormatter::class)) {
            // ext-intl not installed — fall back to just the code.
            return '';
        }
 
        static $cache = [];
 
        if (isset($cache[$currency])) {
            return $cache[$currency];
        }
 
        try {
            $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
            $formatted = $formatter->formatCurrency(0, $currency);
 
            if ($formatted === false) {
                return $cache[$currency] = '';
            }
 
            // formatCurrency(0, 'NGN') => "₦0.00" — strip the digits/
            // punctuation to isolate just the symbol.
            $symbol = trim(preg_replace('/[0-9.,\x{00A0}\s]/u', '', $formatted));
 
            return $cache[$currency] = $symbol;
        } catch (\Throwable $e) {
            return $cache[$currency] = '';
        }
    }
}
 
if (! function_exists('smart_money')) {
    /**
     * Same as smart_amount(), but prefixed with a currency symbol.
     */
    function smart_money(float|int|string $amount, string $currency = 'USD'): string
    {
        $symbol = currency_symbol($currency);
        $suffix = $symbol === '' ? ' ' . strtoupper($currency) : '';
 
        return $symbol . smart_amount($amount) . $suffix;
    }
}