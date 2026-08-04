<?php

use App\Models\Blog;
use App\Models\Media;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('normalize urls rewrites absolute localhost urls to relative', function () {
    $media = Media::factory()->create(['path' => 'media/cover.png']);

    $blog = Blog::factory()->create(['featured_image' => 'http://localhost:8000'.$media->url]);

    Artisan::call('media:normalize-urls');

    expect($blog->fresh()->featured_image)->toBe($media->url);
});

test('normalize urls leaves external and already relative urls untouched', function () {
    $external = 'https://cdn.example.com/images/banner.png';
    $relative = '/storage/media/cover.png';

    Blog::factory()->create(['featured_image' => $external]);
    Blog::factory()->create(['featured_image' => $relative]);

    Artisan::call('media:normalize-urls');

    expect(DB::table('blogs')->where('featured_image', $external)->exists())->toBeTrue();
    expect(DB::table('blogs')->where('featured_image', $relative)->exists())->toBeTrue();
});

test('normalize urls dry run does not modify data', function () {
    $media = Media::factory()->create(['path' => 'media/cover.png']);

    $blog = Blog::factory()->create(['featured_image' => 'http://localhost:8000'.$media->url]);

    Artisan::call('media:normalize-urls', ['--dry-run' => true]);

    expect($blog->fresh()->featured_image)->toBe('http://localhost:8000'.$media->url);
});

test('cleanup renames untracked referenced file and updates reference', function () {
    Storage::disk('public')->put('uploads/My Avatar File 123.png', 'content');

    $testimonial = Testimonial::factory()->create([
        'avatar' => 'http://localhost:8000/storage/uploads/My Avatar File 123.png',
    ]);

    Artisan::call('media:cleanup-filenames');

    expect(Storage::disk('public')->exists('uploads/my-avatar-file-123.png'))->toBeTrue();
    expect(Storage::disk('public')->missing('uploads/My Avatar File 123.png'))->toBeTrue();
    expect($testimonial->fresh()->avatar)->toContain('uploads/my-avatar-file-123.png');
});

test('remove orphans deletes unreferenced files but keeps referenced and registered', function () {
    Storage::disk('public')->put('uploads/orphan-1.png', 'content');

    $media = Media::factory()->create(['path' => 'uploads/registered.png']);
    Storage::disk('public')->put($media->path, 'content');

    Storage::disk('public')->put('uploads/referenced.png', 'content');
    Testimonial::factory()->create(['avatar' => '/storage/uploads/referenced.png']);

    Artisan::call('media:cleanup-filenames', ['--remove-orphans' => true]);

    expect(Storage::disk('public')->missing('uploads/orphan-1.png'))->toBeTrue();
    expect(Storage::disk('public')->exists('uploads/registered.png'))->toBeTrue();
    expect(Storage::disk('public')->exists('uploads/referenced.png'))->toBeTrue();
});

test('remove orphans dry run does not delete anything', function () {
    Storage::disk('public')->put('uploads/orphan-1.png', 'content');

    Artisan::call('media:cleanup-filenames', ['--remove-orphans' => true, '--dry-run' => true]);

    expect(Storage::disk('public')->exists('uploads/orphan-1.png'))->toBeTrue();
});
