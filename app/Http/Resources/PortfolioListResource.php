<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'client' => $this->client,
            'project_url' => $this->project_url,
            'completed_at' => $this->completed_at?->toIso8601String(),
            'featured_image' => $this->whenLoaded('media', fn () => $this->media->first()?->url),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
