<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsApiCache
{
    public static function bootClearsApiCache(): void
    {
        $flush = function () {
            Cache::store('api')->flush();
        };

        static::saved($flush);
        static::deleted($flush);
    }
}
