<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    use NormalizesTranslatableInputs, ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeTranslatableFields(['name', 'position']);
    }

    public function rules(): array
    {
        $roleRule = ['nullable', 'string', 'in:'.implode(',', $this->user()?->isSuperAdmin() ? ['staff', 'admin', 'super_admin'] : ['staff'])];

        return [
            'name' => ['required', 'array'],
            'name.id' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'position' => ['required', 'array'],
            'position.id' => ['required', 'string', 'max:255'],
            'position.en' => ['nullable', 'string', 'max:255'],
            'avatar' => $this->imageUrlRule(),
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
            'role' => $roleRule,
        ];
    }
}
