<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Traits\ClearsApiCache;
use Database\Factories\BlogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    /** @use HasFactory<BlogFactory> */
    use ClearsApiCache, HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id', 'title', 'slug', 'excerpt', 'content', 'featured_image',
        'featured_image_alt', 'published_at', 'is_featured', 'status',
        'meta_title', 'meta_description', 'meta_keywords',
        'og_image', 'og_image_alt',
        'robots_index', 'robots_follow', 'schema_type',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'published_at' => 'datetime',
            'status' => PostStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Blog $blog) {
            if ($blog->status === PostStatus::Published && empty($blog->published_at)) {
                $blog->published_at = now();
            }

            if ($blog->status === PostStatus::Draft) {
                $blog->published_at = null;
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::Published);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', PostStatus::Draft);
    }
}
