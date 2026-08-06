<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FaqService
{
    public function __construct(private readonly Faq $model) {}

    public function all(): Collection
    {
        $locale = app()->getLocale();

        return Cache::store('api')->flexible('faqs.all.'.$locale, [3600, 7200], function () {
            return $this->model->active()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        });
    }
}
