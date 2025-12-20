<?php

namespace App\Filament\Resources\Tools\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ToolInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->size(100)
                    ->defaultImageUrl(url('/images/placeholder-tool.png')),

                TextEntry::make('name')
                    ->label('Tool Name')
                    ->weight('bold')
                    ->size('lg')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->columnSpan(1),
                
                TextEntry::make('domain')
                    ->label('Domain')
                    ->icon('heroicon-m-globe-alt')
                    ->url(fn ($record) => 'https://' . $record->domain)
                    ->openUrlInNewTab()
                    ->copyable()
                    ->copyMessage('Domain copied!')
                    ->color('primary')
                    ->columnSpan(1),

                IconEntry::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->columnSpan(1),

                TextEntry::make('description')
                    ->label('Description')
                    ->placeholder('No description provided')
                    ->columnSpanFull(),

                TextEntry::make('api_key')
                    ->label('API Key')
                    ->copyable()
                    ->copyMessage('API Key copied!')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-m-key')
                    ->columnSpan(1),
                
                TextEntry::make('api_secret')
                    ->label('API Secret')
                    ->copyable()
                    ->copyMessage('API Secret copied!')
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-m-lock-closed')
                    ->formatStateUsing(fn ($state) => substr($state, 0, 10) . '•••' . substr($state, -10))
                    ->columnSpan(1),

                TextEntry::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->since()
                    ->icon('heroicon-m-calendar')
                    ->columnSpan(1),
                
                TextEntry::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->since()
                    ->icon('heroicon-m-clock')
                    ->columnSpan(1),
            ])
            ->columns(2);
    }
}