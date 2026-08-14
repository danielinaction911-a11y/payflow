<?php

namespace App\Providers;

use App\Listeners\LogSuccessfulLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        AliasLoader::getInstance([
            'QrCode' => QrCodeFacade::class,
        ])->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* if (! $this->isAdminRequest()) {
            Paginator::defaultView('vendor.pagination.custom');
            Paginator::defaultSimpleView('vendor.pagination.custom');
        } */
        Event::listen(Login::class, LogSuccessfulLogin::class);
        View::addNamespace('layouts', resource_path('views/components/layouts'));
        View::addNamespace('settings', resource_path('views/components/settings'));
    }

    protected function isAdminRequest(): bool
    {
        if (app()->runningInConsole()) {
            return false;
        }

        return request()->is(config('app.admin_path', 'secure-panel') . '*');
    }
}
