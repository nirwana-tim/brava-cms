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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    use AppliesSeoFallbacks;

    public function __construct()
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
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('client', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::byType('portfolio')->orderBy('name')->get();
        $services = Service::orderBy('title')->get();

        return view('admin.portfolio.index', compact('items', 'categories', 'services'));
    }

    public function create(): View
    {
        $services = Service::pluck('title', 'id');
        $categories = Category::byType('portfolio')->pluck('name', 'id');

        return view('admin.portfolio.create', compact('services', 'categories'));
    }

    public function store(StorePortfolioRequest $request): RedirectResponse
    {
        $validated = $this->applySeoFallbacks($request->validated(), 'description', 'photo', 'photo_alt');
        $portfolio = PortfolioItem::create($validated);

        if ($request->has('categories')) {
            $portfolio->categories()->sync($request->categories);
        }

        if ($galleryIds = $request->input('gallery_media_ids')) {
            $ids = collect(explode(',', $galleryIds))
                ->map(fn ($id) => (int) trim($id))
                ->filter(fn ($id) => $id > 0)
                ->unique()
                ->take(4);

            DB::transaction(function () use ($portfolio, $ids) {
                Media::query()
                    ->whereIn('id', $ids)
                    ->whereNull('mediable_id')
                    ->update([
                        'mediable_type' => PortfolioItem::class,
                        'mediable_id' => $portfolio->id,
                    ]);
            });
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
        $validated = $this->applySeoFallbacks($request->validated(), 'description', 'photo', 'photo_alt');
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

        $portfolio->update([
            'photo' => $medium->url,
            'photo_alt' => $medium->alt_text,
        ]);

        $medium->update([
            'mediable_type' => null,
            'mediable_id' => null,
        ]);

        return response()->json(['success' => true]);
    }
}
