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
            'slugs' => [
                'id' => $this->getTranslation('slug', 'id', false),
                'en' => $this->getTranslation('slug', 'en', false),
            ],
            'description' => $this->description,
            'client' => $this->client,
            'photo' => $this->photo ? url($this->photo) : null,
            'photo_alt' => $this->photo_alt,
            'featured_image' => $this->photo ? url($this->photo) : null,
            'completed_at' => $this->completed_at?->toIso8601String(),
            'service' => new ServiceListResource($this->whenLoaded('service')),
        ];
    }
}
