<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::SuperAdmin || $this->user()?->role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx', 'max:10240'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'collection' => ['nullable', 'string', 'max:255'],
        ];
    }
}
