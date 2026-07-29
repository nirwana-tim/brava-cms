<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class UploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:10240'],
        ]);

        try {
            $file = $request->file('file');
            $path = $this->storeWithCompression($file, 'uploads', 'public');

            $url = Storage::disk('public')->url($path);

            return response()->json([
                'url' => $url,
                'path' => $path,
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
}
