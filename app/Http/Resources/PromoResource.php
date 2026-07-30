<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoResource extends JsonResource
{
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
            'wa_url' => $this->wa_url,
            'is_highlighted' => (bool) $this->is_highlighted,
            'is_coming_soon' => $this->is_coming_soon,
            'state' => $this->is_coming_soon ? 'coming_soon' : ($this->is_expired ? 'expired' : 'active'),
        ];
    }
}
