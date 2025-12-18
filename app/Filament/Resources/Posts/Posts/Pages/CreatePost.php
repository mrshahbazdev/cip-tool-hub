<?php

namespace App\Filament\Resources\Posts\Posts\Pages;

use App\Filament\Resources\Posts\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
