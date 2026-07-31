<?php

namespace App\Http\Requests\Concerns;

/**
 * Shared validation rule for image/URL fields that may hold either a relative
 * storage path (e.g. "/storage/...") or an absolute http(s) URL.
 */
trait ValidatesImageUrl
{
    private function imageUrlRule(bool $required = false, int $max = 255): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            "max:{$max}",
            'regex:/^(?:\/|\.{1,2}\/|https?:\/\/|data:image\/)/i',
        ];
    }
}
