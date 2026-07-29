<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePortfolioRequest;
use App\Http\Requests\Admin\UpdatePortfolioRequest;
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
    public function __construct()
    {
        $this->authorizeResource(PortfolioItem::class, 'portfolio');
    }

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

    public function store(StorePortfolioRequest $request): RedirectResponse
    {
        $validated = $this->applySeoFallbacks($request->validated());
        $portfolio = PortfolioItem::create($validated);

        if ($request->has('categories')) {
            $portfolio->categories()->sync($request->categories);
        }

        if ($galleryIds = $request->input('gallery_media_ids')) {
            $ids = array_filter(explode(',', $galleryIds));
            Media::whereIn('id', $ids)->update([
                'mediable_type' => PortfolioItem::class,
                'mediable_id' => $portfolio->id,
            ]);
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

    public function update(UpdatePortfolioRequest $request, PortfolioItem $portfolio): RedirectResponse
    {
        $validated = $this->applySeoFallbacks($request->validated());
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
        $this->authorize('update', $portfolio);

        $request->validate([
            'media_id' => ['required', 'exists:media,id'],
        ]);

        if (empty($portfolio->photo)) {
            return response()->json(['success' => false, 'message' => 'Upload dan simpan Cover Photo terlebih dahulu sebelum menambahkan foto detail.'], 422);
        }

        $count = $portfolio->media()->count();
        if ($count >= 4) {
            return response()->json(['success' => false, 'message' => 'Maksimal 4 foto detail gallery.'], 422);
        }

        $media = Media::findOrFail($request->media_id);
        $media->update([
            'mediable_type' => PortfolioItem::class,
            'mediable_id' => $portfolio->id,
        ]);

        return response()->json(['success' => true, 'url' => $media->url]);
    }

    public function detachMedia(PortfolioItem $portfolio, Media $medium): JsonResponse
    {
        $this->authorize('update', $portfolio);

        if ($medium->mediable_id !== $portfolio->id || $medium->mediable_type !== PortfolioItem::class) {
            return response()->json(['success' => false], 404);
        }

        $medium->update([
            'mediable_type' => null,
            'mediable_id' => null,
        ]);

        return response()->json(['success' => true]);
    }

    public function setCover(PortfolioItem $portfolio, Media $medium): JsonResponse
    {
        $this->authorize('update', $portfolio);

        if ($medium->mediable_id !== $portfolio->id || $medium->mediable_type !== PortfolioItem::class) {
            return response()->json(['success' => false], 404);
        }

        $portfolio->update([
            'photo' => $medium->url,
            'photo_alt' => $medium->alt_text,
        ]);

        $medium->delete();

        return response()->json(['success' => true]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function applySeoFallbacks(array $validated): array
    {
        $title = $validated['title'] ?? null;
        $description = ! empty($validated['description'])
            ? $validated['description']
            : str(strip_tags($validated['content'] ?? ''))->limit(160)->toString();

        $validated['meta_title'] = ! empty($validated['meta_title'])
            ? $validated['meta_title']
            : $title;

        $validated['meta_description'] = ! empty($validated['meta_description'])
            ? $validated['meta_description']
            : $description;

        $validated['og_image'] = ! empty($validated['og_image'])
            ? $validated['og_image']
            : ($validated['photo'] ?? null);

        $validated['og_image_alt'] = ! empty($validated['og_image_alt'])
            ? $validated['og_image_alt']
            : ($validated['photo_alt'] ?? null);

        return $validated;
    }
}
