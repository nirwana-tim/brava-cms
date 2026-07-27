<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function __construct(
        private readonly ProductService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'featured', 'search', 'per_page']);
        $products = $this->service->list($filters);

        return $this->paginatedSuccess(
            ProductListResource::collection($products),
            $this->formatPagination($products)
        );
    }

    public function show(string $slug): JsonResponse
    {
        $product = $this->service->getBySlug($slug);

        if (! $product) {
            return $this->notFound('Product not found');
        }

        return $this->success(new ProductResource($product));
    }
}
