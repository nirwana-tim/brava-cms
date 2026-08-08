<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFaqRequest extends FormRequest
{
    use NormalizesTranslatableInputs;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields(['question', 'answer']);
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'array'],
            'question.id' => ['required', 'string', 'max:255'],
            'question.en' => ['nullable', 'string', 'max:255'],
            'answer' => ['required', 'array'],
            'answer.id' => ['required', 'string'],
            'answer.en' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', Rule::unique('faqs', 'sort_order')->ignore($this->route('faq'))->whereNull('deleted_at')],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'sort_order.unique' => 'Urutan (Sort Order) :input sudah dipakai oleh FAQ lain. Pilih angka lain yang belum digunakan.',
        ];
    }
}
