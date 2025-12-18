<?php

namespace App\Filament\Resources\Posts\Schemas;

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
     * Configures the Post form schema using Filament v4 components
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Main Content')
                            ->description('The core information of your blog post.')
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
                                    ->unique(Post::class, 'slug', ignoreRecord: true),

                                RichEditor::make('content')
                                    ->required()
                                    ->columnSpanFull()
                                    ->fileAttachmentsDirectory('blog/attachments'),

                                Textarea::make('excerpt')
                                    ->label('Short Summary')
                                    ->placeholder('Short teaser for the blog list page...')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(2),

                        Grid::make(1)
                            ->schema([
                                Section::make('Settings')
                                    ->schema([
                                        FileUpload::make('cover_image')
                                            ->image()
                                            ->directory('blog/covers')
                                            ->imageEditor(),

                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        Select::make('user_id')
                                            ->relationship('user', 'name')
                                            ->default(Auth::id())
                                            ->required()
                                            ->label('Author'),
                                    ]),

                                Section::make('Publication Status')
                                    ->schema([
                                        Toggle::make('is_published')
                                            ->label('Go Live')
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set) {
                                                if ($state) {
                                                    $set('published_at', now());
                                                }
                                            }),

                                        DateTimePicker::make('published_at')
                                            ->label('Publishing Date')
                                            ->native(false),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}