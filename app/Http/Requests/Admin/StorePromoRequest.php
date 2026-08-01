<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;

class StorePromoRequest extends FormRequest
{
    use ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:promos,slug'],
            'badge_text' => ['nullable', 'string', 'max:100'],
            'discount_info' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'image' => $this->imageUrlRule(max: 500),
            'image_alt' => ['nullable', 'string', 'max:255'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'wa_template' => ['nullable', 'string', 'max:1000'],
            'is_highlighted' => ['boolean'],
            'is_active' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
}
