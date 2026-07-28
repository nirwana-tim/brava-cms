<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StorePortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::SuperAdmin || $this->user()?->role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['nullable', 'exists:services,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:portfolio_items,slug'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'photo_alt' => ['nullable', 'string', 'max:255'],
            'client' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],
            'robots_index' => ['boolean'],
        ];
    }
}
