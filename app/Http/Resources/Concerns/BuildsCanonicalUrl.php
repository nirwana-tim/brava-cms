<?php

namespace App\Http\Resources\Concerns;

trait BuildsCanonicalUrl
{
    protected function canonicalUrl(string $path): string
    {
        $frontendUrl = rtrim((string) config('app.frontend_url'), '/');

        return $frontendUrl.'/'.ltrim($path, '/');
    }
}
