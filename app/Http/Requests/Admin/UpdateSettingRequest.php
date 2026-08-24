<?php

namespace App\Http\Requests\Admin;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Per-key value rules. Empty values are allowed unless a rule states otherwise;
     * values are only validated when a non-empty value is submitted.
     *
     * @var array<string, list<string>>
     */
    private const KEY_RULES = [
        'site_name' => ['string', 'max:100'],
        'site_description' => ['string', 'max:255'],
        'default_meta_title' => ['string', 'max:70'],
        'default_meta_description' => ['string', 'max:160'],
        'google_analytics_id' => ['regex:/^(G-[A-Z0-9]{6,}|UA-[0-9]+-[0-9]+)$/i'],
        'adsense_enabled' => ['boolean'],
        'adsense_client_id' => ['regex:/^ca-pub-[0-9]{10,}$/'],
        'adsense_slot_1' => ['regex:/^[0-9]{3,}$/'],
        'adsense_slot_2' => ['regex:/^[0-9]{3,}$/'],
        'whatsapp_number' => ['regex:/^[0-9]{8,15}$/'],
        'phone' => ['string', 'max:50'],
        'facebook_url' => ['url'],
        'instagram_url' => ['url'],
        'youtube_url' => ['url'],
        'tiktok_url' => ['url'],
        'x_url' => ['url'],
        'linkedin_url' => ['url'],
        'ga4_property_id' => ['numeric'],
        'ga4_service_account_key' => ['json'],
        'organization_schema' => ['json'],
        'default_og_image' => ['url'],
        'google_verification' => ['string', 'max:255'],
        'bing_verification' => ['string', 'max:255'],
        'custom_webmaster_tags' => ['string', 'max:5000'],
    ];

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
                    if (! in_array($key, $existing, true) && ! in_array($key, Setting::allowedKeys(), true)) {
                        $validator->errors()->add($key, 'Setting key "'.$key.'" is not allowed.');

                        continue;
                    }

                    $rules = self::KEY_RULES[$key] ?? null;

                    if ($rules === null) {
                        continue;
                    }

                    $values = is_array($value) ? $value : [$key => $value];

                    foreach ($values as $locale => $item) {
                        if ($item === null || $item === '') {
                            continue;
                        }

                        $field = is_array($value) ? $key.'.'.$locale : $key;
                        $result = ValidatorFacade::make([$field => $item], [$field => $rules]);

                        if ($result->fails()) {
                            $validator->errors()->add($field, $result->errors()->first($field));
                        }
                    }
                }
            },
        ];
    }
}
