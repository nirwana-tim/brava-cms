<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioRequest extends FormRequest
{
    use ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    public function rules(): array
    {
        $portfolio = $this->route('portfolio');

        return [
            'service_id' => ['nullable', 'exists:services,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:portfolio_items,slug,'.$portfolio->id],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'photo' => $this->imageUrlRule(required: true),
            'photo_alt' => ['nullable', 'string', 'max:255'],
            'client' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'is_active' => ['boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_image' => $this->imageUrlRule(),
            'og_image_alt' => ['nullable', 'string', 'max:255'],
            'robots_index' => ['boolean'],
            'gallery_media_ids' => ['nullable', 'string'],
        ];
    }
}
