<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function __construct(private readonly MediaService $mediaService) {}

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Media::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:10240'],
        ]);

        try {
            $file = $request->file('file');
            $path = $this->mediaService->storeWithCompression($file, 'uploads', 'public');

            $url = Storage::disk('public')->url($path);

            return response()->json([
                'url' => $url,
                'path' => $path,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Upload failed.'], 422);
        }
    }
}
