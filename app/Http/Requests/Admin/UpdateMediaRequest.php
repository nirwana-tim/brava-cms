<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    public function rules(): array
    {
        return [
            'alt_text' => ['nullable', 'string', 'max:255'],
            'collection' => ['nullable', 'string', 'max:255'],
        ];
    }
}
