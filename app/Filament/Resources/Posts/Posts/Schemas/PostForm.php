<?php

namespace App\Filament\Resources\Posts\Posts\Schemas;

use App\Models\Post;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostForm
{
    /**
     * Configures the Post form schema using Filament v4 components.
     * Updated: Removed the 2-column grid to allow sections to occupy the full width,
     * resolving the cramped layout issue where the sidebar pushed the content.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Main Content Section - Now occupies full width
                Section::make('Post Composition')
                    ->description('Draft your story and primary metadata.')
                    ->schema([
                        TextInput::make('title')
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
                            ->unique(Post::class, 'slug', ignoreRecord: true)
                            ->helperText('The SEO-friendly URL is generated from the title.'),

                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'attachFiles', 'blockquote', 'bold', 'bulletList', 'codeBlock', 
                                'h2', 'h3', 'italic', 'link', 'orderedList', 'redo', 'strike', 'undo',
                            ])
                            ->fileAttachmentsDirectory('blog/attachments'),

                        Textarea::make('excerpt')
                            ->label('Search Engine Description')
                            ->placeholder('Write a brief teaser for search results...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                // Media & Classification Section
                Section::make('Media & Classification')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('blog/covers')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->default(Auth::id())
                                    ->required()
                                    ->label('Author')
                                    ->helperText('Assigned to you by default.'),
                            ]),
                    ]),

                // Publishing Protocols Section
                Section::make('Publishing Protocols')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Publicly Visible')
                                    ->helperText('Toggle to publish or draft.')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state) {
                                            $set('published_at', now());
                                        }
                                    }),

                                DateTimePicker::make('published_at')
                                    ->label('Schedule Date')
                                    ->placeholder('Select date/time...')
                                    ->native(false),
                            ]),
                    ]),
            ]);
    }
}