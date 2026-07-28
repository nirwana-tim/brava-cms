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
            'completed_at' => $this->completed_at?->toIso8601String(),
            'service' => new ServiceListResource($this->whenLoaded('service')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'photo_alt' => $this->photo_alt,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'og_image' => $this->og_image,
            'og_image_alt' => $this->og_image_alt,
            'robots_index' => $this->robots_index,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
