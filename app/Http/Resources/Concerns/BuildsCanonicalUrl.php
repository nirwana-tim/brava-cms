<?php

namespace App\Http\Resources\Concerns;

use Illuminate\Database\Eloquent\Model;

trait BuildsCanonicalUrl
{
    protected function canonicalUrl(string $path): string
    {
        $frontendUrl = rtrim((string) (config('app.frontend_url') ?: config('app.url')), '/');
        $locale = app()->getLocale();

        $resource = $this->resource;
        if ($resource instanceof Model && empty($resource->getTranslation('slug', $locale, false))) {
            $locale = 'id';
        }

        return $frontendUrl.'/'.$locale.'/'.ltrim($path, '/');
    }
}
