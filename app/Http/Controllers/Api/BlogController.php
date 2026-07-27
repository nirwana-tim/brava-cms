<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BlogListResource;
use App\Http\Resources\BlogResource;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends ApiController
{
    public function __construct(
        private readonly BlogService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'tag', 'search', 'per_page']);
        $posts = $this->service->list($filters);

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

        return $this->success(new BlogResource($post));
    }
}
