<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'slugs' => [
                'id' => $this->getTranslation('slug', 'id', false),
                'en' => $this->getTranslation('slug', 'en', false),
            ],
            'description' => $this->description,
        ];
    }
}
