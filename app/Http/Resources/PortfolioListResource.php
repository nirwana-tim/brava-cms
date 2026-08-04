<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ResolvesMediaAlt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioListResource extends JsonResource
{
    use ResolvesMediaAlt;

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
            'client' => $this->client,
            'photo' => $this->photo ? url($this->photo) : null,
            'photo_alt' => $this->photo_alt ?: $this->mediaAlt($this->photo),
            'featured_image' => $this->photo ? url($this->photo) : null,
            'completed_at' => $this->completed_at?->toIso8601String(),
            'service' => new ServiceListResource($this->whenLoaded('service')),
        ];
    }
}
