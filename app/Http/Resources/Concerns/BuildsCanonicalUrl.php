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
        if ($resource instanceof Model && method_exists($resource, 'isTranslatableAttribute') && $resource->isTranslatableAttribute('slug')) {
            if (empty($resource->getTranslation('slug', $locale, false))) {
                $locale = 'id';
            }
        }

        $cleanPath = ltrim($path, '/');

        return $cleanPath ? $frontendUrl.'/'.$locale.'/'.$cleanPath : $frontendUrl.'/'.$locale;
    }
}
