<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesImageUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    use ValidatesImageUrl;

    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isStaffOrAdmin();
    }

    public function rules(): array
    {
        $service = $this->route('service');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:services,slug,'.$service->id],
            'description' => ['nullable', 'string'],
            'photo' => $this->imageUrlRule(),
            'photo_alt' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
