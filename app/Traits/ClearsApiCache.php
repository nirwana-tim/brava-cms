<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsApiCache
{
    public static function bootClearsApiCache(): void
    {
        $flush = function () {
            if (Cache::supportsTags()) {
                Cache::tags(['api'])->flush();
            } else {
                Cache::flush();
            }
        };

        static::saved($flush);
        static::deleted($flush);
    }
}
