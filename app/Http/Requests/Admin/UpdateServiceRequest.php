<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    use NormalizesTranslatableInputs, ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields(['title', 'slug', 'description', 'photo_alt']);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'array'],
            'title.id' => ['required', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'array'],
            'slug.id' => ['required', 'string', 'max:255', Rule::unique('services', 'slug->id')->ignore($this->route('service'))],
            'slug.en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'array'],
            'description.id' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'photo' => $this->imageUrlRule(),
            'photo_alt' => ['nullable', 'array'],
            'photo_alt.id' => ['nullable', 'string', 'max:255'],
            'photo_alt.en' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', Rule::unique('services', 'sort_order')->ignore($this->route('service'))],
            'is_active' => ['boolean'],
        ];
    }
}
