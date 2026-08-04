<?php

use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('deleteStoredUpload removes an existing relative upload file', function () {
    Storage::disk('public')->put('uploads/photo.jpg', 'content');

    app(MediaService::class)->deleteStoredUpload('/storage/uploads/photo.jpg');

    Storage::disk('public')->assertMissing('uploads/photo.jpg');
});

test('deleteStoredUpload handles absolute localhost urls', function () {
    Storage::disk('public')->put('uploads/photo.jpg', 'content');

    app(MediaService::class)->deleteStoredUpload('http://localhost:8000/storage/uploads/photo.jpg');

    Storage::disk('public')->assertMissing('uploads/photo.jpg');
});

test('deleteStoredUpload ignores media library paths and external urls', function () {
    Storage::disk('public')->put('media/library.jpg', 'content');

    app(MediaService::class)->deleteStoredUpload('/storage/media/library.jpg');
    app(MediaService::class)->deleteStoredUpload('https://cdn.example.com/photo.jpg');

    Storage::disk('public')->assertExists('media/library.jpg');
});

test('deleteStoredUpload is a no-op for null and empty values', function () {
    $service = app(MediaService::class);

    $service->deleteStoredUpload(null);
    $service->deleteStoredUpload('');
    $service->deleteStoredUpload('  ');

    expect(true)->toBeTrue();
});

test('deleteStoredUpload removes a bare uploads path', function () {
    Storage::disk('public')->put('uploads/photo.jpg', 'content');

    app(MediaService::class)->deleteStoredUpload('uploads/photo.jpg');

    Storage::disk('public')->assertMissing('uploads/photo.jpg');
});
