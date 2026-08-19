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
        $locale = app()->getLocale();
        $page = max(1, min((int) request()->integer('page', 1), 1000));

        $filters['search'] = isset($filters['search']) ? mb_substr((string) $filters['search'], 0, 100) : null;

        return Cache::store('api')->flexible('blog.list.'.$locale.'.'.md5(serialize($filters)).'.p'.$page, [900, 1800], function () use ($filters, $perPage, $locale, $page) {
            return $this->model->with(['author', 'categories'])
                ->published()
                ->when($filters['category'] ?? null, function ($query, $category) use ($locale) {
                    $query->whereHas('categories', fn ($q) => $q->where("slug->{$locale}", $category)->orWhere('slug->id', $category));
                })
                ->when(filter_var($filters['featured'] ?? null, FILTER_VALIDATE_BOOLEAN), fn ($q) => $q->featured())
                ->when($filters['search'] ?? null, function ($query, $search) use ($locale) {
                    $query->where(function ($q) use ($search, $locale) {
                        $q->where("title->{$locale}", 'like', '%'.$search.'%')
                            ->orWhere('title->id', 'like', '%'.$search.'%')
                            ->orWhere("excerpt->{$locale}", 'like', '%'.$search.'%')
                            ->orWhere('excerpt->id', 'like', '%'.$search.'%');
                    });
                })
                ->orderByDesc('published_at')
                ->paginate($perPage, ['*'], 'page', $page)
                ->withQueryString();
        });
    }

    public function getBySlug(string $slug): ?Blog
    {
        $locale = app()->getLocale();

        return Cache::store('api')->remember("blog.slug.{$locale}.{$slug}", 1800, function () use ($slug, $locale) {
            return $this->model->published()
                ->where(function ($q) use ($slug, $locale) {
                    $q->where("slug->{$locale}", $slug)
                        ->orWhere('slug->id', $slug);
                })
                ->with(['author', 'categories', 'media'])
                ->first();
        });
    }
}
