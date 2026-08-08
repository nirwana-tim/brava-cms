<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
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

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            static::restored($flush);
        }
    }
}
