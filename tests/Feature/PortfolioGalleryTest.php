<?php

use App\Enums\UserRole;
use App\Models\Media;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function adminUpdatePayload(PortfolioItem $portfolio, array $overrides = []): array
{
    $portfolio->refresh();

    $title = is_array($portfolio->title) ? $portfolio->title : (array) $portfolio->title;
    $slug = is_array($portfolio->slug) ? $portfolio->slug : (array) $portfolio->slug;

    return array_merge([
        'service_id' => $portfolio->service_id,
        'title' => ['id' => $title['id'] ?? reset($title), 'en' => $title['en'] ?? null],
        'slug' => ['id' => $slug['id'] ?? reset($slug), 'en' => $slug['en'] ?? null],
        'photo' => $portfolio->photo,
    ], $overrides);
}

test('portfolio update attaches free gallery media selected by the picker', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $portfolio = PortfolioItem::factory()->create(['service_id' => $service->id]);
    $free = Media::factory()->create(['mediable_id' => null, 'mediable_type' => null]);

    $this->actingAs($admin)->put(
        route('admin.portfolio.update', $portfolio),
        adminUpdatePayload($portfolio, ['gallery_media_ids' => (string) $free->id])
    )->assertRedirect();

    expect($free->fresh()->mediable_id)->toBe($portfolio->id)
        ->and($free->fresh()->mediable_type)->toBe(PortfolioItem::class);
});

test('portfolio update does not steal media attached to another item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $portfolio = PortfolioItem::factory()->create(['service_id' => $service->id]);
    $other = PortfolioItem::factory()->create(['service_id' => $service->id]);
    $attached = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $other->id,
    ]);

    $this->actingAs($admin)->put(
        route('admin.portfolio.update', $portfolio),
        adminUpdatePayload($portfolio, ['gallery_media_ids' => (string) $attached->id])
    )->assertRedirect();

    expect($attached->fresh()->mediable_id)->toBe($other->id);
});

test('portfolio update detaches gallery media no longer part of the list', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $portfolio = PortfolioItem::factory()->create(['service_id' => $service->id]);
    $kept = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $portfolio->id,
    ]);
    $removed = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $portfolio->id,
    ]);

    $this->actingAs($admin)->put(
        route('admin.portfolio.update', $portfolio),
        adminUpdatePayload($portfolio, ['gallery_media_ids' => (string) $kept->id])
    )->assertRedirect();

    expect($kept->fresh()->mediable_id)->toBe($portfolio->id)
        ->and($removed->fresh()->mediable_id)->toBeNull()
        ->and($removed->fresh()->mediable_type)->toBeNull();
});

test('portfolio update with empty gallery detaches all photos', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $portfolio = PortfolioItem::factory()->create(['service_id' => $service->id]);
    $photo = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $portfolio->id,
    ]);

    $this->actingAs($admin)->put(
        route('admin.portfolio.update', $portfolio),
        adminUpdatePayload($portfolio, ['gallery_media_ids' => ''])
    )->assertRedirect();

    expect($photo->fresh()->mediable_id)->toBeNull();
});

test('portfolio store attaches gallery media selected by the picker', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $media1 = Media::factory()->create(['mediable_id' => null, 'mediable_type' => null]);
    $media2 = Media::factory()->create(['mediable_id' => null, 'mediable_type' => null]);

    $response = $this->actingAs($admin)->post(route('admin.portfolio.store'), [
        'service_id' => $service->id,
        'title' => ['id' => 'Proyek Baru', 'en' => 'New Project'],
        'slug' => ['id' => 'proyek-baru', 'en' => 'new-project'],
        'photo' => 'https://example.com/cover.jpg',
        'gallery_media_ids' => "{$media1->id},{$media2->id}",
    ]);

    $response->assertRedirect(route('admin.portfolio.index'));

    $portfolio = PortfolioItem::where('slug->id', 'proyek-baru')->firstOrFail();
    expect($media1->fresh()->mediable_id)->toBe($portfolio->id)
        ->and($media1->fresh()->mediable_type)->toBe(PortfolioItem::class)
        ->and($media2->fresh()->mediable_id)->toBe($portfolio->id)
        ->and($media2->fresh()->mediable_type)->toBe(PortfolioItem::class);
});

test('portfolio create and edit views render gallery picker successfully', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $portfolio = PortfolioItem::factory()->create(['service_id' => $service->id]);
    $galleryMedia = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $portfolio->id,
    ]);

    $this->actingAs($admin)->get(route('admin.portfolio.create'))
        ->assertOk()
        ->assertSee('Gallery Photos')
        ->assertSee('Choose from Media')
        ->assertSee('name="gallery_media_ids"', false);

    $this->actingAs($admin)->get(route('admin.portfolio.edit', $portfolio))
        ->assertOk()
        ->assertSee('Gallery Photos')
        ->assertSee('Choose from Media')
        ->assertSee('name="gallery_media_ids"', false);
});

test('portfolio store rejects request without cover photo', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $media = Media::factory()->create(['mediable_id' => null, 'mediable_type' => null]);

    $response = $this->actingAs($admin)->post(route('admin.portfolio.store'), [
        'service_id' => $service->id,
        'title' => ['id' => 'Proyek Tanpa Cover', 'en' => 'Project Without Cover'],
        'slug' => ['id' => 'proyek-tanpa-cover', 'en' => 'project-without-cover'],
        'gallery_media_ids' => (string) $media->id,
    ]);

    $response->assertSessionHasErrors('photo');
});
