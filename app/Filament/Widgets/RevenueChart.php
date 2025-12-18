<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue Trend';

    /**
     * Set the polling interval to '30s' for a realtime feel.
     */
    protected ?string $pollingInterval = '30s';

    /**
     * Determines the data displayed on the chart.
     * Updated to use the Trend package to ensure all months are represented,
     * preventing the "single dot" issue when data is sparse.
     */
    protected function getData(): array
    {
        $data = Trend::query(Transaction::where('status', 'completed'))
            ->between(
                start: now()->subMonths(5)->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perMonth()
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (€)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
                    'fill' => 'start',
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.1)',
                    'tension' => 0.4, // Adds a smooth curve to the line
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Extra styling to make the chart look premium.
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => "function(value) { return '€' + value.toLocaleString(); }",
                    ],
                ],
            ],
        ];
    }
}