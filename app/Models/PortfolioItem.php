<?php

namespace App\Models;

use App\Traits\ClearsApiCache;
use Database\Factories\PortfolioItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class PortfolioItem extends Model
{
    /** @use HasFactory<PortfolioItemFactory> */
    use ClearsApiCache, HasFactory, HasTranslations, SoftDeletes;

    public array $translatable = [
        'title', 'slug', 'description', 'client',
        'photo_alt', 'meta_title', 'meta_description', 'meta_keywords', 'og_image_alt',
    ];

    protected $fillable = [
        'service_id', 'title', 'slug', 'description',
        'specifications', 'features',
        'client', 'photo', 'photo_alt', 'completed_at', 'is_active',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'og_image_alt', 'robots_index',
        'robots_follow', 'schema_type',
    ];

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'features' => 'array',
            'is_active' => 'boolean',
            'completed_at' => 'date',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_portfolio_item');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
