<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $items = PortfolioItem::with('service', 'categories')->latest()->paginate(15);

        return view('admin.portfolio.index', compact('items'));
    }

    public function create(): View
    {
        $services = Service::pluck('title', 'id');
        $categories = Category::byType('portfolio')->pluck('name', 'id');

        return view('admin.portfolio.create', compact('services', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['nullable', 'exists:services,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:portfolio_items,slug'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'photo_alt' => ['nullable', 'string', 'max:255'],
            'client' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],
            'robots_index' => ['boolean'],
        ]);

        $portfolio = PortfolioItem::create($validated);

        if ($request->has('categories')) {
            $portfolio->categories()->sync($request->categories);
        }

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item created successfully.');
    }

    public function show(PortfolioItem $portfolio): View
    {
        $portfolio->load('service', 'categories', 'media');

        return view('admin.portfolio.show', compact('portfolio'));
    }

    public function edit(PortfolioItem $portfolio): View
    {
        $portfolio->load('service', 'categories', 'media');
        $services = Service::pluck('title', 'id');
        $categories = Category::byType('portfolio')->pluck('name', 'id');

        return view('admin.portfolio.edit', compact('portfolio', 'services', 'categories'));
    }

    public function update(Request $request, PortfolioItem $portfolio): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['nullable', 'exists:services,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:portfolio_items,slug,'.$portfolio->id],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'photo_alt' => ['nullable', 'string', 'max:255'],
            'client' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],
            'robots_index' => ['boolean'],
        ]);

        $portfolio->update($validated);

        if ($request->has('categories')) {
            $portfolio->categories()->sync($request->categories);
        } else {
            $portfolio->categories()->sync([]);
        }

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item updated successfully.');
    }

    public function destroy(PortfolioItem $portfolio): RedirectResponse
    {
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio item deleted successfully.');
    }

    public function attachMedia(Request $request, PortfolioItem $portfolio): JsonResponse
    {
        $request->validate([
            'media_id' => ['required', 'exists:media,id'],
        ]);

        $media = Media::findOrFail($request->media_id);
        $media->update([
            'mediable_type' => PortfolioItem::class,
            'mediable_id' => $portfolio->id,
        ]);

        return response()->json(['success' => true, 'url' => $media->url]);
    }

    public function detachMedia(PortfolioItem $portfolio, Media $medium): JsonResponse
    {
        if ($medium->mediable_id !== $portfolio->id || $medium->mediable_type !== PortfolioItem::class) {
            return response()->json(['success' => false], 404);
        }

        $medium->update([
            'mediable_type' => null,
            'mediable_id' => null,
        ]);

        return response()->json(['success' => true]);
    }
}
