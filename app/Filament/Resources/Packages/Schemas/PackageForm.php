<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tool_id')
                    ->label('Tool')
                    ->relationship('tool', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a tool')
                    ->helperText('Select which tool this package belongs to')
                    ->columnSpan(1),
                
                TextInput::make('name')
                    ->label('Package Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Basic Plan, Pro Plan, Enterprise')
                    ->helperText('Name of the package')
                    ->columnSpan(1),

                TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->prefix('€')
                    ->minValue(0)
                    ->step(0.01)
                    ->default(0.00)
                    ->placeholder('0.00')
                    ->helperText('Package price in EUR')
                    ->columnSpan(1),
                
                Toggle::make('status')
                    ->label('Active Status')
                    ->default(true)
                    ->inline(false)
                    ->helperText('Enable to make this package available')
                    ->columnSpan(1),

                Select::make('duration_type')
                    ->label('Duration Type')
                    ->required()
                    ->options([
                        'trial' => 'Trial',
                        'days' => 'Days',
                        'months' => 'Months',
                        'years' => 'Years',
                        'lifetime' => 'Lifetime',
                    ])
                    ->default('months')
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 'lifetime') {
                            $set('duration_value', null);
                        }
                    })
                    ->helperText('Select subscription duration type')
                    ->columnSpan(1),
                
                TextInput::make('duration_value')
                    ->label('Duration Value')
                    ->numeric()
                    ->minValue(1)
                    ->placeholder('Enter number')
                    ->visible(fn ($get) => 
                        !in_array($get('duration_type'), ['lifetime', null])
                    )
                    ->required(fn ($get) => 
                        !in_array($get('duration_type'), ['lifetime', null])
                    )
                    ->helperText(function ($get) {
                        $type = $get('duration_type');
                        return match($type) {
                            'trial' => 'Number of trial days (e.g., 7)',
                            'days' => 'Number of days (e.g., 30)',
                            'months' => 'Number of months (e.g., 1, 3, 6, 12)',
                            'years' => 'Number of years (e.g., 1, 2)',
                            'lifetime' => 'Not needed for lifetime access',
                            default => 'Enter duration value based on type selected'
                        };
                    })
                    ->columnSpan(1),

                TagsInput::make('features')
                    ->label('Package Features')
                    ->placeholder('Type feature and press Enter')
                    ->helperText('Add features one by one (e.g., "Full Access", "Priority Support")')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}