<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PortfolioListResource;
use App\Http\Resources\PortfolioResource;
use App\Services\MediaUsageService;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends ApiController
{
    public function __construct(
        private readonly PortfolioService $service,
        private readonly MediaUsageService $mediaUsageService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'per_page']);
        $items = $this->service->list($filters);

        $this->mediaUsageService->resolveAlts($items->getCollection()->pluck('photo'));

        return $this->paginatedSuccess(
            PortfolioListResource::collection($items),
            $this->formatPagination($items)
        );
    }

    public function show(string $slug): JsonResponse
    {
        $item = $this->service->getBySlug($slug);

        if (! $item) {
            return $this->notFound('Portfolio item not found');
        }

        $this->mediaUsageService->resolveAlts([$item->photo, $item->og_image]);

        return $this->success(new PortfolioResource($item));
    }
}
