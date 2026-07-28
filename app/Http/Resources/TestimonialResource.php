<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'company' => $this->company,
            'content' => $this->content,
            'rating' => $this->rating,
            'avatar' => $this->avatar,
            'avatar_alt' => $this->avatar_alt,
        ];
    }
}
