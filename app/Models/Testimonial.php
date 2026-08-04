<?php

namespace App\Models;

use App\Services\MediaService;
use App\Traits\ClearsApiCache;
use Database\Factories\TestimonialFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    /** @use HasFactory<TestimonialFactory> */
    use ClearsApiCache, HasFactory, HasTranslations, SoftDeletes;

    public array $translatable = ['client_name', 'content', 'avatar_alt'];

    protected $fillable = [
        'client_name', 'content',
        'rating', 'avatar', 'avatar_alt', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'rating' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Testimonial $testimonial) {
            if ($testimonial->isForceDeleting()) {
                app(MediaService::class)->deleteStoredUpload($testimonial->avatar);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
