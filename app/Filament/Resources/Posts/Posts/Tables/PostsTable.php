<?php

namespace App\Filament\Resources\Posts\Posts\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

/**
 * Konpigurasion ti tabla para kadagiti Post.
 */
class PostsTable
{
    /**
     * Mang-configure iti tabla dagiti Posts a maus-usar kadagiti Filament v4 Table components.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Aklub')
                    ->circular()
                    ->disk('public'),

                TextColumn::make('title')
                    ->label('Titulo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap()
                    ->color('primary'),

                TextColumn::make('category.name')
                    ->label('Klasipikasion')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Kasasaad')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Petsa ti Pannakaipalubos')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Mannurat')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Kasasaad ti Pannakaipablaak')
                    ->boolean()
                    ->trueLabel('Dagiti Naipablaak laeng')
                    ->falseLabel('Dagiti Draft laeng')
                    ->native(false),

                SelectFilter::make('category')
                    ->label('Kategoria')
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }
}