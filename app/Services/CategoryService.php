<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function __construct(private readonly Category $model) {}

    public function getByType(string $type): Collection
    {
        $locale = app()->getLocale();

        return Cache::store('api')->flexible('categories.type.'.$locale.'.'.$type, [3600, 7200], function () use ($type) {
            return $this->model->byType($type)->latest()->get();
        });
    }
}
