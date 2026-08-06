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

            $blog = function (Blog $blog) use ($frontendUrl, $locale): array {
                $slug = $blog->getTranslation('slug', $locale, false);
                $seg = empty($slug) ? 'id' : $locale;

                return [
                    'type' => 'blog',
                    'slug' => $slug ?: $blog->getTranslation('slug', 'id', false),
                    'slugs' => [
                        'id' => $blog->getTranslation('slug', 'id', false) ?: null,
                        'en' => $blog->getTranslation('slug', 'en', false) ?: null,
                    ],
                    'loc' => $frontendUrl.'/'.$seg.'/blogs/'.$blog->getTranslation('slug', $seg),
                    'lastmod' => $blog->updated_at?->toIso8601String(),
                ];
            };

            $portfolio = function (PortfolioItem $item) use ($frontendUrl, $locale): array {
                $slug = $item->getTranslation('slug', $locale, false);
                $seg = empty($slug) ? 'id' : $locale;

                return [
                    'type' => 'portfolio',
                    'slug' => $slug ?: $item->getTranslation('slug', 'id', false),
                    'slugs' => [
                        'id' => $item->getTranslation('slug', 'id', false) ?: null,
                        'en' => $item->getTranslation('slug', 'en', false) ?: null,
                    ],
                    'loc' => $frontendUrl.'/'.$seg.'/portfolio/'.$item->getTranslation('slug', $seg),
                    'lastmod' => $item->updated_at?->toIso8601String(),
                ];
            };

            $promo = function (Promo $promo) use ($frontendUrl, $locale): array {
                $slug = $promo->getTranslation('slug', $locale, false);
                $seg = empty($slug) ? 'id' : $locale;

                return [
                    'type' => 'promo',
                    'slug' => $slug ?: $promo->getTranslation('slug', 'id', false),
                    'slugs' => [
                        'id' => $promo->getTranslation('slug', 'id', false) ?: null,
                        'en' => $promo->getTranslation('slug', 'en', false) ?: null,
                    ],
                    'loc' => $frontendUrl.'/'.$seg.'/promos/'.$promo->getTranslation('slug', $seg),
                    'lastmod' => $promo->updated_at?->toIso8601String(),
                ];
            };

            $service = function (Service $service) use ($frontendUrl, $locale): array {
                $slug = $service->getTranslation('slug', $locale, false);
                $seg = empty($slug) ? 'id' : $locale;

                return [
                    'type' => 'service',
                    'slug' => $slug ?: $service->getTranslation('slug', 'id', false),
                    'slugs' => [
                        'id' => $service->getTranslation('slug', 'id', false) ?: null,
                        'en' => $service->getTranslation('slug', 'en', false) ?: null,
                    ],
                    'loc' => $frontendUrl.'/'.$seg.'/services/'.$service->getTranslation('slug', $seg),
                    'lastmod' => $service->updated_at?->toIso8601String(),
                ];
            };

            $category = function (Category $category) use ($frontendUrl, $locale): array {
                $slug = $category->getTranslation('slug', $locale, false);
                $seg = empty($slug) ? 'id' : $locale;

                return [
                    'type' => 'category',
                    'slug' => $slug ?: $category->getTranslation('slug', 'id', false),
                    'slugs' => [
                        'id' => $category->getTranslation('slug', 'id', false) ?: null,
                        'en' => $category->getTranslation('slug', 'en', false) ?: null,
                    ],
                    'loc' => $frontendUrl.'/'.$seg.'/'.$category->type.'?category='.$category->getTranslation('slug', $seg),
                    'lastmod' => $category->updated_at?->toIso8601String(),
                ];
            };

            $urls = collect()
                ->merge(Blog::query()->published()->get()->map($blog))
                ->merge(PortfolioItem::query()->active()->get()->map($portfolio))
                ->merge(Promo::query()->active()->get()->map($promo))
                ->merge(Service::query()->active()->get()->map($service))
                ->merge(Category::query()->whereNotNull('type')->get()->map($category))
                ->values();

            return $urls->all();
        });
    }
}
