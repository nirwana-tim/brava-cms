<?php

use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('media url is always host independent', function () {
    $media = Media::factory()->create(['path' => 'media/cover.jpg']);

    expect($media->url)->toBe('/storage/media/cover.jpg');
});

test('media absolute url uses current request host', function () {
    $media = Media::factory()->create(['path' => 'media/cover.jpg']);

    $this->get('/some-page');

    expect($media->absolute_url)->toBe(request()->getSchemeAndHttpHost().'/storage/media/cover.jpg');
});

test('media absolute url keeps already absolute value', function () {
    $media = Media::factory()->create(['path' => 'media/cover.jpg']);

    $this->get('/some-page');

    $absolute = $media->absolute_url;

    expect($absolute)->toMatch('/^https?:\/\//');
});
