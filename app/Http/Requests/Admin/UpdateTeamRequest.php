<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamRequest extends FormRequest
{
    use ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::SuperAdmin || $this->user()?->role === UserRole::Admin;
    }

    public function rules(): array
    {
        $team = $this->route('team');
        $userId = $team?->user_id;
        $userUnique = $userId ? "unique:users,email,{$userId}" : 'unique:users,email';

        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'avatar' => $this->imageUrlRule(),
            'email' => ['nullable', 'email', 'max:255', $userUnique, "unique:team_members,email,{$team?->id}"],
            'role' => ['nullable', 'string', Rule::in($this->assignableRoles())],
            'phone' => ['nullable', 'string', 'max:50'],
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
