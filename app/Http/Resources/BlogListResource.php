<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ResolvesMediaAlt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogListResource extends JsonResource
{
    use ResolvesMediaAlt;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'featured_image' => $this->featured_image ? url($this->featured_image) : null,
            'featured_image_alt' => $this->featured_image_alt ?: $this->mediaAlt($this->featured_image),
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ]),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
