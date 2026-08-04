<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public function __construct(private readonly Setting $model) {}

    public function all(): Collection
    {
        return Cache::store('api')->flexible('settings.all', [3600, 7200], function () {
            return $this->model->get()->keyBy('key');
        });
    }

    /**
     * Settings grouped by group key for the public API, e.g.
     * `['general' => ['site_name' => 'Brava CMS'], 'seo' => [...]]`.
     *
     * @return array<string, array<string, mixed>>
     */
    public function grouped(): array
    {
        return Cache::store('api')->flexible('settings.grouped', [3600, 7200], function () {
            return $this->all()
                ->groupBy('group')
                ->mapWithKeys(fn ($settings, string $group) => [
                    $group => $settings->mapWithKeys(function (Setting $setting) {
                        $value = $setting->value;

                        if (in_array($setting->type, ['boolean', 'bool'], true)) {
                            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        }

                        return [$setting->key => $value];
                    })->all(),
                ])
                ->all();
        });
    }
}
