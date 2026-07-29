<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsApiCache
{
    public static function bootClearsApiCache(): void
    {
        static::saved(function () {
            if (Cache::supportsTags()) {
                Cache::tags(['api'])->flush();
            } else {
                Cache::flush();
            }
        });

        static::deleted(function () {
            if (Cache::supportsTags()) {
                Cache::tags(['api'])->flush();
            } else {
                Cache::flush();
            }
        });
    }
}
