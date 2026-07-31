<?php

namespace App\Models;

use App\Services\HtmlSanitizer;
use App\Traits\ClearsApiCache;
use Database\Factories\FaqFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    /** @use HasFactory<FaqFactory> */
    use ClearsApiCache, HasFactory, SoftDeletes;

    protected $fillable = ['question', 'answer', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getAnswerAttribute(?string $value): ?string
    {
        return app(HtmlSanitizer::class)->clean($value);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
