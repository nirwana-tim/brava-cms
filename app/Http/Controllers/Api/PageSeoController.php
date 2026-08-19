<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageSeoResource;
use App\Models\PageSeo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageSeoController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $page = $request->query('page');
        $lang = $request->query('lang', app()->getLocale());

        if ($page) {
            $cacheKey = "api:page_seo:{$page}:{$lang}";

            $data = Cache::store('api')->flexible(
                $cacheKey,
                [3600, 7200],
                function () use ($page) {
                    $item = PageSeo::where('page_key', $page)->first();

                    return $item ? (new PageSeoResource($item))->resolve() : null;
                }
            );

            if (! $data) {
                return $this->notFound('Page SEO not found');
            }

            return $this->success($data);
        }

        $cacheKey = "api:page_seo:all:{$lang}";

        $allData = Cache::store('api')->flexible(
            $cacheKey,
            [3600, 7200],
            function () {
                return PageSeo::all()->mapWithKeys(function (PageSeo $item) {
                    return [$item->page_key => (new PageSeoResource($item))->resolve()];
                })->all();
            }
        );

        return $this->success($allData);
    }
}
