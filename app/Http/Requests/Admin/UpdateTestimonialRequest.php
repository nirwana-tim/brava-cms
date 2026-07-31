<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
{
    use ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    public function rules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'avatar' => $this->imageUrlRule(),
            'avatar_alt' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
