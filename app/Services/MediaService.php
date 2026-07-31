<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class MediaService
{
    private const DOCUMENT_EXTENSIONS = [
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    ];

    public function storeWithCompression(UploadedFile $file, string $directory, string $disk): string
    {
        if (! str_starts_with($file->getMimeType(), 'image/')) {
            return $this->storeDocument($file, $directory, $disk);
        }

        return $this->storeImage($file, $directory, $disk);
    }

    private function storeImage(UploadedFile $file, string $directory, string $disk): string
    {
        $image = app(ImageManager::class)->decodeSplFileInfo($file);
        $image->scaleDown(width: 1920);

        $encoded = match ($file->getMimeType()) {
            'image/webp' => $image->encodeUsingFormat(Format::WEBP, quality: 85),
            'image/png' => $image->encodeUsingFormat(Format::PNG),
            'image/gif' => $image->encodeUsingFormat(Format::GIF),
            default => $image->encodeUsingFormat(Format::JPEG, quality: 85),
        };

        $extension = match ($file->getMimeType()) {
            'image/webp' => 'webp',
            'image/png' => 'png',
            'image/gif' => 'gif',
            default => 'jpg',
        };

        $path = $directory.'/'.$this->storedName($file, $extension);
        Storage::disk($disk)->put($path, (string) $encoded);

        return $path;
    }

    private function storeDocument(UploadedFile $file, string $directory, string $disk): string
    {
        $extension = self::DOCUMENT_EXTENSIONS[$file->getMimeType()] ?? 'bin';
        $path = $directory.'/'.$this->storedName($file, $extension);
        Storage::disk($disk)->put($path, $file->getContent());

        return $path;
    }

    private function storedName(UploadedFile $file, string $extension): string
    {
        $filename = str(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))->slug()->toString();

        return $filename.'-'.uniqid().'.'.$extension;
    }
}
