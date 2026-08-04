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
        $locale = app()->getLocale();

        return Cache::store('api')->flexible('sitemap.all.'.$locale, [900, 1800], function () use ($locale) {
            $frontendUrl = rtrim((string) (config('app.frontend_url') ?: config('app.url')), '/');

            $urls = collect()
                ->merge(Blog::query()
                    ->published()
                    ->get()
                    ->map(fn (Blog $blog) => [
                        'type' => 'blog',
                        'slug' => $blog->getTranslation('slug', $locale),
                        'slugs' => [
                            'id' => $blog->getTranslation('slug', 'id', false) ?: null,
                            'en' => $blog->getTranslation('slug', 'en', false) ?: null,
                        ],
                        'loc' => $frontendUrl.'/'.$locale.'/blogs/'.$blog->getTranslation('slug', $locale),
                        'lastmod' => $blog->updated_at?->toIso8601String(),
                    ]))
                ->merge(PortfolioItem::query()
                    ->active()
                    ->get()
                    ->map(fn (PortfolioItem $item) => [
                        'type' => 'portfolio',
                        'slug' => $item->getTranslation('slug', $locale),
                        'slugs' => [
                            'id' => $item->getTranslation('slug', 'id', false) ?: null,
                            'en' => $item->getTranslation('slug', 'en', false) ?: null,
                        ],
                        'loc' => $frontendUrl.'/'.$locale.'/portfolio/'.$item->getTranslation('slug', $locale),
                        'lastmod' => $item->updated_at?->toIso8601String(),
                    ]))
                ->merge(Promo::query()
                    ->active()
                    ->get()
                    ->map(fn (Promo $promo) => [
                        'type' => 'promo',
                        'slug' => $promo->getTranslation('slug', $locale),
                        'slugs' => [
                            'id' => $promo->getTranslation('slug', 'id', false) ?: null,
                            'en' => $promo->getTranslation('slug', 'en', false) ?: null,
                        ],
                        'loc' => $frontendUrl.'/'.$locale.'/promos/'.$promo->getTranslation('slug', $locale),
                        'lastmod' => $promo->updated_at?->toIso8601String(),
                    ]))
                ->merge(Service::query()
                    ->active()
                    ->get()
                    ->map(fn (Service $service) => [
                        'type' => 'service',
                        'slug' => $service->getTranslation('slug', $locale),
                        'slugs' => [
                            'id' => $service->getTranslation('slug', 'id', false) ?: null,
                            'en' => $service->getTranslation('slug', 'en', false) ?: null,
                        ],
                        'loc' => $frontendUrl.'/'.$locale.'/services/'.$service->getTranslation('slug', $locale),
                        'lastmod' => $service->updated_at?->toIso8601String(),
                    ]))
                ->merge(Category::query()
                    ->whereNotNull('type')
                    ->get()
                    ->map(fn (Category $category) => [
                        'type' => 'category',
                        'slug' => $category->getTranslation('slug', $locale),
                        'slugs' => [
                            'id' => $category->getTranslation('slug', 'id', false) ?: null,
                            'en' => $category->getTranslation('slug', 'en', false) ?: null,
                        ],
                        'loc' => $frontendUrl.'/'.$locale.'/'.$category->type.'?category='.$category->getTranslation('slug', $locale),
                        'lastmod' => $category->updated_at?->toIso8601String(),
                    ]))
                ->values();

            return $urls->all();
        });
    }
}
