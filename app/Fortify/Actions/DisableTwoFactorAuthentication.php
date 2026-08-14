<?php

namespace App\Fortify\Actions;

class DisableTwoFactorAuthentication
{
    public function __invoke($user): void
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }
}
