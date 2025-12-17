<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PackageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tool.name')
                    ->label('Tool')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->columnSpan(1),
                
                TextEntry::make('name')
                    ->label('Package Name')
                    ->weight('bold')
                    ->size('lg')
                    ->icon('heroicon-m-cube')
                    ->columnSpan(1),

                TextEntry::make('price')
                    ->label('Price')
                    ->money('EUR')
                    ->size('lg')
                    ->weight('bold')
                    ->color('success')
                    ->icon('heroicon-m-banknotes')
                    ->columnSpan(1),

                IconEntry::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->columnSpan(1),

                TextEntry::make('duration_type')
                    ->label('Duration Type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'trial' => 'warning',
                        'lifetime' => 'success',
                        'days' => 'info',
                        'months' => 'primary',
                        'years' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->icon('heroicon-m-clock')
                    ->columnSpan(1),
                
                TextEntry::make('duration_value')
                    ->label('Duration')
                    ->formatStateUsing(function ($record) {
                        if (!$record) return '-';
                        
                        if ($record->duration_type === 'lifetime') {
                            return 'Unlimited';
                        }
                        
                        $value = $record->duration_value ?? 0;
                        
                        return match($record->duration_type) {
                            'trial' => $value . ' Day' . ($value > 1 ? 's' : '') . ' Trial',
                            'days' => $value . ' Day' . ($value > 1 ? 's' : ''),
                            'months' => $value . ' Month' . ($value > 1 ? 's' : ''),
                            'years' => $value . ' Year' . ($value > 1 ? 's' : ''),
                            default => (string)$value,
                        };
                    })
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-calendar-days')
                    ->columnSpan(1),

                TextEntry::make('features')
                    ->label('Package Features')
                    ->formatStateUsing(function ($state) {
                        // Handle null or empty
                        if (empty($state)) {
                            return 'No features added yet';
                        }
                        
                        // If it's a string, try to decode JSON
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $state = $decoded;
                            } else {
                                // Not valid JSON, split by comma
                                $state = array_filter(array_map('trim', explode(',', $state)));
                            }
                        }
                        
                        // If it's an array, filter empty values
                        if (is_array($state)) {
                            $state = array_filter($state, fn($item) => !empty(trim($item)));
                            
                            if (empty($state)) {
                                return 'No features added yet';
                            }
                            
                            // Format as bulleted list
                            $features = array_map(fn($feature) => '• ' . trim($feature), $state);
                            return implode('<br>', $features);
                        }
                        
                        return 'No features added yet';
                    })
                    ->html()
                    ->placeholder('No features added yet')
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->since()
                    ->icon('heroicon-m-calendar')
                    ->columnSpan(1),
                
                TextEntry::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->since()
                    ->icon('heroicon-m-clock')
                    ->columnSpan(1),
            ])
            ->columns(2);
    }
}