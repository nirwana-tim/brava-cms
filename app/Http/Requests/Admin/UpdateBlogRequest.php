<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::SuperAdmin || $this->user()?->role === UserRole::Admin;
    }

    public function rules(): array
    {
        $blog = $this->route('blog');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug,'.$blog->id],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],
        ];
    }
}
