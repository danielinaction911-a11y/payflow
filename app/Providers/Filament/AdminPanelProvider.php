<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Admin\Widgets\PendingKyc;
use App\Filament\Admin\Widgets\RecentTransactions;
use App\Filament\Admin\Widgets\StatsOverview;
use App\Filament\Admin\Widgets\UserGrowthChart;
use Filament\Navigation\MenuItem;
use App\Filament\Admin\Resources\SupportTicketResource;
use App\Filament\Admin\Pages\ManageSettings;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path(config('app.admin_path', 'secure-panel'))
            ->authGuard('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->profile(isSimple: false)
            ->brandName(fn() => setting('site_title', 'Admin Panel'))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                /*  \Filament\Widgets\AccountWidget::class, */
                StatsOverview::class,
                UserGrowthChart::class,
                RecentTransactions::class,
                PendingKyc::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->profile(isSimple: false) // enables Filament's default profile page
            ->userMenuItems([
                MenuItem::make()
                    ->label('Visit Website')
                    ->url(fn() => route('home'))
                    ->icon('heroicon-o-globe-alt')
                    ->openUrlInNewTab(),
                MenuItem::make()
                    ->label('Support')
                    ->url(fn() => SupportTicketResource::getUrl('index'))
                    ->icon('heroicon-o-lifebuoy'),
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn() => ManageSettings::getUrl())
                    ->icon('heroicon-o-cog'),
            ]);
    }
}
