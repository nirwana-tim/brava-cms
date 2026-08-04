<?php

namespace App\Services;

use App\Models\Promo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PromoService
{
    public function getHighlighted(): ?Promo
    {
        return Cache::store('api')->flexible('promos.highlight', [900, 1800], function () {
            $highlight = Promo::currentlyRunning()->highlighted()->latest()->first();

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

        return Cache::store('api')->flexible('promos.list.'.md5(serialize($filters).'_'.$excludeId).'.p'.request()->integer('page', 1), [900, 1800], function () use ($filters, $perPage, $excludeId) {
            return Promo::active()
                ->where(fn ($q) => $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()))
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
                ->paginate($perPage)
                ->withQueryString();
        });
    }

    public function getBySlug(string $slug): ?Promo
    {
        return Cache::store('api')->flexible("promos.slug.{$slug}", [1800, 3600], function () use ($slug) {
            return Promo::active()
                ->where(fn ($q) => $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()))
                ->where('slug', $slug)
                ->first();
        });
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function listAllAdmin(array $filters = []): LengthAwarePaginator
    {
        $perPage = max(1, min((int) ($filters['per_page'] ?? 15), 100));

        return Promo::query()
            ->when(! empty($filters['status']), function ($query) use ($filters) {
                $status = $filters['status'];
                if ($status === 'active') {
                    $query->where('is_active', true)
                        ->where(fn ($q) => $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()))
                        ->where(fn ($q) => $q->whereNull('valid_from')->orWhere('valid_from', '<=', now()));
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($status === 'expired') {
                    $query->where('valid_until', '<', now());
                } elseif ($status === 'coming_soon') {
                    $query->where('valid_from', '>', now());
                }
            })
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
            ->paginate($perPage)
            ->withQueryString();
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
        $previousImage = $promo->image;

        $promo->update($data);

        if ($previousImage !== null && $previousImage !== $promo->image) {
            app(MediaService::class)->deleteStoredUpload($previousImage);
        }

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
