<?php

namespace App\Fortify\Actions;

use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class EnableTwoFactorAuthentication
{
    public function __invoke($user): void
    {
        $google2fa = new Google2FA();
        $secret = $user->two_factor_secret;

        if (! $this->isValidSecret($secret)) {
            $secret = $google2fa->generateSecretKey();
        }

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    protected function isValidSecret(?string $secret): bool
    {
        if (empty($secret)) {
            return false;
        }

        return preg_match('/^[A-Z2-7]{16,}$/', strtoupper($secret)) === 1;
    }
}
