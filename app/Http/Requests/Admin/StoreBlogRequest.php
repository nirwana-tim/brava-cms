<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogRequest extends FormRequest
{
    use NormalizesTranslatableInputs, ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields([
            'title', 'slug', 'excerpt', 'content',
            'meta_title', 'meta_description', 'meta_keywords',
            'featured_image_alt', 'og_image_alt',
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'array'],
            'title.id' => ['required', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],

            'slug' => ['required', 'array'],
            'slug.id' => ['required', 'string', 'max:255', Rule::unique('blogs', 'slug->id')],
            'slug.en' => ['nullable', 'string', 'max:255'],

            'excerpt' => ['nullable', 'array'],
            'excerpt.id' => ['nullable', 'string', 'max:500'],
            'excerpt.en' => ['nullable', 'string', 'max:500'],

            'content' => ['nullable', 'array'],
            'content.id' => ['nullable', 'string'],
            'content.en' => ['nullable', 'string'],

            'featured_image' => $this->imageUrlRule(),
            'featured_image_alt' => ['nullable', 'array'],
            'featured_image_alt.id' => ['nullable', 'string', 'max:255'],
            'featured_image_alt.en' => ['nullable', 'string', 'max:255'],

            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],

            'meta_title' => ['nullable', 'array'],
            'meta_title.id' => ['nullable', 'string', 'max:70'],
            'meta_title.en' => ['nullable', 'string', 'max:70'],

            'meta_description' => ['nullable', 'array'],
            'meta_description.id' => ['nullable', 'string', 'max:160'],
            'meta_description.en' => ['nullable', 'string', 'max:160'],

            'meta_keywords' => ['nullable', 'array'],
            'meta_keywords.id' => ['nullable', 'string', 'max:255'],
            'meta_keywords.en' => ['nullable', 'string', 'max:255'],

            'og_image' => $this->imageUrlRule(),
            'og_image_alt' => ['nullable', 'array'],
            'og_image_alt.id' => ['nullable', 'string', 'max:255'],
            'og_image_alt.en' => ['nullable', 'string', 'max:255'],

            'robots_index' => ['boolean'],
            'robots_follow' => ['boolean'],
            'schema_type' => ['nullable', 'string', 'max:50'],
        ];
    }
}
