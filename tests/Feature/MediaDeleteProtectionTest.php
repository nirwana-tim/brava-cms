<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Media;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\User;
use App\Services\MediaUsageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    $this->service = app(MediaUsageService::class);
});

test('upload file not referenced anywhere can be deleted', function () {
    Storage::disk('public')->put('uploads/free.png', 'content');

    $response = $this->actingAs($this->admin)->deleteJson('/admin/upload', ['path' => 'uploads/free.png']);

    $response->assertOk();
    Storage::disk('public')->assertMissing('uploads/free.png');
});

test('upload file referenced inside blog content cannot be deleted', function () {
    Storage::disk('public')->put('uploads/in-content.png', 'content');

    Blog::factory()->create([
        'title' => 'Cara Merawat Seragam',
        'content' => '<p>Gambar <img src="/storage/uploads/in-content.png" alt="x"></p>',
    ]);

    $response = $this->actingAs($this->admin)->deleteJson('/admin/upload', ['path' => 'uploads/in-content.png']);

    $response->assertStatus(422);
    $response->assertJsonPath('error', fn (string $error) => str_contains($error, 'Cara Merawat Seragam'));
    Storage::disk('public')->assertExists('uploads/in-content.png');
});

test('media not referenced anywhere can be deleted', function () {
    $media = Media::factory()->create(['path' => 'media/unused.png']);
    Storage::disk('public')->put($media->path, 'content');

    $response = $this->actingAs($this->admin)->delete("/admin/media/{$media->id}");

    $response->assertRedirect(route('admin.media.index'));
    expect(Media::find($media->id))->toBeNull();
    Storage::disk('public')->assertMissing($media->path);
});

test('media referenced by portfolio cannot be deleted', function () {
    $media = Media::factory()->create(['path' => 'media/portfolio.png']);
    Storage::disk('public')->put($media->path, 'content');

    PortfolioItem::factory()->create([
        'title' => 'Proyek Seragam Kantor',
        'photo' => $media->url,
    ]);

    $response = $this->actingAs($this->admin)->delete("/admin/media/{$media->id}");

    $response->assertRedirect();
    $response->assertSessionHasErrors('media');
    expect(Media::find($media->id))->not->toBeNull();
    Storage::disk('public')->assertExists($media->path);
});

test('media referenced inside blog content cannot be deleted', function () {
    $media = Media::factory()->create(['path' => 'media/in-content.png']);
    Storage::disk('public')->put($media->path, 'content');

    Blog::factory()->create([
        'title' => 'Cara Merawat Seragam',
        'content' => '<p>Lihat gambar <img src="'.$media->url.'" alt="x"> di sini.</p>',
    ]);

    $response = $this->actingAs($this->admin)->delete("/admin/media/{$media->id}");

    $response->assertSessionHasErrors('media');
    expect(Media::find($media->id))->not->toBeNull();
});

test('ajax delete of used media returns 422 with error message', function () {
    $media = Media::factory()->create(['path' => 'media/ajax-used.png']);
    Storage::disk('public')->put($media->path, 'content');

    Promo::factory()->create([
        'title' => 'Diskon Seragam 40%',
        'image' => $media->url,
    ]);

    $response = $this->actingAs($this->admin)
        ->deleteJson("/admin/media/{$media->id}");

    $response->assertStatus(422);
    $response->assertJsonPath('error', fn (string $error) => str_contains($error, 'Diskon Seragam 40%'));
    expect(Media::find($media->id))->not->toBeNull();
    Storage::disk('public')->assertExists($media->path);
});

test('markInUseBatch marks used and unused media correctly', function () {
    $used = Media::factory()->create(['path' => 'media/used.png']);
    $unused = Media::factory()->create(['path' => 'media/free.png']);

    Promo::factory()->create([
        'title' => 'Promo Tahunan',
        'image' => $used->url,
    ]);

    $media = Media::whereIn('id', [$used->id, $unused->id])->get();

    $this->service->markInUseBatch($media);

    $usedResult = $media->firstWhere('id', $used->id);
    $unusedResult = $media->firstWhere('id', $unused->id);

    expect($usedResult->in_use)->toBeTrue();
    expect($usedResult->usage)->toContain('Promo "Promo Tahunan"');
    expect($unusedResult->in_use)->toBeFalse();
    expect($unusedResult->usage)->toBe([]);
});
