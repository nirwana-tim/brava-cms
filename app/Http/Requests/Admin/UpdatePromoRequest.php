<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePromoRequest extends FormRequest
{
    use NormalizesTranslatableInputs, ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields([
            'title', 'slug', 'badge_text', 'discount_info', 'description',
            'image_alt', 'wa_template', 'meta_title', 'meta_description', 'meta_keywords',
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'array'],
            'title.id' => ['required', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'array'],
            'slug.id' => ['required', 'string', 'max:255', Rule::unique('promos', 'slug->id')->ignore($this->route('promo'))],
            'slug.en' => ['nullable', 'string', 'max:255'],
            'badge_text' => ['nullable', 'array'],
            'badge_text.id' => ['nullable', 'string', 'max:50'],
            'badge_text.en' => ['nullable', 'string', 'max:50'],
            'discount_info' => ['nullable', 'array'],
            'discount_info.id' => ['nullable', 'string', 'max:100'],
            'discount_info.en' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'array'],
            'description.id' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'image' => $this->imageUrlRule(),
            'image_alt' => ['nullable', 'array'],
            'image_alt.id' => ['nullable', 'string', 'max:255'],
            'image_alt.en' => ['nullable', 'string', 'max:255'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'wa_template' => ['nullable', 'array'],
            'wa_template.id' => ['nullable', 'string', 'max:500'],
            'wa_template.en' => ['nullable', 'string', 'max:500'],
            'is_highlighted' => ['boolean'],
            'is_active' => ['boolean'],
            'meta_title' => ['nullable', 'array'],
            'meta_title.id' => ['nullable', 'string', 'max:70'],
            'meta_title.en' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'array'],
            'meta_description.id' => ['nullable', 'string', 'max:160'],
            'meta_description.en' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'array'],
            'meta_keywords.id' => ['nullable', 'string', 'max:255'],
            'meta_keywords.en' => ['nullable', 'string', 'max:255'],
        ];
    }
}
