<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ServiceListResource;
use App\Http\Resources\ServiceResource;
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

    public function show(string $slug): JsonResponse
    {
        $service = $this->service->getBySlug($slug);

        if (! $service) {
            return $this->notFound('Service not found');
        }

        return $this->success(new ServiceResource($service));
    }
}
