<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\BuildsCanonicalUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageSeoResource extends JsonResource
{
    use BuildsCanonicalUrl;

    public function toArray(Request $request): array
    {
        $path = $this->page_key === 'home' ? '' : $this->page_key;

        return [
            'page_key' => $this->page_key,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'og_image' => $this->og_image ? url($this->og_image) : null,
            'og_image_alt' => $this->og_image_alt,
            'robots_index' => $this->robots_index ?? true,
            'robots_follow' => $this->robots_follow ?? true,
            'canonical_url' => $this->canonical_url ?: $this->canonicalUrl($path),
            'schema_type' => $this->schema_type ?: 'WebPage',
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
