<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Enquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->canAccessModule('enquiries') ?? false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('All products in catalogue')
                ->icon('heroicon-o-shopping-bag'),
            Stat::make('Published Products', Product::where('status', 'published')->count())
                ->description('Live on website')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Draft Products', Product::where('status', 'draft')->count())
                ->description('Not yet published')
                ->icon('heroicon-o-pencil')
                ->color('warning'),
            Stat::make('Inactive Products', Product::where('status', 'inactive')->count())
                ->description('Hidden from the catalogue')
                ->icon('heroicon-o-eye-slash')
                ->color('gray'),
            Stat::make('Categories', Category::count())
                ->description('Catalogue categories')
                ->icon('heroicon-o-folder'),
            Stat::make('Collections', Collection::count())
                ->description('Curated collections')
                ->icon('heroicon-o-squares-2x2'),
            Stat::make('Total Enquiries', Enquiry::count())
                ->description('All customer enquiries')
                ->icon('heroicon-o-chat-bubble-left-ellipsis'),
            Stat::make('New Enquiries', Enquiry::where('status', 'new')->count())
                ->description('Awaiting response')
                ->icon('heroicon-o-clock')
                ->color('danger'),
            Stat::make('Pending Enquiries', Enquiry::whereIn('status', ['new', 'contacted', 'in_progress'])->count())
                ->description('Open follow-up required')
                ->icon('heroicon-o-arrow-path')
                ->color('warning'),
            Stat::make('Contact Messages', Enquiry::where('enquiry_type', 'contact')->count())
                ->description('General website enquiries')
                ->icon('heroicon-o-envelope'),
            Stat::make('Resolved Enquiries', Enquiry::whereIn('status', ['converted', 'closed'])->count())
                ->description('Converted or closed')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
