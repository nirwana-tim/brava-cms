<?php

namespace App\Models;

use App\Services\MediaService;
use App\Traits\ClearsApiCache;
use App\Traits\LogsActivity;
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
    use ClearsApiCache, HasFactory, HasTranslations, LogsActivity, SoftDeletes;

    public array $translatable = [
        'title', 'slug', 'description',
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

    protected static function booted(): void
    {
        static::deleting(function (PortfolioItem $portfolio) {
            if ($portfolio->isForceDeleting()) {
                app(MediaService::class)
                    ->deleteStoredUpload($portfolio->photo);

                app(MediaService::class)
                    ->deleteStoredUpload($portfolio->og_image);

                $portfolio->media()->forceDelete();
            }
        });
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

    /**
     * Resolve the specifications list for a locale, falling back to the Indonesian
     * (default) list when the requested locale has no content. Legacy records that
     * stored a plain flat array are returned as-is.
     *
     * @return array<int, array{key: string, value: string}>
     */
    public function specificationsFor(?string $locale = null): array
    {
        return $this->localizedList('specifications', $locale);
    }

    /**
     * Resolve the features list for a locale, falling back to the Indonesian
     * (default) list when the requested locale has no content. Legacy records that
     * stored a plain flat array are returned as-is.
     *
     * @return array<int, string>
     */
    public function featuresFor(?string $locale = null): array
    {
        return $this->localizedList('features', $locale);
    }

    private function localizedList(string $field, ?string $locale): array
    {
        $value = $this->getAttribute($field);

        if (! is_array($value) || ! array_key_exists('id', $value)) {
            return $value ?: [];
        }

        $locale = $locale ?: app()->getLocale();

        if ($locale === 'id') {
            return $value['id'] ?? [];
        }

        return ($value['en'] ?? []) ?: ($value['id'] ?? []);
    }

    /**
     * Decode legacy JSON stored in the client column (e.g. {"id":"Acme","en":null})
     * so display and form values show a plain string.
     */
    protected function getClientAttribute(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return isset($decoded['en']) && $decoded['en'] !== null ? $decoded['en'] : ($decoded['id'] ?? $value);
        }

        return $value;
    }
}
