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
        return Cache::flexible('categories.type.'.$type, [3600, 7200], function () use ($type) {
            return $this->model->active()->byType($type)->orderBy('sort_order')->get();
        });
    }

    public function flush(): void {}
}
