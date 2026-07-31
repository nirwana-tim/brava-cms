<?php

namespace App\Models;

use App\Services\HtmlSanitizer;
use App\Traits\ClearsApiCache;
use Database\Factories\PortfolioItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioItem extends Model
{
    /** @use HasFactory<PortfolioItemFactory> */
    use ClearsApiCache, HasFactory, SoftDeletes;

    protected $fillable = [
        'service_id', 'title', 'slug', 'description', 'content',
        'client', 'photo', 'photo_alt', 'completed_at', 'is_active',
        'meta_title', 'meta_description', 'og_image', 'og_image_alt', 'robots_index',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'completed_at' => 'date',
            'robots_index' => 'boolean',
        ];
    }

    public function getContentAttribute(?string $value): ?string
    {
        return app(HtmlSanitizer::class)->clean($value);
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
