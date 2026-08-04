<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PromoResource;
use App\Models\Promo;
use App\Services\MediaUsageService;
use App\Services\PromoService;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromoController extends ApiController
{
    public function __construct(
        private readonly PromoService $service,
        private readonly SettingService $settings,
        private readonly MediaUsageService $mediaUsageService
    ) {}

    private function waNumber(): string
    {
        return $this->settings->all()->get('whatsapp_number')?->value
            ?: Promo::DEFAULT_WA_NUMBER;
    }

    public function highlight(): JsonResponse
    {
        $highlight = $this->service->getHighlighted();

        if (! $highlight) {
            return $this->success(null);
        }

        $this->mediaUsageService->resolveAlts([$highlight->image]);

        return $this->success(new PromoResource($highlight, $this->waNumber()));
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'per_page']);
        $promos = $this->service->listActive($filters);
        $waNumber = $this->waNumber();

        $this->mediaUsageService->resolveAlts($promos->getCollection()->pluck('image'));

        return $this->paginatedSuccess(
            $promos->getCollection()->map(fn (Promo $promo) => new PromoResource($promo, $waNumber)),
            $this->formatPagination($promos)
        );
    }

    public function show(string $slug): JsonResponse
    {
        $promo = $this->service->getBySlug($slug);

        if (! $promo) {
            return $this->notFound('Promo not found');
        }

        $this->mediaUsageService->resolveAlts([$promo->image]);

        return $this->success(new PromoResource($promo, $this->waNumber()));
    }
}
