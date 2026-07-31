<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\BuildsCanonicalUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    use BuildsCanonicalUrl;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'client' => $this->client,
            'project_url' => $this->project_url,
            'photo' => $this->photo ? url($this->photo) : null,
            'photo_alt' => $this->photo_alt,
            'featured_image' => $this->photo ? url($this->photo) : null,
            'completed_at' => $this->completed_at?->toIso8601String(),
            'service' => new ServiceListResource($this->whenLoaded('service')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'og_image' => $this->og_image ? url($this->og_image) : null,
            'og_image_alt' => $this->og_image_alt,
            'robots_index' => $this->robots_index,
            'seo' => [
                'meta_title' => $this->meta_title ?: $this->title,
                'meta_description' => $this->meta_description ?: $this->description,
                'og_title' => $this->meta_title ?: $this->title,
                'og_description' => $this->meta_description ?: $this->description,
                'og_image' => ($this->og_image ?: $this->photo) ? url($this->og_image ?: $this->photo) : null,
                'og_image_alt' => $this->og_image_alt ?: $this->photo_alt,
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
