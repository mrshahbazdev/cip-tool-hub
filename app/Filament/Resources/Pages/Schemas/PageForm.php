<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class PageForm
{
    /**
     * Configures the dynamic Page form schema using Filament v4 components.
     * This form handles the creation and editing of static pages for the frontend footer.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Main Page Content Section
                Section::make('Page Content')
                    ->description('Define the primary heading and body of your page.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Page Title')
                            ->placeholder('e.g., Privacy Policy')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Set $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Page::class, 'slug', ignoreRecord: true)
                            ->helperText('This is the URL path (e.g., /p/privacy-policy).'),

                        RichEditor::make('content')
                            ->label('Page Body')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'attachFiles', 'blockquote', 'bold', 'bulletList', 'codeBlock', 
                                'h2', 'h3', 'italic', 'link', 'orderedList', 'redo', 'strike', 'undo',
                            ])
                            ->fileAttachmentsDirectory('pages/attachments')
                            ->fileAttachmentsDisk('public'),
                    ])->columns(2),

                // Sidebar/Settings Section for SEO and Visibility
                Section::make('Visibility & SEO')
                    ->description('Manage how this page appears in the footer and search results.')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Visible in Footer')
                            ->helperText('If disabled, the link will not show in the website footer.')
                            ->default(true),

                        Textarea::make('meta_description')
                            ->label('SEO Description')
                            ->placeholder('Brief summary for search engine results...')
                            ->rows(3)
                            ->maxLength(160)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}