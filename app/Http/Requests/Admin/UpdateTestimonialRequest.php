<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::SuperAdmin || $this->user()?->role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'avatar_alt' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
