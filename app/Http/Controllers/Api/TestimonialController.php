<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TestimonialResource;
use App\Services\TestimonialService;
use Illuminate\Http\JsonResponse;

class TestimonialController extends ApiController
{
    public function __construct(
        private readonly TestimonialService $service
    ) {}

    public function index(): JsonResponse
    {
        $testimonials = $this->service->all();

        return $this->success(TestimonialResource::collection($testimonials));
    }
}
