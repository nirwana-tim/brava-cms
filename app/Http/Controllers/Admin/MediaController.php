<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaRequest;
use App\Http\Requests\Admin\UpdateMediaRequest;
use App\Models\Media;
use App\Services\MediaService;
use App\Services\MediaUsageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(
        private readonly MediaService $mediaService,
        private readonly MediaUsageService $mediaUsageService,
    ) {
        $this->authorizeResource(Media::class, 'medium');
    }

    public function index(Request $request): View
    {
        $query = Media::latest();

        if ($collection = $request->get('collection')) {
            $query->where('collection', $collection);
        }

        $media = $query->paginate(30);

        $this->mediaUsageService->markInUseBatch($media->getCollection());

        $collections = Media::whereNotNull('collection')
            ->selectRaw('collection, count(*) as total')
            ->groupBy('collection')
            ->orderBy('collection')
            ->pluck('total', 'collection');

        return view('admin.media.index', compact('media', 'collections'));
    }

    public function pickerList(): JsonResponse
    {
        $this->authorize('viewAny', Media::class);

        $media = Media::latest()->limit(60)->get()->map(fn ($item) => [
            'id' => $item->id,
            'url' => $item->url,
            'name' => $item->name,
            'alt_text' => $item->alt_text,
            'mime_type' => $item->mime_type,
            'size' => number_format($item->size / 1024, 1).' KB',
            'is_image' => str_starts_with($item->mime_type, 'image/'),
        ]);

        return response()->json($media);
    }

    public function create(): View
    {
        return view('admin.media.create');
    }

    public function store(StoreMediaRequest $request): RedirectResponse
    {
        $file = $request->file('file');

        try {
            $path = $this->mediaService->storeWithCompression($file, 'media', 'public');
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['file' => $e->getMessage()])->withInput();
        }

        $mimeType = Storage::disk('public')->mimeType($path);
        $size = Storage::disk('public')->size($path);

        try {
            Media::create([
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $mimeType,
                'size' => $size,
                'disk' => 'public',
                'path' => $path,
                'alt_text' => $request->alt_text,
                'collection' => $request->collection,
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);

            throw $e;
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'Media uploaded successfully.');
    }

    public function uploadAjax(Request $request): JsonResponse
    {
        $this->authorize('create', Media::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:10240'],
            'collection' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $file = $request->file('file');
            $path = $this->mediaService->storeWithCompression($file, 'media', 'public');

            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $mimeType = Storage::disk('public')->mimeType($path);
            $size = Storage::disk('public')->size($path);

            try {
                $media = Media::create([
                    'name' => $name,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $mimeType,
                    'size' => $size,
                    'disk' => 'public',
                    'path' => $path,
                    'alt_text' => $request->input('alt_text') ?: str_replace(['-', '_'], ' ', $name),
                    'collection' => $request->collection,
                ]);
            } catch (\Throwable $e) {
                Storage::disk('public')->delete($path);

                throw $e;
            }

            return response()->json([
                'id' => $media->id,
                'url' => $media->url,
                'name' => $media->name,
                'alt_text' => $media->alt_text,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Upload failed.'], 422);
        }
    }

    public function edit(Media $medium): View
    {
        return view('admin.media.edit', [
            'medium' => $medium,
            'usage' => $this->mediaUsageService->usageSummary($medium),
        ]);
    }

    public function update(UpdateMediaRequest $request, Media $medium): RedirectResponse
    {
        $medium->update($request->validated());

        return redirect()->route('admin.media.index')
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(Request $request, Media $medium)
    {
        $usage = $this->mediaUsageService->usageSummary($medium);

        if ($usage !== []) {
            $message = 'Media sedang dipakai di: '.implode(', ', $usage).'. Lepas referensinya dari konten tersebut sebelum menghapus.';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => $message], 422);
            }

            return back()->withErrors(['media' => $message]);
        }

        $medium->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'Media deleted successfully.');
    }
}
