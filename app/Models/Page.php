<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_visible',
        'sort_order',
        'meta_description'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /**
     * Auto-generate slug from title if not provided.
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
}