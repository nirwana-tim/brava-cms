<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url ? url($this->url) : null,
            'alt_text' => $this->alt_text,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'collection' => $this->collection,
        ];
    }
}
