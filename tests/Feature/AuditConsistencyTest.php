<?php

use App\Enums\PostStatus;
use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::store('api')->flush();
    Cache::flush();
});

test('blog api featured filter returns only featured posts', function () {
    $featured = Blog::factory()->create(['is_featured' => true, 'status' => PostStatus::Published]);
    Blog::factory()->create(['is_featured' => false, 'status' => PostStatus::Published]);

    $response = $this->getJson('/api/blogs?featured=1');

    $response->assertOk();
    $ids = collect($response->json('data'))->pluck('id');
    expect($ids)->toContain($featured->id)->toHaveCount(1);
});

test('portfolio gallery limit rejects more than 4 media ids', function () {
    $admin = User::factory()->create(['role' => UserRole::SuperAdmin]);
    $service = Service::factory()->create();

    $response = $this->actingAs($admin)->post('/admin/portfolio', [
        'service_id' => $service->id,
        'title' => 'Proyek Audit',
        'slug' => 'proyek-audit',
        'photo' => '/storage/portfolio/cover.jpg',
        'gallery_media_ids' => '1,2,3,4,5',
    ]);

    $response->assertSessionHasErrors('gallery_media_ids');
    expect(PortfolioItem::where('slug->id', 'proyek-audit')->exists())->toBeFalse();
});

test('portfolio gallery accepts up to 4 media ids', function () {
    $admin = User::factory()->create(['role' => UserRole::SuperAdmin]);
    $service = Service::factory()->create();

    $response = $this->actingAs($admin)->post('/admin/portfolio', [
        'service_id' => $service->id,
        'title' => 'Proyek Valid',
        'slug' => 'proyek-valid',
        'photo' => '/storage/portfolio/cover.jpg',
        'gallery_media_ids' => '1,2,3,4',
    ]);

    $response->assertSessionDoesntHaveErrors();
});

test('clear-stale-highlights command clears highlights that became expired or inactive', function () {
    $expired = Promo::factory()->highlighted()->create(['valid_until' => now()->addDay()]);
    $inactive = Promo::factory()->highlighted()->create();

    Promo::where('id', $expired->id)->update(['valid_until' => now()->subDay()]);
    Promo::where('id', $inactive->id)->update(['is_active' => false]);

    $active = Promo::factory()->highlighted()->create();

    $this->artisan('promos:clear-stale-highlights')->assertSuccessful();

    expect($expired->fresh()->is_highlighted)->toBeFalse()
        ->and($inactive->fresh()->is_highlighted)->toBeFalse()
        ->and($active->fresh()->is_highlighted)->toBeTrue();
});
