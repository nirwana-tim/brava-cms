<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ServiceListResource;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends ApiController
{
    public function __construct(
        private readonly ServiceService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'per_page']);
        $services = $this->service->list($filters);

        return $this->paginatedSuccess(
            ServiceListResource::collection($services),
            $this->formatPagination($services)
        );
    }
}
