<?php

namespace App\Filament\Resources\Posts\Posts;

use App\Filament\Resources\Posts\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Posts\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    /**
     * Use a document icon to distinguish posts from categories
     */
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    /**
     * Grouping under "Blog Management" to match the Category Resource
     */
    protected static string|UnitEnum|null $navigationGroup = 'Blog Management';

    /**
     * Setting the slug to 'posts' ensures the URL is /admin/posts
     */
    protected static ?string $slug = 'posts';

    /**
     * Changed from 'name' to 'title' to match the Post model schema
     */
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}