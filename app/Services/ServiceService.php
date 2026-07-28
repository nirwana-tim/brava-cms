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
        $perPage = $filters['per_page'] ?? 12;

        return Cache::flexible('services.list.'.md5(serialize($filters)), [900, 1800], function () use ($filters, $perPage) {
            return $this->model->with('portfolioItems')
                ->active()
                ->published()
                ->when($filters['search'] ?? null, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%'.$search.'%')
                            ->orWhere('description', 'like', '%'.$search.'%');
                    });
                })
                ->orderBy('sort_order')
                ->paginate($perPage);
        });
    }

    public function flush(): void {}
}
