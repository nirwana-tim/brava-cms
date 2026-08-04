<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\BuildsCanonicalUrl;
use App\Http\Resources\Concerns\ResolvesMediaAlt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    use BuildsCanonicalUrl, ResolvesMediaAlt;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'slugs' => [
                'id' => $this->getTranslation('slug', 'id', false) ?: null,
                'en' => $this->getTranslation('slug', 'en', false) ?: null,
            ],
            'description' => $this->description,
            'specifications' => $this->specifications ?: [],
            'features' => $this->features ?: [],
            'client' => $this->client,
            'photo' => $this->photo ? url($this->photo) : null,
            'photo_alt' => $this->photo_alt ?: $this->mediaAlt($this->photo),
            'featured_image' => $this->photo ? url($this->photo) : null,
            'completed_at' => $this->completed_at?->toIso8601String(),
            'service' => new ServiceListResource($this->whenLoaded('service')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'og_image' => $this->og_image ? url($this->og_image) : null,
            'og_image_alt' => $this->og_image_alt ?: ($this->photo_alt ?: $this->mediaAlt($this->photo)),
            'robots_index' => $this->robots_index,
            'seo' => [
                'meta_title' => $this->meta_title ?: $this->title,
                'meta_description' => $this->meta_description ?: $this->description,
                'meta_keywords' => $this->meta_keywords,
                'og_title' => $this->meta_title ?: $this->title,
                'og_description' => $this->meta_description ?: $this->description,
                'og_image' => ($this->og_image ?: $this->photo) ? url($this->og_image ?: $this->photo) : null,
                'og_image_alt' => $this->og_image_alt ?: ($this->photo_alt ?: $this->mediaAlt($this->photo)),
                'robots_index' => $this->robots_index,
                'robots_follow' => $this->robots_follow,
                'schema_type' => $this->schema_type,
                'canonical_url' => $this->canonicalUrl('portfolio/'.$this->slug),
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
