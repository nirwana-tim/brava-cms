<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Services\HtmlSanitizer;
use App\Services\MediaService;
use App\Traits\ClearsApiCache;
use App\Traits\LogsActivity;
use Database\Factories\BlogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Blog extends Model
{
    /** @use HasFactory<BlogFactory> */
    use ClearsApiCache, HasFactory, HasTranslations, LogsActivity, SoftDeletes;

    public array $translatable = [
        'title', 'slug', 'excerpt', 'content',
        'meta_title', 'meta_description', 'meta_keywords',
        'featured_image_alt', 'og_image_alt',
    ];

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
        });

        static::deleting(function (Blog $blog) {
            if ($blog->isForceDeleting()) {
                app(MediaService::class)
                    ->deleteStoredUpload($blog->featured_image);

                app(MediaService::class)
                    ->deleteStoredUpload($blog->og_image);

                $blog->media()->forceDelete();
            }
        });
    }

    public function getContentAttribute(?string $value): ?string
    {
        return app(HtmlSanitizer::class)->clean($value);
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
        return $query->where('status', PostStatus::Published)
            ->where('published_at', '<=', now());
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
