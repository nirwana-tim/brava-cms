<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    use NormalizesTranslatableInputs;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields(['name', 'slug', 'description']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.id' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'array'],
            'slug.id' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug->id')],
            'slug.en' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:blog,portfolio'],
            'description' => ['nullable', 'array'],
            'description.id' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
        ];
    }
}
