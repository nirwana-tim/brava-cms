<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        $team = $this->route('team');
        $emailRules = $team?->user_id !== null
            ? ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($team->user_id), Rule::unique('team_members', 'email')->ignore($team->id)]
            : ['nullable', 'email', 'max:255', Rule::unique('team_members', 'email')->ignore($team?->id)];

        return [
            'name' => ['required', 'array'],
            'name.id' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'position' => ['required', 'array'],
            'position.id' => ['required', 'string', 'max:255'],
            'position.en' => ['nullable', 'string', 'max:255'],
            'avatar' => $this->imageUrlRule(),
            'email' => $emailRules,
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
            'role' => $roleRule,
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi karena member ini memiliki akun login.',
            'email.unique' => 'Email sudah digunakan oleh member atau akun login lain.',
        ];
    }
}
