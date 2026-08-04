<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\BuildsCanonicalUrl;
use App\Http\Resources\Concerns\ResolvesMediaAlt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    use BuildsCanonicalUrl, ResolvesMediaAlt;

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
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $this->featured_image ? url($this->featured_image) : null,
            'featured_image_alt' => $this->featured_image_alt ?: $this->mediaAlt($this->featured_image),
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'avatar' => $this->author->avatar ? url($this->author->avatar) : null,
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
                'og_image' => ($this->og_image ?: $this->featured_image) ? url($this->og_image ?: $this->featured_image) : null,
                'og_image_alt' => $this->og_image_alt ?: ($this->featured_image_alt ?: $this->mediaAlt($this->featured_image)),
                'robots_index' => $this->robots_index,
                'robots_follow' => $this->robots_follow,
                'schema_type' => $this->schema_type,
                'canonical_url' => $this->canonicalUrl('blogs/'.$this->slug),
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
