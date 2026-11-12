<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use Filament\Widgets\ChartWidget;

class EnquiryTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Enquiries';

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->canAccessModule('enquiries') ?? false;
    }

    protected function getData(): array
    {
        $start = now()->startOfMonth()->subMonths(5);
        $counts = Enquiry::where('created_at', '>=', $start)
            ->get(['created_at'])
            ->groupBy(fn (Enquiry $enquiry): string => $enquiry->created_at->format('Y-m'))
            ->map->count();
        $months = collect(range(0, 5))->map(fn (int $offset) => $start->copy()->addMonths($offset));

        return [
            'datasets' => [[
                'label' => 'Enquiries',
                'data' => $months->map(fn ($month): int => (int) ($counts[$month->format('Y-m')] ?? 0))->all(),
                'borderColor' => '#b08548',
                'backgroundColor' => 'rgba(176, 133, 72, 0.18)',
                'fill' => true,
                'tension' => 0.35,
            ]],
            'labels' => $months->map(fn ($month): string => $month->format('M Y'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
