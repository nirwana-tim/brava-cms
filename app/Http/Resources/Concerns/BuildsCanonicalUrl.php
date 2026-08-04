<?php

namespace App\Http\Resources\Concerns;

trait BuildsCanonicalUrl
{
    protected function canonicalUrl(string $path): string
    {
        $frontendUrl = rtrim((string) (config('app.frontend_url') ?: config('app.url')), '/');

        return $frontendUrl.'/'.app()->getLocale().'/'.ltrim($path, '/');
    }
}
