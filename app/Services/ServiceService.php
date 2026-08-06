<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ServiceService
{
    public function __construct(private readonly Service $model) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        $perPage = max(1, min((int) ($filters['per_page'] ?? 12), 100));
        $locale = app()->getLocale();

        return Cache::store('api')->flexible('services.list.'.$locale.'.'.md5(serialize($filters)).'.p'.request()->integer('page', 1), [900, 1800], function () use ($filters, $perPage, $locale) {
            return $this->model->active()
                ->when($filters['search'] ?? null, function ($query, $search) use ($locale) {
                    $query->where(function ($q) use ($search, $locale) {
                        $q->where("title->{$locale}", 'like', '%'.$search.'%')
                            ->orWhere('title->id', 'like', '%'.$search.'%')
                            ->orWhere("description->{$locale}", 'like', '%'.$search.'%')
                            ->orWhere('description->id', 'like', '%'.$search.'%');
                    });
                })
                ->orderBy('sort_order')
                ->orderBy('id')
                ->paginate($perPage)
                ->withQueryString();
        });
    }
}
