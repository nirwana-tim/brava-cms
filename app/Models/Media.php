<?php

namespace App\Models;

use App\Traits\ClearsApiCache;
use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    /** @use HasFactory<MediaFactory> */
    use ClearsApiCache, HasFactory;

    protected $fillable = [
        'name', 'file_name', 'mime_type', 'size', 'disk', 'path',
        'alt_text', 'sort_order', 'collection',
        'mediable_type', 'mediable_id',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    protected static function booted(): void
    {
        static::deleting(function (Media $media) {
            if ($media->path && Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }
        });
    }
}
