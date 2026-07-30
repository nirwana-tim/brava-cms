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
        return Cache::flexible('faqs.all', [3600, 7200], function () {
            return $this->model->active()
                ->orderBy('sort_order')
                ->get();
        });
    }
}
