<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ResolvesMediaAlt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceListResource extends JsonResource
{
    use ResolvesMediaAlt;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'photo' => $this->photo ? url($this->photo) : null,
            'photo_alt' => $this->photo_alt ?: $this->mediaAlt($this->photo),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
