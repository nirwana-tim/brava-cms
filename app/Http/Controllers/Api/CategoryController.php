<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function __construct(
        private readonly CategoryService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $type = $request->query('type');

        if (!$type) {
            return $this->error('Type parameter is required', 422);
        }

        $categories = $this->service->getByType($type);
        return $this->success(CategoryResource::collection($categories));
    }
}
