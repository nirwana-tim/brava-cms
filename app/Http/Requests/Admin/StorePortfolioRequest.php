<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;

class StorePortfolioRequest extends FormRequest
{
    use NormalizesTranslatableInputs, ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields([
            'title', 'slug', 'description', 'client', 'photo_alt',
            'meta_title', 'meta_description', 'meta_keywords', 'og_image_alt',
        ]);
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'title' => ['required', 'array'],
            'title.id' => ['required', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'array'],
            'slug.id' => ['required', 'string', 'max:255'],
            'slug.en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'array'],
            'description.id' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.key' => ['required', 'string'],
            'specifications.*.value' => ['required', 'string'],
            'features' => ['nullable', 'array'],
            'gallery_media_ids' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $ids = array_filter(explode(',', (string) $value));
                    if (count($ids) > 4) {
                        $fail('Maksimal 4 foto galeri.');
                    }
                },
            ],
            'client' => ['nullable', 'array'],
            'client.id' => ['nullable', 'string', 'max:255'],
            'client.en' => ['nullable', 'string', 'max:255'],
            'photo' => $this->imageUrlRule(),
            'photo_alt' => ['nullable', 'array'],
            'photo_alt.id' => ['nullable', 'string', 'max:255'],
            'photo_alt.en' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'is_active' => ['boolean'],
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
