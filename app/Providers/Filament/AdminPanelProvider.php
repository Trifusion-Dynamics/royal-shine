<?php

namespace App\Providers\Filament;

use Filament\Pages\Dashboard;
use App\Filament\Pages\Settings;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\EnquiryStatusChart;
use App\Filament\Widgets\EnquiryTrendChart;
use App\Filament\Widgets\ProductCategoryChart;
use App\Filament\Widgets\RecentProducts;
use App\Filament\Widgets\RecentEnquiries;
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
use Illuminate\Support\HtmlString;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path(env('ADMIN_PATH', 'admin'))
            ->brandName('Royal Shine | Studio Admin')
            ->brandLogoHeight('2.5rem')
            ->login()
            ->passwordReset()
            ->profile()
            ->databaseNotifications()
            ->colors([
                'primary' => Color::hex('#a77b43'),
                'gray' => Color::Slate,
            ])
            ->darkMode()
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Overview',
                'Catalogue',
                'Customers & Enquiries',
                'Website Content',
                'Administration',
            ])
            ->maxContentWidth('full')
            ->renderHook('panels::head.end', fn (): HtmlString => new HtmlString(<<<'HTML'
                <style>
                    :root { --rs-gold: #a77b43; --rs-ink: #29231e; --rs-paper: #faf8f4; }
                    .fi-body { background: #f7f5f0; }
                    .fi-topbar { background: rgba(255,253,250,.94); border-bottom: 1px solid #e9dfd1; backdrop-filter: blur(14px); }
                    .fi-sidebar { background: #fffdfa; border-right: 1px solid #eee5d9; }
                    .fi-sidebar-nav-groups { gap: .65rem; }
                    .fi-sidebar-group-label { color: #9a713e; font-size: .68rem; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
                    .fi-sidebar-item-button { border-radius: .55rem; }
                    .fi-sidebar-item-button:hover { background: #f7f1e8; }
                    .fi-sidebar-item-active .fi-sidebar-item-button { background: #f3eadc; color: #79552f; }
                    .fi-wi-stats-overview-stat { border: 1px solid #ece3d7; border-radius: .8rem; box-shadow: 0 5px 20px rgba(45,32,20,.035); }
                    .fi-section, .fi-ta, .fi-wi-chart { border-color: #ece3d7 !important; border-radius: .8rem !important; box-shadow: 0 5px 22px rgba(45,32,20,.035); }
                    .fi-header-heading { color: var(--rs-ink); letter-spacing: -.025em; }
                    .fi-btn-color-primary { box-shadow: 0 5px 14px rgba(167,123,67,.18); }
                    .fi-input-wrp, .fi-select-input, .fi-fo-textarea { border-radius: .55rem; }
                    .fi-ta-header-cell { color: #796c5d; font-size: .69rem; font-weight: 700; letter-spacing: .09em; text-transform: uppercase; }
                    @media (max-width: 767px) { .fi-main { padding-inline: .85rem; } }
                </style>
            HTML))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
                Settings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                DashboardStats::class,
                EnquiryStatusChart::class,
                EnquiryTrendChart::class,
                ProductCategoryChart::class,
                RecentEnquiries::class,
                RecentProducts::class,
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
            ]);
    }
}
