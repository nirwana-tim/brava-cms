<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateGlobalSeoRequest;
use App\Http\Requests\Admin\UpdatePageSeoRequest;
use App\Models\PageSeo;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PageSeoController extends Controller
{
    public const GLOBAL_DEFAULTS_KEYS = ['default_meta_title', 'default_meta_description', 'default_og_image'];

    public function index(): View
    {
        $this->authorize('viewAny', PageSeo::class);

        $pageSeos = PageSeo::orderBy('page_key')->get();
        $defaults = $this->globalDefaults();

        return view('admin.page-seo.index', compact('pageSeos', 'defaults'));
    }

    public function defaults(): View
    {
        $defaults = $this->globalDefaults();

        $this->authorize('update', $defaults->get('default_meta_title'));

        return view('admin.page-seo.defaults', compact('defaults'));
    }

    public function updateDefaults(UpdateGlobalSeoRequest $request): RedirectResponse
    {
        $defaults = $this->globalDefaults();

        $this->authorize('update', $defaults->get('default_meta_title'));

        foreach (self::GLOBAL_DEFAULTS_KEYS as $key) {
            $setting = $defaults->get($key);

            if ($key === 'default_og_image') {
                $setting->update(['value' => $request->input('default_og_image') ?? '']);
            } else {
                $setting->update(['value' => $request->validated($key) ?? ['id' => null, 'en' => null]]);
            }
        }

        return redirect()->route('admin.page-seo.defaults')
            ->with('success', 'Global SEO defaults updated successfully.');
    }

    public function edit(string $page): View
    {
        $pageSeo = $this->resolvePageSeo($page);

        $this->authorize('update', $pageSeo);

        return view('admin.page-seo.edit', compact('pageSeo'));
    }

    public function show(string $page): View
    {
        $pageSeo = $this->resolvePageSeo($page);

        $this->authorize('view', $pageSeo);

        return view('admin.page-seo.show', compact('pageSeo'));
    }

    public function update(UpdatePageSeoRequest $request, string $page): RedirectResponse
    {
        $pageSeo = $this->resolvePageSeo($page);

        $this->authorize('update', $pageSeo);

        $pageSeo->update(array_merge($request->validated(), [
            'robots_index' => $request->boolean('robots_index'),
            'robots_follow' => $request->boolean('robots_follow'),
        ]));

        return redirect()->route('admin.page-seo.index')
            ->with('success', 'Page SEO updated successfully.');
    }

    private function globalDefaults(): Collection
    {
        $types = [
            'default_meta_title' => 'text',
            'default_meta_description' => 'textarea',
            'default_og_image' => 'text',
        ];

        foreach ($types as $key => $type) {
            Setting::updateOrCreate(['key' => $key], ['group' => 'seo', 'type' => $type]);
        }

        return Setting::whereIn('key', self::GLOBAL_DEFAULTS_KEYS)->get()->keyBy('key');
    }

    private function resolvePageSeo(string $page): PageSeo
    {
        return PageSeo::where('page_key', $page)->firstOrFail();
    }
}
