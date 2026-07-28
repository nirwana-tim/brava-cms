<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaRequest;
use App\Http\Requests\Admin\UpdateMediaRequest;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Media::class, 'medium');
    }

    public function index(Request $request): View
    {
        $query = Media::latest();

        if ($collection = $request->get('collection')) {
            $query->where('collection', $collection);
        }

        $media = $query->paginate(30);

        $collections = Media::whereNotNull('collection')
            ->selectRaw('collection, count(*) as total')
            ->groupBy('collection')
            ->orderBy('collection')
            ->pluck('total', 'collection');

        return view('admin.media.index', compact('media', 'collections'));
    }

    public function pickerList(): JsonResponse
    {
        $media = Media::latest()->get()->map(fn ($item) => [
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
        $path = $this->storeWithCompression($file, 'media', 'public');

        Media::create([
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'disk' => 'public',
            'path' => $path,
            'alt_text' => $request->alt_text,
            'collection' => $request->collection,
        ]);

        return redirect()->route('admin.media.index')
            ->with('success', 'Media uploaded successfully.');
    }

    public function uploadAjax(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:10240'],
            'collection' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $file = $request->file('file');
            $path = $this->storeWithCompression($file, 'media', 'public');

            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $media = Media::create([
                'name' => $name,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
                'path' => $path,
                'alt_text' => str_replace(['-', '_'], ' ', $name),
                'collection' => $request->collection,
            ]);

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

    private function storeWithCompression($file, string $directory, string $disk): string
    {
        if (! str_starts_with($file->getMimeType(), 'image/')) {
            return $file->store($directory, $disk);
        }

        $manager = app(ImageManager::class);
        $image = $manager->decodeSplFileInfo($file);

        $image->scaleDown(width: 1920);

        $encoded = match ($file->getMimeType()) {
            'image/webp' => $image->encodeUsingFormat(Format::WEBP, quality: 85),
            'image/png' => $image->encodeUsingFormat(Format::PNG),
            'image/gif' => $image->encodeUsingFormat(Format::GIF),
            default => $image->encodeUsingFormat(Format::JPEG, quality: 85),
        };

        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = match ($file->getMimeType()) {
            'image/webp' => 'webp',
            'image/png' => 'png',
            'image/gif' => 'gif',
            default => 'jpg',
        };
        $storedName = $filename.'-'.uniqid().'.'.$extension;

        $path = $directory.'/'.$storedName;
        Storage::disk($disk)->put($path, (string) $encoded);

        return $path;
    }

    public function edit(Media $medium): View
    {
        return view('admin.media.edit', compact('medium'));
    }

    public function update(UpdateMediaRequest $request, Media $medium): RedirectResponse
    {
        $medium->update($request->validated());

        return redirect()->route('admin.media.index')
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return redirect()->route('admin.media.index')
            ->with('success', 'Media deleted successfully.');
    }
}
