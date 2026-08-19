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
        $locale = app()->getLocale();
        $page = max(1, min((int) request()->integer('page', 1), 1000));

        $filters['search'] = isset($filters['search']) ? mb_substr((string) $filters['search'], 0, 100) : null;

        return Cache::store('api')->flexible('portfolio.list.'.$locale.'.'.md5(serialize($filters)).'.p'.$page, [1800, 3600], function () use ($filters, $perPage, $locale, $page) {
            return $this->model->with('service')
                ->active()
                ->when($filters['search'] ?? null, function ($query, $search) use ($locale) {
                    $query->where(function ($q) use ($search, $locale) {
                        $q->where("title->{$locale}", 'like', '%'.$search.'%')
                            ->orWhere('title->id', 'like', '%'.$search.'%')
                            ->orWhere("description->{$locale}", 'like', '%'.$search.'%')
                            ->orWhere('description->id', 'like', '%'.$search.'%');
                    });
                })
                ->latest()
                ->paginate($perPage, ['*'], 'page', $page)
                ->withQueryString();
        });
    }

    public function getBySlug(string $slug): ?PortfolioItem
    {
        $locale = app()->getLocale();

        return Cache::store('api')->remember("portfolio.slug.{$locale}.{$slug}", 1800, function () use ($slug, $locale) {
            return $this->model->active()
                ->where(function ($q) use ($slug, $locale) {
                    $q->where("slug->{$locale}", $slug)
                        ->orWhere('slug->id', $slug);
                })
                ->with(['service', 'media'])
                ->first();
        });
    }
}
