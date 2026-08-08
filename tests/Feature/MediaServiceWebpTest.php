<?php

use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('jpeg uploads are re-encoded as webp', function () {
    $file = UploadedFile::fake()->image('photo.jpg', 100, 60);

    $path = app(MediaService::class)->storeWithCompression($file, 'media', 'public');

    expect($path)->toEndWith('.webp')
        ->and(Storage::disk('public')->exists($path))->toBeTrue()
        ->and(Storage::disk('public')->get($path))->toStartWith('RIFF')->toContain('WEBP');
});

test('png uploads are re-encoded as webp', function () {
    $file = UploadedFile::fake()->image('logo.png', 120, 120);

    $path = app(MediaService::class)->storeWithCompression($file, 'media', 'public');

    expect($path)->toEndWith('.webp')
        ->and(Storage::disk('public')->exists($path))->toBeTrue()
        ->and(Storage::disk('public')->get($path))->toStartWith('RIFF')->toContain('WEBP');
});

test('gif uploads keep their animated format', function () {
    $file = UploadedFile::fake()->image('anim.gif', 80, 80);

    $path = app(MediaService::class)->storeWithCompression($file, 'media', 'public');

    expect($path)->toEndWith('.gif')
        ->and(Storage::disk('public')->exists($path))->toBeTrue()
        ->and(Storage::disk('public')->get($path))->toStartWith('GIF');
});

test('webp uploads are kept as webp', function () {
    $file = UploadedFile::fake()->image('preview.webp', 90, 90);

    $path = app(MediaService::class)->storeWithCompression($file, 'media', 'public');

    expect($path)->toEndWith('.webp')
        ->and(Storage::disk('public')->exists($path))->toBeTrue()
        ->and(Storage::disk('public')->get($path))->toStartWith('RIFF')->toContain('WEBP');
});
