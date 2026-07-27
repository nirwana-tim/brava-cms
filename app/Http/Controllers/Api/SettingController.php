<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SettingResource;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends ApiController
{
    public function __construct(
        private readonly SettingService $service
    ) {}

    public function index(): JsonResponse
    {
        $settings = $this->service->all();

        return $this->success(SettingResource::collection($settings));
    }
}
