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
        $locale = app()->getLocale();

        return Cache::store('api')->flexible('settings.all.'.$locale, [3600, 7200], function () {
            return $this->model->get()->keyBy('key');
        });
    }

    /**
     * Groups exposed through the public `/api/settings` endpoint.
     *
     * The `adsense` group only contains public identifiers (`ca-pub-...`,
     * slot IDs) plus an enable flag — all needed by the frontend to load
     * AdSense scripts. It is safe to expose; editing stays superadmin-only.
     * `system` remains excluded because it may hold credentials/secrets.
     */
    private const PUBLIC_GROUPS = ['general', 'contact', 'social', 'seo', 'adsense'];

    /**
     * Settings grouped by group key for the public API, e.g.
     * `['general' => ['site_name' => 'Brava CMS'], 'seo' => [...]]`.
     *
     * @return array<string, array<string, mixed>>
     */
    public function grouped(): array
    {
        $locale = app()->getLocale();

        return Cache::store('api')->flexible('settings.grouped.'.$locale, [3600, 7200], function () {
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

    /**
     * Public-facing subset of the settings, restricted to non-sensitive groups.
     *
     * @return array<string, array<string, mixed>>
     */
    public function publicGrouped(): array
    {
        $locale = app()->getLocale();

        return Cache::store('api')->flexible('settings.public.'.$locale, [3600, 7200], function () {
            return collect($this->grouped())
                ->only(self::PUBLIC_GROUPS)
                ->all();
        });
    }
}
