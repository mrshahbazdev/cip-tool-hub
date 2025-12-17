<?php

namespace App\Filament\Widgets;

use App\Models\Tool;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tools', Tool::count())
                ->description('Active: ' . Tool::where('status', true)->count())
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->color('success'),
            
            Stat::make('Active Subscriptions', Subscription::where('status', 'active')->count())
                ->description('Total: ' . Subscription::count())
                ->descriptionIcon('heroicon-m-ticket')
                ->chart([3, 7, 4, 2, 5, 8, 6])
                ->color('primary'),
            
            // Fix: Use 'status' instead of 'payment_status'
            Stat::make('Total Revenue', '€' . number_format(Transaction::where('status', 'completed')->sum('amount'), 2))
                ->description('Pending: €' . number_format(Transaction::where('status', 'pending')->sum('amount'), 2))
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([2, 5, 8, 12, 10, 15, 18])
                ->color('success'),
            
            Stat::make('Total Users', User::where('role', 'user')->count())
                ->description('New this month: ' . User::where('role', 'user')->whereMonth('created_at', now()->month)->count())
                ->descriptionIcon('heroicon-m-users')
                ->chart([1, 2, 3, 5, 4, 6, 8])
                ->color('warning'),
        ];
    }
}