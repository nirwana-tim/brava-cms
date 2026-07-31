<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\BuildsCanonicalUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoResource extends JsonResource
{
    use BuildsCanonicalUrl;

    public function __construct(mixed $resource, private readonly ?string $waNumber = null)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'badge_text' => $this->badge_text,
            'discount_info' => $this->discount_info,
            'description' => $this->description,
            'image' => $this->image ? url($this->image) : null,
            'image_alt' => $this->image_alt ?: $this->title,
            'valid_from' => $this->valid_from?->toIso8601String(),
            'valid_until' => $this->valid_until?->toIso8601String(),
            'wa_template' => $this->wa_template,
            'wa_url' => $this->waNumber !== null ? $this->buildWaUrl() : $this->wa_url,
            'is_highlighted' => (bool) $this->is_highlighted,
            'is_coming_soon' => $this->is_coming_soon,
            'state' => $this->is_coming_soon ? 'coming_soon' : ($this->is_expired ? 'expired' : 'active'),
            'seo' => [
                'meta_title' => $this->title,
                'meta_description' => str(strip_tags($this->description ?: ''))->limit(160)->toString(),
                'og_title' => $this->title,
                'og_description' => str(strip_tags($this->description ?: ''))->limit(160)->toString(),
                'og_image' => $this->image ? url($this->image) : null,
                'og_image_alt' => $this->image_alt ?: $this->title,
                'robots_index' => (bool) $this->is_active,
                'robots_follow' => true,
                'schema_type' => 'SpecialAnnouncement',
                'canonical_url' => $this->canonicalUrl('promos/'.$this->slug),
            ],
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    private function buildWaUrl(): string
    {
        return $this->resource->buildWaUrl($this->waNumber);
    }
}
