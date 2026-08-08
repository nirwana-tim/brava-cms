<?php

namespace App\Models;

use App\Services\MediaService;
use App\Traits\ClearsApiCache;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use ClearsApiCache, HasFactory, HasTranslations, SoftDeletes;

    public array $translatable = ['title', 'slug', 'description', 'photo_alt'];

    protected $fillable = [
        'title', 'slug', 'description', 'photo', 'photo_alt', 'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Service $service) {
            if ($service->isForceDeleting()) {
                app(MediaService::class)
                    ->deleteStoredUpload($service->photo);

                $service->media()->forceDelete();
            }
        });
    }

    public function portfolioItems(): HasMany
    {
        return $this->hasMany(PortfolioItem::class);
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
