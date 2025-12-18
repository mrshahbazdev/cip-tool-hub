<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestSubscriptions extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Subscription::query()
                    ->with(['user', 'package.tool']) // Eager load relations for performance
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                /**
                 * Updated Tool column to show the full domain as requested.
                 * We use the relationship path from your SubscriptionsTable logic.
                 */
                Tables\Columns\TextColumn::make('package.tool.name')
                    ->label('Tool / Instance')
                    ->formatStateUsing(fn (Subscription $record) => $record->full_domain)
                    ->description(fn (Subscription $record) => $record->package->tool->name)
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-globe-alt')
                    ->copyable()
                    ->copyMessage('Domain copied!')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('package.name')
                    ->label('Plan')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'warning',
                        'pending' => 'gray',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Provisioned')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->color('gray'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}