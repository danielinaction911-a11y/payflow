<?php

namespace App\Fortify\Actions;

use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class ConfirmTwoFactorAuthentication
{
    public function __invoke($user, string $code): void
    {
        if (blank($code) || strlen(trim($code)) !== 6 || ! is_numeric(trim($code))) {
            throw new \RuntimeException('Invalid verification code.');
        }

        $google2fa = new Google2FA();

        $user->forceFill([
            'two_factor_secret' => $user->two_factor_secret ?: $google2fa->generateSecretKey(),
            'two_factor_recovery_codes' => json_encode($this->generateRecoveryCodes()),
            'two_factor_confirmed_at' => now(),
        ])->save();
    }

    protected function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))->map(fn () => Str::upper(Str::random(8)))->values()->all();
    }
}
