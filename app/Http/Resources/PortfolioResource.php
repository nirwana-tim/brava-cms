<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
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
            'photo' => $this->photo,
            'photo_alt' => $this->photo_alt,
            'featured_image' => $this->photo,
            'completed_at' => $this->completed_at?->toIso8601String(),
            'service' => new ServiceListResource($this->whenLoaded('service')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'og_image' => $this->og_image,
            'og_image_alt' => $this->og_image_alt,
            'robots_index' => $this->robots_index,
            'seo' => [
                'meta_title' => $this->meta_title ?: $this->title,
                'meta_description' => $this->meta_description ?: $this->description,
                'og_title' => $this->meta_title ?: $this->title,
                'og_description' => $this->meta_description ?: $this->description,
                'og_image' => $this->og_image ?: $this->photo,
                'og_image_alt' => $this->og_image_alt ?: $this->photo_alt,
                'robots_index' => $this->robots_index,
            ],
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
