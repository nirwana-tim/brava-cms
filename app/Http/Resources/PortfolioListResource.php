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
            'photo' => $this->photo,
            'photo_alt' => $this->photo_alt,
            'featured_image' => $this->photo,
            'completed_at' => $this->completed_at?->toIso8601String(),
            'service' => new ServiceListResource($this->whenLoaded('service')),
        ];
    }
}
