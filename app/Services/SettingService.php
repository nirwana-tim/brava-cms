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

    public function getByGroup(string $group): Collection
    {
        return Cache::store('api')->flexible('settings.group.'.$group, [3600, 7200], function () use ($group) {
            return $this->model->inGroup($group)->get()->keyBy('key');
        });
    }
}
