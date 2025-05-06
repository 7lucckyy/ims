<?php

namespace App\Providers\Filament;

use App\Filament\Meal\Pages\Dashboard;
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

class ProcurementPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('procurement')
            ->path('procurement')
            ->login()
            ->brandLogo(asset('gponicon.png'))
            ->brandLogoHeight('6.5rem')
            ->colors([
                'primary' => Color::Green
            ])
            ->discoverResources(in: app_path('Filament/Procurement/Resources'), for: 'App\\Filament\\Procurement\\Resources')
            ->discoverPages(in: app_path('Filament/Procurement/Pages'), for: 'App\\Filament\\Procurement\\Pages')
            ->pages([
                    // Pages\Dashboard::class,
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Procurement/Widgets'), for: 'App\\Filament\\Procurement\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])->sidebarWidth('250px')
            // ->maxContentWidth(MaxWidth::Full)
            ->sidebarCollapsibleOnDesktop();
    }
}
