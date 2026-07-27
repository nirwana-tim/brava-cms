<?php

namespace App\Services;

use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TestimonialService
{
    public function __construct(private readonly Testimonial $model) {}

    public function all(): Collection
    {
        return Cache::flexible('testimonials.all', [3600, 7200], function () {
            return $this->model->active()->orderBy('sort_order')->get();
        });
    }

    public function flush(): void {}
}
