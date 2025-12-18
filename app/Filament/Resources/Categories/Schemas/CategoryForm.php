<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Set;
use Illuminate\Support\Str;

class CategoryForm
{
    /**
     * Configures the form schema using Filament v4 Schemas components
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Identity')
                    ->description('Define the naming and SEO URL for this content category.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Set $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'slug', ignoreRecord: true)
                            ->helperText('The unique URL slug is generated automatically.'),

                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->placeholder('Briefly describe the purpose of this category...'),
                    ])->columns(2)
            ]);
    }
}