<?php

namespace App\Services;

use App\Models\PortfolioItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PortfolioService
{
    public function __construct(private readonly PortfolioItem $model) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        $perPage = max(1, min((int) ($filters['per_page'] ?? 12), 100));

        return Cache::flexible('portfolio.list.'.md5(serialize($filters)), [1800, 3600], function () use ($filters, $perPage) {
            return $this->model->with('service')
                ->active()
                ->when($filters['search'] ?? null, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%'.$search.'%')
                            ->orWhere('description', 'like', '%'.$search.'%');
                    });
                })
                ->latest()
                ->paginate($perPage);
        });
    }

    public function getBySlug(string $slug): ?PortfolioItem
    {
        return Cache::remember('portfolio.slug.'.$slug, 1800, function () use ($slug) {
            return $this->model->active()->where('slug', $slug)->with(['service', 'media'])->first();
        });
    }
}
