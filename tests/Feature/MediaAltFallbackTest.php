<?php

use App\Http\Resources\BlogResource;
use App\Http\Resources\PromoResource;
use App\Models\Blog;
use App\Models\Media;
use App\Models\Promo;
use App\Services\MediaUsageService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

afterEach(fn () => MediaUsageService::flushAltCache());

test('empty content alt falls back to media alt', function () {
    $media = Media::factory()->create(['path' => 'media/cover.png', 'alt_text' => 'Foto cover seragam kantor']);

    $blog = Blog::factory()->create([
        'featured_image' => $media->url,
        'featured_image_alt' => null,
    ]);

    $data = (new BlogResource($blog))->resolve();

    expect($data['featured_image_alt'])->toBe('Foto cover seragam kantor');
    expect($data['seo']['og_image_alt'])->toBe('Foto cover seragam kantor');
});

test('content alt overrides media alt', function () {
    $media = Media::factory()->create(['path' => 'media/cover.png', 'alt_text' => 'Alt default media']);

    $blog = Blog::factory()->create([
        'featured_image' => $media->url,
        'featured_image_alt' => 'Alt khusus untuk blog ini',
    ]);

    $data = (new BlogResource($blog))->resolve();

    expect($data['featured_image_alt'])->toBe('Alt khusus untuk blog ini');
});

test('editing media alt propagates to content with empty alt', function () {
    $media = Media::factory()->create(['path' => 'media/cover.png', 'alt_text' => 'Alt lama']);
    $blog = Blog::factory()->create(['featured_image' => $media->url, 'featured_image_alt' => null]);

    $media->update(['alt_text' => 'Alt baru hasil edit']);
    MediaUsageService::flushAltCache();

    $data = (new BlogResource($blog))->resolve();

    expect($data['featured_image_alt'])->toBe('Alt baru hasil edit');
});

test('promo image alt falls back to media alt then title', function () {
    $media = Media::factory()->create(['path' => 'media/banner.png', 'alt_text' => 'Banner promo seragam']);

    $promo = Promo::factory()->create([
        'title' => 'Promo Seragam 2026',
        'image' => $media->url,
        'image_alt' => null,
    ]);

    $data = (new PromoResource($promo))->resolve();

    expect($data['image_alt'])->toBe('Banner promo seragam');
});

test('promo image alt without media falls back to title', function () {
    $promo = Promo::factory()->create([
        'title' => 'Promo Seragam 2026',
        'image' => '/storage/uploads/unregistered-banner.png',
        'image_alt' => null,
    ]);

    $data = (new PromoResource($promo))->resolve();

    expect($data['image_alt'])->toBe('Promo Seragam 2026');
});
