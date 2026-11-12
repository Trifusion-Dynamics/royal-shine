<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class ProductCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Published Products by Category';

    public static function canView(): bool
    {
        return auth()->user()?->canAccessModule('products') ?? false;
    }

    protected function getData(): array
    {
        $categories = Category::query()
            ->withCount(['products' => fn ($query) => $query->where('status', 'published')])
            ->orderBy('sort_order')
            ->get();

        return [
            'datasets' => [[
                'label' => 'Published products',
                'data' => $categories->pluck('products_count')->all(),
                'backgroundColor' => [
                    '#b08548',
                    '#d4a373',
                    '#7b6853',
                    '#ecd9b0',
                    '#a87439',
                    '#dac5a6',
                    '#836953',
                    '#c9a76c',
                    '#eee5d7',
                    '#907451',
                    '#bd9a60',
                    '#d9c7aa',
                ],
            ]],
            'labels' => $categories->pluck('name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
