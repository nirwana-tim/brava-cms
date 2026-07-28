<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function __construct(private readonly Category $model) {}

    public function all(): Collection
    {
        return Cache::flexible('categories.all', [3600, 7200], function () {
            return $this->model->latest()->get();
        });
    }

    public function flush(): void {}
}
