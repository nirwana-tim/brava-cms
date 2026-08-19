<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGlobalSeoRequest extends FormRequest
{
    use NormalizesTranslatableInputs, ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields(['default_meta_title', 'default_meta_description']);
    }

    public function rules(): array
    {
        return [
            'default_meta_title' => ['nullable', 'array'],
            'default_meta_title.id' => ['nullable', 'string', 'max:255'],
            'default_meta_title.en' => ['nullable', 'string', 'max:255'],
            'default_meta_description' => ['nullable', 'array'],
            'default_meta_description.id' => ['nullable', 'string', 'max:500'],
            'default_meta_description.en' => ['nullable', 'string', 'max:500'],
            'default_og_image' => $this->imageUrlRule(),
        ];
    }
}
