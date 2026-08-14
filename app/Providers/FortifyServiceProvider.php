<?php

namespace App\Providers;

use App\Fortify\Actions\ConfirmTwoFactorAuthentication as AppConfirmTwoFactorAuthentication;
use App\Fortify\Actions\DisableTwoFactorAuthentication as AppDisableTwoFactorAuthentication;
use App\Fortify\Actions\EnableTwoFactorAuthentication as AppEnableTwoFactorAuthentication;
use Illuminate\Support\ServiceProvider;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (! class_exists(\Laravel\Fortify\Actions\EnableTwoFactorAuthentication::class)) {
            class_alias(AppEnableTwoFactorAuthentication::class, \Laravel\Fortify\Actions\EnableTwoFactorAuthentication::class);
        }

        if (! class_exists(\Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication::class)) {
            class_alias(AppConfirmTwoFactorAuthentication::class, \Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication::class);
        }

        if (! class_exists(\Laravel\Fortify\Actions\DisableTwoFactorAuthentication::class)) {
            class_alias(AppDisableTwoFactorAuthentication::class, \Laravel\Fortify\Actions\DisableTwoFactorAuthentication::class);
        }

        $this->app->bind(\Laravel\Fortify\Actions\EnableTwoFactorAuthentication::class, AppEnableTwoFactorAuthentication::class);
        $this->app->bind(\Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication::class, AppConfirmTwoFactorAuthentication::class);
        $this->app->bind(\Laravel\Fortify\Actions\DisableTwoFactorAuthentication::class, AppDisableTwoFactorAuthentication::class);
    }

    public function boot(): void
    {
        //
    }
}
