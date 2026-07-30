<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PromoResource;
use App\Services\PromoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromoController extends ApiController
{
    public function __construct(
        private readonly PromoService $service
    ) {}

    public function highlight(): JsonResponse
    {
        $highlight = $this->service->getHighlighted();

        if (! $highlight) {
            return $this->success(null);
        }

        return $this->success(new PromoResource($highlight));
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'per_page']);
        $promos = $this->service->listActive($filters);

        return $this->paginatedSuccess(
            PromoResource::collection($promos),
            $this->formatPagination($promos)
        );
    }

    public function show(string $slug): JsonResponse
    {
        $promo = $this->service->getBySlug($slug);

        if (! $promo) {
            return $this->notFound('Promo not found');
        }

        return $this->success(new PromoResource($promo));
    }
}
