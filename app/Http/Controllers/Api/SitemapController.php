<?php

namespace App\Http\Controllers\Api;

use App\Services\SitemapService;
use Illuminate\Http\JsonResponse;

class SitemapController extends ApiController
{
    public function __construct(
        private readonly SitemapService $service
    ) {}

    public function index(): JsonResponse
    {
        return $this->success($this->service->build());
    }
}
