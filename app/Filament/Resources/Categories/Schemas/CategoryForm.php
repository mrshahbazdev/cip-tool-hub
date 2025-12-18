<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class CategoryForm
{
    /**
     * Configures the form schema using Filament v4 components.
     * Following the provided documentation:
     * - Layout components (Section) are in Filament\Schemas\Components
     * - Field components (TextInput, Textarea) are in Filament\Forms\Components
     * - Utilities (Set) are in Filament\Schemas\Components\Utilities
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