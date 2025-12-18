<?php

namespace App\Filament\Widgets;

use App\Models\Tool;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Post;
use App\Models\Category;
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
            
            Stat::make('Total Revenue', '€' . number_format(Transaction::where('status', 'completed')->sum('amount'), 2))
                ->description('Pending: €' . number_format(Transaction::where('status', 'pending')->sum('amount'), 2))
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([2, 5, 8, 12, 10, 15, 18])
                ->color('success'),

            /* New Blog Statistics */
            Stat::make('Blog Posts', Post::count())
                ->description('Published: ' . Post::where('is_published', true)->count())
                ->descriptionIcon('heroicon-m-document-text')
                ->chart([2, 4, 6, 3, 7, 9, 12])
                ->color('info'),

            Stat::make('Blog Categories', Category::count())
                ->description('Active Content Topics')
                ->descriptionIcon('heroicon-m-tag')
                ->color('warning'),
            
            Stat::make('Total Users', User::where('role', 'user')->count())
                ->description('New this month: ' . User::where('role', 'user')->whereMonth('created_at', now()->month)->count())
                ->descriptionIcon('heroicon-m-users')
                ->chart([1, 2, 3, 5, 4, 6, 8])
                ->color('warning'),
        ];
    }
}