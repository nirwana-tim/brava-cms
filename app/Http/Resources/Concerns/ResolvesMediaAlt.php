<?php

namespace App\Http\Resources\Concerns;

use App\Services\MediaUsageService;

trait ResolvesMediaAlt
{
    protected function mediaAlt(?string $url): ?string
    {
        return app(MediaUsageService::class)->resolveAlt($url);
    }
}
