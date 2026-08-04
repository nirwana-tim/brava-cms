<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BlogListResource;
use App\Http\Resources\BlogResource;
use App\Services\BlogService;
use App\Services\MediaUsageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends ApiController
{
    public function __construct(
        private readonly BlogService $service,
        private readonly MediaUsageService $mediaUsageService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'search', 'featured', 'per_page']);
        $posts = $this->service->list($filters);

        $this->mediaUsageService->resolveAlts($posts->getCollection()->pluck('featured_image'));

        return $this->paginatedSuccess(
            BlogListResource::collection($posts),
            $this->formatPagination($posts)
        );
    }

    public function show(string $slug): JsonResponse
    {
        $post = $this->service->getBySlug($slug);

        if (! $post) {
            return $this->notFound('Blog post not found');
        }

        $this->mediaUsageService->resolveAlts([$post->featured_image, $post->og_image]);

        return $this->success(new BlogResource($post));
    }
}
