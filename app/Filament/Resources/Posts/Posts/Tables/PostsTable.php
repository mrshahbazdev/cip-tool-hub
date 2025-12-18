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

class PostsTable
{
    /**
     * Configures the Posts table helper using Filament v4 Table components.
     * Designed for the CIP Tools premium blog ecosystem.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->circular()
                    ->disk('public'),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap()
                    ->color('primary'),

                TextColumn::make('category.name')
                    ->label('Classification')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Release Date')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->boolean()
                    ->trueLabel('Published Only')
                    ->falseLabel('Drafts Only')
                    ->native(false),

                SelectFilter::make('category')
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