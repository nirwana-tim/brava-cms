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
        $locale = app()->getLocale();

        return Cache::store('api')->flexible('testimonials.all.'.$locale, [3600, 7200], function () {
            return $this->model->active()->orderBy('sort_order')->get();
        });
    }
}
