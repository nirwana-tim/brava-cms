<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTestimonialRequest extends FormRequest
{
    use NormalizesTranslatableInputs, ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields(['client_name', 'content', 'avatar_alt']);
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'array'],
            'client_name.id' => ['required', 'string', 'max:255'],
            'client_name.en' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'array'],
            'content.id' => ['required', 'string'],
            'content.en' => ['nullable', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'avatar' => $this->imageUrlRule(),
            'avatar_alt' => ['nullable', 'array'],
            'avatar_alt.id' => ['nullable', 'string', 'max:255'],
            'avatar_alt.en' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', Rule::unique('testimonials', 'sort_order')->whereNull('deleted_at')],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'sort_order.unique' => 'Urutan (Sort Order) :input sudah dipakai oleh testimoni lain. Pilih angka lain yang belum digunakan.',
        ];
    }
}
