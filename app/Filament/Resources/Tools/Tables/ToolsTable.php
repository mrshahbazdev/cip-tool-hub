<?php

namespace App\Filament\Resources\Tools\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class ToolsTable
{
    /**
     * Configures the Tools table logic.
     * Fixed: logo now shows as image, API fields enhanced with badges/icons.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Show Logo as an actual image instead of a URL
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->disk('public') // Ensure this matches your disk config
                    ->defaultImageUrl(url('/images/placeholder-tool.png')),

                TextColumn::make('name')
                    ->label('Tool Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('domain')
                    ->label('Domain')
                    ->icon('heroicon-m-globe-alt')
                    ->color('primary')
                    ->searchable(),

                // Enhanced API Key visibility
                TextColumn::make('api_key')
                    ->label('API Key')
                    ->icon('heroicon-m-key')
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->toggleable()
                    ->copyable(),

                // Masked API Secret visibility
                TextColumn::make('api_secret')
                    ->label('API Secret')
                    ->icon('heroicon-m-lock-closed')
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn ($state) => $state ? substr($state, 0, 4) . '••••' . substr($state, -4) : 'N/A')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable(),

                IconColumn::make('status')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}