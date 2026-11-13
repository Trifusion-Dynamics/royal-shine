<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\Category;
use App\Models\Enquiry;
use Filament\Pages\Page;
use Filament\Widgets\DashboardStats;
use Filament\Widgets\EnquiryStatusChart;
use Filament\Widgets\RecentEnquiries;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $title = 'Dashboard';

    protected static ?string $slug = 'dashboard';

    protected static bool $shouldRegisterNavigation = false;

    public function getWidgets(): array
    {
        return [
            DashboardStats::class,
            EnquiryStatusChart::class,
            RecentEnquiries::class,
        ];
    }
}
