<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class BlogService
{
    public function __construct(private readonly Blog $model) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        $perPage = max(1, min((int) ($filters['per_page'] ?? 12), 100));

        return Cache::flexible('blog.list.'.md5(serialize($filters)), [900, 1800], function () use ($filters, $perPage) {
            return $this->model->with(['author', 'categories'])
                ->published()
                ->when($filters['category'] ?? null, function ($query, $category) {
                    $query->whereHas('categories', fn ($q) => $q->where('slug', $category));
                })
                ->when($filters['featured'] ?? null, function ($query, $featured) {
                    $featured ? $query->featured() : null;
                })
                ->when($filters['search'] ?? null, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%'.$search.'%')
                            ->orWhere('excerpt', 'like', '%'.$search.'%');
                    });
                })
                ->orderByDesc('published_at')
                ->paginate($perPage);
        });
    }

    public function getBySlug(string $slug): ?Blog
    {
        return Cache::remember('blog.slug.'.$slug, 1800, function () use ($slug) {
            return $this->model->published()->where('slug', $slug)->with(['author', 'categories', 'media'])->first();
        });
    }

    public function flush(): void {}
}
