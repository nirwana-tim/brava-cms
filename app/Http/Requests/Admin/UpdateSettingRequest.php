<?php

namespace App\Http\Requests\Admin;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() || $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $existing = Setting::pluck('key')->all();

                foreach ($this->except('_token', '_method') as $key => $value) {
                    if (in_array($key, $existing, true) || in_array($key, Setting::allowedKeys(), true)) {
                        continue;
                    }

                    $validator->errors()->add($key, 'Setting key "'.$key.'" is not allowed.');
                }
            },
        ];
    }
}
