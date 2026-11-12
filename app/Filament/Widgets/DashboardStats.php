<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use App\Models\Enquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
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
            Stat::make('Categories', Category::count())
                ->description('Active product categories')
                ->icon('heroicon-o-folder'),
            Stat::make('Total Enquiries', Enquiry::count())
                ->description('All customer enquiries')
                ->icon('heroicon-o-chat-bubble-left-ellipsis'),
            Stat::make('New Enquiries', Enquiry::where('status', 'new')->count())
                ->description('Awaiting response')
                ->icon('heroicon-o-clock')
                ->color('danger'),
        ];
    }
}
