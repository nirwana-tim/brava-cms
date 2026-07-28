<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $this->featured_image,
            'featured_image_alt' => $this->featured_image_alt,
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'avatar' => $this->author->avatar,
            ]),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'published_at' => $this->published_at?->toIso8601String(),
            'seo' => [
                'meta_title' => $this->meta_title ?: $this->title,
                'meta_description' => $this->meta_description ?: ($this->excerpt ?: str(strip_tags($this->content ?: ''))->limit(160)->toString()),
                'meta_keywords' => $this->meta_keywords,
                'og_title' => $this->meta_title ?: $this->title,
                'og_description' => $this->meta_description ?: ($this->excerpt ?: str(strip_tags($this->content ?: ''))->limit(160)->toString()),
                'og_image' => $this->og_image ?: $this->featured_image,
                'og_image_alt' => $this->og_image_alt ?: $this->featured_image_alt,
                'robots_index' => $this->robots_index,
                'robots_follow' => $this->robots_follow,
                'schema_type' => $this->schema_type,
            ],
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
