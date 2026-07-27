<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        $media = Media::latest()->paginate(30);

        return view('admin.media.index', compact('media'));
    }

    public function create(): View
    {
        return view('admin.media.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf,doc,docx', 'max:10240'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'collection' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

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

    public function edit(Media $media): View
    {
        return view('admin.media.edit', compact('media'));
    }

    public function update(Request $request, Media $media): RedirectResponse
    {
        $validated = $request->validate([
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $media->update($validated);

        return redirect()->route('admin.media.index')
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();

        return redirect()->route('admin.media.index')
            ->with('success', 'Media deleted successfully.');
    }
}
