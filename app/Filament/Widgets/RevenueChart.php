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
    protected static ?string $pollingInterval = '30s';

    /**
     * Determines the data displayed on the chart.
     * It calculates the monthly sum of completed transactions.
     */
    protected function getData(): array
    {
        // Get data for the last 6 months
        $data = Transaction::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('SUM(amount) as total, DATE_FORMAT(created_at, "%Y-%m") as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (€)',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.1)',
                ],
            ],
            'labels' => $data->map(fn ($item) => Carbon::parse($item->month)->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        // Changed to 'line' for a professional revenue trend aesthetic
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