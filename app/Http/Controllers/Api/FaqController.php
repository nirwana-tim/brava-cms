<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FaqResource;
use App\Services\FaqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends ApiController
{
    public function __construct(
        private readonly FaqService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $category = $request->query('category');
        $faqs = $this->service->all($category);

        return $this->success(FaqResource::collection($faqs));
    }
}
