<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EnquiryStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Enquiry Status Breakdown';

    public static function canView(): bool
    {
        return auth()->user()?->canAccessModule('enquiries') ?? false;
    }

    protected function getData(): array
    {
        $counts = Enquiry::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $statuses = ['new', 'contacted', 'in_progress', 'converted', 'closed'];

        return [
            'datasets' => [
                [
                    'label' => 'Enquiries',
                    'data' => collect($statuses)->map(fn ($status) => (int) ($counts[$status] ?? 0))->toArray(),
                    'backgroundColor' => [
                        '#ef4444',
                        '#f59e0b',
                        '#3b82f6',
                        '#10b981',
                        '#6b7280',
                    ],
                ],
            ],
            'labels' => collect($statuses)->map(fn ($status) => ucfirst(str_replace('_', ' ', $status)))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
