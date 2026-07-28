<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::SuperAdmin || $this->user()?->role === UserRole::Admin;
    }

    public function rules(): array
    {
        $service = $this->route('service');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:services,slug,'.$service->id],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'photo_alt' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
