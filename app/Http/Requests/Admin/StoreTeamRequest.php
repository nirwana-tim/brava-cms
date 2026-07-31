<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::SuperAdmin || $this->user()?->role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email', 'unique:team_members,email'],
            'role' => ['nullable', 'string', Rule::in($this->assignableRoles())],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required_with:email', 'nullable', 'string', 'min:8', 'confirmed'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    private function assignableRoles(): array
    {
        return $this->user()?->isSuperAdmin()
            ? [UserRole::Admin->value, UserRole::Staff->value]
            : [UserRole::Staff->value];
    }
}
