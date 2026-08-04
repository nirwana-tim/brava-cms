<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Category;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    /**
     * @return array<int, array{type: string, slug: string, loc: string, lastmod: string|null}>
     */
    public function build(): array
    {
        return Cache::store('api')->flexible('sitemap.all', [900, 1800], function () {
            $frontendUrl = rtrim((string) (config('app.frontend_url') ?: config('app.url')), '/');

            $urls = collect()
                ->merge(Blog::query()
                    ->published()
                    ->select(['slug', 'updated_at'])
                    ->get()
                    ->map(fn (Blog $blog) => [
                        'type' => 'blog',
                        'slug' => $blog->slug,
                        'loc' => $frontendUrl.'/blogs/'.$blog->slug,
                        'lastmod' => $blog->updated_at?->toIso8601String(),
                    ]))
                ->merge(PortfolioItem::query()
                    ->active()
                    ->select(['slug', 'updated_at'])
                    ->get()
                    ->map(fn (PortfolioItem $item) => [
                        'type' => 'portfolio',
                        'slug' => $item->slug,
                        'loc' => $frontendUrl.'/portfolio/'.$item->slug,
                        'lastmod' => $item->updated_at?->toIso8601String(),
                    ]))
                ->merge(Promo::query()
                    ->active()
                    ->select(['slug', 'updated_at'])
                    ->get()
                    ->map(fn (Promo $promo) => [
                        'type' => 'promo',
                        'slug' => $promo->slug,
                        'loc' => $frontendUrl.'/promos/'.$promo->slug,
                        'lastmod' => $promo->updated_at?->toIso8601String(),
                    ]))
                ->merge(Service::query()
                    ->active()
                    ->select(['slug', 'updated_at'])
                    ->get()
                    ->map(fn (Service $service) => [
                        'type' => 'service',
                        'slug' => $service->slug,
                        'loc' => $frontendUrl.'/services/'.$service->slug,
                        'lastmod' => $service->updated_at?->toIso8601String(),
                    ]))
                ->merge(Category::query()
                    ->whereNotNull('type')
                    ->select(['slug', 'type', 'updated_at'])
                    ->get()
                    ->map(fn (Category $category) => [
                        'type' => 'category',
                        'slug' => $category->slug,
                        'loc' => $frontendUrl.'/'.$category->type.'?category='.$category->slug,
                        'lastmod' => $category->updated_at?->toIso8601String(),
                    ]))
                ->values();

            return $urls->all();
        });
    }
}
