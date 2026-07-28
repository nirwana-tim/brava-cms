<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FaqResource;
use App\Services\FaqService;
use Illuminate\Http\JsonResponse;

class FaqController extends ApiController
{
    public function __construct(
        private readonly FaqService $service
    ) {}

    public function index(): JsonResponse
    {
        return $this->success(FaqResource::collection($this->service->all()));
    }
}
