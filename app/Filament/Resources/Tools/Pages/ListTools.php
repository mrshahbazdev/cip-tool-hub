<?php

namespace App\Filament\Resources\Tools\Pages;

use App\Filament\Resources\Tools\ToolResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTools extends ListRecords
{
    protected static string $resource = ToolResource::class;

    /**
     * Enhanced header actions with icons and labels.
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Tool')
                ->icon('heroicon-m-plus-circle')
                ->color('primary'),
        ];
    }

    /**
     * Added Tabs for instant filtering between Active and Inactive tools.
     * This provides a much more premium feel to the management dashboard.
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Ecosystem'),
            
            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', true))
                ->icon('heroicon-m-bolt')
                ->badge(fn () => static::getResource()::getEloquentQuery()->where('status', true)->count())
                ->badgeColor('success'),

            'inactive' => Tab::make('Inactive')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', false))
                ->icon('heroicon-m-pause-circle')
                ->badge(fn () => static::getResource()::getEloquentQuery()->where('status', false)->count())
                ->badgeColor('danger'),
        ];
    }
}