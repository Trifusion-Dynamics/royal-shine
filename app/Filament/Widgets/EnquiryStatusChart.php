<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use Filament\Widgets\ChartWidget;

class EnquiryStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Enquiry Status Breakdown';

    protected function getData(): array
    {
        $statuses = ['new', 'contacted', 'in_progress', 'converted', 'closed'];

        return [
            'datasets' => [
                [
                    'label' => 'Enquiries',
                    'data' => collect($statuses)->map(fn ($status) => Enquiry::where('status', $status)->count())->toArray(),
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
