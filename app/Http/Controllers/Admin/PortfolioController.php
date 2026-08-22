<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AppliesSeoFallbacks;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePortfolioRequest;
use App\Http\Requests\Admin\UpdatePortfolioRequest;
use App\Models\Category;
use App\Models\Media;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    use AppliesSeoFallbacks;

    public function __construct(private readonly MediaService $mediaService)
    {
        $this->authorizeResource(PortfolioItem::class, 'portfolio');
    }

    public function index(Request $request): View
    {
        $query = PortfolioItem::with('service', 'categories');

        if ($categoryId = $request->input('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $categoryId));
        }

        if ($serviceId = $request->input('service')) {
            $query->where('service_id', $serviceId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title->id', 'like', "%{$search}%")
                    ->orWhere('title->en', 'like', "%{$search}%")
                    ->orWhere('slug->id', 'like', "%{$search}%")
                    ->orWhere('slug->en', 'like', "%{$search}%")
                    ->orWhere('client', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::byType('portfolio')->get()
            ->sortBy(fn (Category $category) => (string) $category->name, SORT_NATURAL | SORT_FLAG_CASE);
        $services = Service::orderBy('id')->get()
            ->sortBy(fn (Service $service) => (string) $service->title, SORT_NATURAL | SORT_FLAG_CASE);

        return view('admin.portfolio.index', compact('items', 'categories', 'services'));
    }

    public function create(): View
    {
        $services = Service::orderBy('id')->get()
            ->sortBy(fn (Service $service) => (string) $service->title, SORT_NATURAL | SORT_FLAG_CASE)
            ->pluck('title', 'id');
        $categories = Category::byType('portfolio')->get()
            ->sortBy(fn (Category $category) => (string) $category->name, SORT_NATURAL | SORT_FLAG_CASE)
            ->pluck('name', 'id');

        return view('admin.portfolio.create', compact('services', 'categories'));
    }

    public function store(StorePortfolioRequest $request): RedirectResponse
    {
        $validated = $this->applySeoFallbacks($request->validated(), 'description', 'photo', 'photo_alt');
        $portfolio = PortfolioItem::create($validated);

        if (($categoryIds = $request->input('category_ids')) !== null) {
            $portfolio->categories()->sync($categoryIds);
        }

        $this->syncGalleryMedia($portfolio, $request->input('gallery_media_ids'));

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
        $services = Service::orderBy('id')->get()
            ->sortBy(fn (Service $service) => (string) $service->title, SORT_NATURAL | SORT_FLAG_CASE)
            ->pluck('title', 'id');
        $categories = Category::byType('portfolio')->get()
            ->sortBy(fn (Category $category) => (string) $category->name, SORT_NATURAL | SORT_FLAG_CASE)
            ->pluck('name', 'id');

        return view('admin.portfolio.edit', compact('portfolio', 'services', 'categories'));
    }

    public function update(UpdatePortfolioRequest $request, PortfolioItem $portfolio): RedirectResponse
    {
        $previousImages = [$portfolio->photo, $portfolio->og_image];
        $validated = $this->applySeoFallbacks($request->validated(), 'description', 'photo', 'photo_alt');

        $portfolio->update($validated);

        foreach ($previousImages as $previous) {
            if ($previous !== null && ! in_array($previous, [$portfolio->photo, $portfolio->og_image], true)) {
                $this->mediaService->deleteStoredUpload($previous);
            }
        }

        if ($request->has('category_ids')) {
            $portfolio->categories()->sync($request->input('category_ids') ?? []);
        } else {
            $portfolio->categories()->sync([]);
        }

        $this->syncGalleryMedia($portfolio, $request->input('gallery_media_ids'));

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

        if ($media->mediable_id !== null && $media->mediable_id !== $portfolio->id) {
            return response()->json(['success' => false, 'message' => 'Media ini sudah terpasang pada item lain.'], 422);
        }

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

        $previousPhoto = $portfolio->photo;

        $portfolio->update([
            'photo' => $medium->url,
            'photo_alt' => $medium->alt_text,
        ]);

        if ($previousPhoto !== null && $previousPhoto !== $portfolio->photo) {
            $this->mediaService->deleteStoredUpload($previousPhoto);
        }

        $medium->update([
            'mediable_type' => null,
            'mediable_id' => null,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Sync the portfolio gallery with the selected media ids. Attaches previously
     * unassigned media and detaches media that are no longer part of the gallery,
     * never stealing media already attached to another item.
     *
     * @param  string|null  $galleryIds  Comma separated media ids from the gallery picker.
     */
    private function syncGalleryMedia(PortfolioItem $portfolio, ?string $galleryIds): void
    {
        $ids = collect(explode(',', (string) $galleryIds))
            ->map(fn ($id) => (int) trim($id))
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->take(4)
            ->values();

        if ($ids->isEmpty()) {
            $portfolio->media()->update([
                'mediable_type' => null,
                'mediable_id' => null,
            ]);

            return;
        }

        DB::transaction(function () use ($portfolio, $ids) {
            $portfolio->media()
                ->whereNotIn('id', $ids)
                ->update([
                    'mediable_type' => null,
                    'mediable_id' => null,
                ]);

            Media::query()
                ->whereIn('id', $ids)
                ->where(fn ($query) => $query->whereNull('mediable_id')->orWhere('mediable_id', $portfolio->id))
                ->update([
                    'mediable_type' => PortfolioItem::class,
                    'mediable_id' => $portfolio->id,
                ]);
        });
    }
}
