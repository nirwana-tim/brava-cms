<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageSeoRequest extends FormRequest
{
    use NormalizesTranslatableInputs, ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields(['meta_title', 'meta_description', 'og_image_alt']);
    }

    public function rules(): array
    {
        return [
            'meta_title' => ['nullable', 'array'],
            'meta_title.id' => ['nullable', 'string', 'max:255'],
            'meta_title.en' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'array'],
            'meta_description.id' => ['nullable', 'string', 'max:500'],
            'meta_description.en' => ['nullable', 'string', 'max:500'],
            'og_image' => $this->imageUrlRule(),
            'og_image_alt' => ['nullable', 'array'],
            'og_image_alt.id' => ['nullable', 'string', 'max:255'],
            'og_image_alt.en' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'schema_type' => ['nullable', 'string', Rule::in(['WebPage', 'AboutPage', 'ContactPage', 'Article', 'NewsArticle', 'Product', 'Offer', 'Service'])],
            'robots_index' => ['boolean'],
            'robots_follow' => ['boolean'],
        ];
    }
}
