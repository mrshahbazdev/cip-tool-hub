<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;

class PostsTable
{
    /**
     * Configures the Posts table helper using Filament v4 Table components
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->circular(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('category.name')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Published On')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published Status'),
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}