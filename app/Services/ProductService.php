<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function __construct(private readonly Product $model) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 12;

        return Cache::flexible('products.list.'.md5(serialize($filters)), [900, 1800], function () use ($filters, $perPage) {
            return $this->model->with('categories')
                ->active()
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
                            ->orWhere('description', 'like', '%'.$search.'%');
                    });
                })
                ->orderByDesc('published_at')
                ->paginate($perPage);
        });
    }

    public function getBySlug(string $slug): ?Product
    {
        return Cache::remember('products.slug.'.$slug, 1800, function () use ($slug) {
            return $this->model->active()->published()->where('slug', $slug)->with(['categories', 'media'])->first();
        });
    }

    public function flush(): void {}
}
