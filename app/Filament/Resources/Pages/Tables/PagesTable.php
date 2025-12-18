<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Models\Page;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PagesTable
{
    /**
     * Configures the Pages table with reordering and visibility controls.
     */
    public static function configure(Table $table): Table
    {
        return $table
            /* Enables drag-and-drop reordering for footer links */
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label('Page Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('slug')
                    ->label('URL Path')
                    ->icon('heroicon-m-link')
                    ->fontFamily('mono')
                    ->color('gray')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => "/p/{$state}"),

                IconColumn::make('is_visible')
                    ->label('Footer Visibility')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_visible')
                    ->label('Visibility Status')
                    ->placeholder('All Pages')
                    ->trueLabel('Visible in Footer')
                    ->falseLabel('Hidden Pages'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}