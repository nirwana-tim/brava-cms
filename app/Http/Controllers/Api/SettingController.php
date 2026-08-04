<?php

namespace App\Http\Controllers\Api;

use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends ApiController
{
    public function __construct(
        private readonly SettingService $service
    ) {}

    public function index(): JsonResponse
    {
        return $this->success($this->service->grouped());
    }
}
