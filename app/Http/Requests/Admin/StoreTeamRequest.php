<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesTranslatableInputs;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamRequest extends FormRequest
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
        $roleRule = ['nullable', 'string', 'in:staff,admin'];
        if ($this->user()?->isAdmin() && ! $this->user()?->isSuperAdmin()) {
            $roleRule[] = function ($attribute, $value, $fail) {
                if ($value === 'admin') {
                    $fail('Admin biasa tidak dapat membuat user dengan role Admin.');
                }
            };
        }

        return [
            'name' => ['required', 'array'],
            'name.id' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'position' => ['required', 'array'],
            'position.id' => ['required', 'string', 'max:255'],
            'position.en' => ['nullable', 'string', 'max:255'],
            'avatar' => $this->imageUrlRule(),
            'email' => ['nullable', 'required_if:create_user_account,1', 'email', 'max:255', Rule::unique('users', 'email'), Rule::unique('team_members', 'email')],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
            'create_user_account' => ['nullable', 'boolean'],
            'role' => $roleRule,
            'password' => ['nullable', 'required_if:create_user_account,1', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_if' => 'Email wajib diisi jika membuat akun login.',
            'email.unique' => 'Email sudah digunakan oleh member atau akun login lain.',
            'password.required_if' => 'Password wajib diisi jika membuat akun login.',
        ];
    }
}
