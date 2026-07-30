<?php

namespace App\Services;

use App\Models\Promo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PromoService
{
    public function getHighlighted(): ?Promo
    {
        return Cache::flexible('promos.highlight', [900, 1800], function () {
            $highlight = Promo::active()->highlighted()->latest()->first();

            if (! $highlight) {
                $highlight = Promo::currentlyRunning()->latest()->first();
            }

            return $highlight;
        });
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function listActive(array $filters = []): LengthAwarePaginator
    {
        $perPage = max(1, min((int) ($filters['per_page'] ?? 12), 100));
        $highlight = $this->getHighlighted();
        $excludeId = $highlight?->id;

        return Cache::flexible('promos.list.'.md5(serialize($filters).'_'.$excludeId), [900, 1800], function () use ($filters, $perPage, $excludeId) {
            return Promo::active()
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->when(! empty($filters['search']), function ($query) use ($filters) {
                    $search = $filters['search'];
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('badge_text', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate($perPage);
        });
    }

    public function getBySlug(string $slug): ?Promo
    {
        return Cache::flexible("promos.slug.{$slug}", [1800, 3600], function () use ($slug) {
            return Promo::active()->where('slug', $slug)->first();
        });
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function listAllAdmin(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;

        return Promo::query()
            ->when(! empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('badge_text', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('is_highlighted')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): Promo
    {
        return Promo::create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Promo $promo, array $data): Promo
    {
        $promo->update($data);

        return $promo->fresh();
    }

    public function destroy(Promo $promo): void
    {
        $promo->delete();
    }

    public function setHighlight(Promo $promo): void
    {
        if (! $promo->is_active || $promo->is_expired) {
            throw new \InvalidArgumentException('Promo yang non-aktif atau sudah kedaluwarsa tidak dapat dijadikan Highlight.');
        }

        $promo->update(['is_highlighted' => true]);
    }
}
