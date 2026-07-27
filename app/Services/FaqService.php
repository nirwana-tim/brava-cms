<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FaqService
{
    public function __construct(private readonly Faq $model) {}

    public function all(?string $category = null): Collection
    {
        $cacheKey = $category ? 'faqs.category.'.$category : 'faqs.all';

        return Cache::flexible($cacheKey, [3600, 7200], function () use ($category) {
            return $this->model->active()
                ->when($category, fn ($query, $cat) => $query->inCategory($cat))
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function flush(): void {}
}
