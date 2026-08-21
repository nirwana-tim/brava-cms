<?php

namespace App\Models;

use App\Traits\ClearsApiCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PageSeo extends Model
{
    use ClearsApiCache, HasFactory, HasTranslations;

    public array $translatable = ['meta_title', 'meta_description', 'og_image_alt'];

    protected $fillable = [
        'page_key',
        'meta_title',
        'meta_description',
        'og_image',
        'og_image_alt',
        'robots_index',
        'robots_follow',
    ];

    protected function casts(): array
    {
        return [
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
        ];
    }
}
