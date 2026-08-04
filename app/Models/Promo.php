<?php

namespace App\Models;

use App\Traits\ClearsApiCache;
use Database\Factories\PromoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    /** @use HasFactory<PromoFactory> */
    use ClearsApiCache, HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'badge_text',
        'discount_info',
        'description',
        'image',
        'image_alt',
        'valid_from',
        'valid_until',
        'wa_template',
        'is_highlighted',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
            'is_highlighted' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Promo $promo) {
            if ($promo->isDirty('is_highlighted') && $promo->is_highlighted && (! $promo->is_active || ($promo->valid_until && $promo->valid_until->isPast()))) {
                throw new \InvalidArgumentException('Promo yang non-aktif atau sudah kedaluwarsa tidak dapat dijadikan Highlight.');
            }
        });

        static::saved(function (Promo $promo) {
            if ($promo->is_highlighted) {
                static::where('id', '!=', $promo->id)
                    ->where('is_highlighted', true)
                    ->update(['is_highlighted' => false]);
            }
        });
    }

    /**
     * @param  Builder<Promo>  $query
     * @return Builder<Promo>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentlyRunning(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            });
    }

    /**
     * @param  Builder<Promo>  $query
     * @return Builder<Promo>
     */
    public function scopeHighlighted(Builder $query): Builder
    {
        return $query->where('is_highlighted', true);
    }

    public function getIsComingSoonAttribute(): bool
    {
        return $this->valid_from && $this->valid_from->isFuture();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function getWaUrlAttribute(): string
    {
        return $this->buildWaUrl();
    }

    public function buildWaUrl(?string $rawNumber = null): string
    {
        $rawNumber ??= Setting::where('key', 'whatsapp_number')->value('value') ?? '6281234567890';
        $number = preg_replace('/[^0-9]/', '', (string) $rawNumber);
        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        $message = ! empty($this->wa_template)
            ? $this->wa_template
            : "Halo Brava, saya tertarik untuk mengklaim promo: {$this->title}.";

        return "https://wa.me/{$number}?text=".urlencode($message);
    }
}
