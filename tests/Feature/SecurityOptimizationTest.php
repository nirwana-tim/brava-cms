<?php

use App\Enums\UserRole;
use App\Models\Media;
use App\Models\User;
use App\Services\BlogService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('api per_page parameter is clamped to prevent dos attacks', function () {
    $service = app(BlogService::class);
    $paginator = $service->list(['per_page' => '999999']);

    expect($paginator->perPage())->toBe(100);

    $paginatorMin = $service->list(['per_page' => '-10']);
    expect($paginatorMin->perPage())->toBe(1);
});

test('media picker list is limited to 60 items to prevent memory exhaustion', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    Media::factory()->count(65)->create();

    $response = $this->actingAs($admin)->getJson('/admin/media/picker-list');

    $response->assertStatus(200);
    expect(count($response->json()))->toBe(60);
});

test('media picker list filters by collection', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $blogMedia = Media::factory()->create(['collection' => 'blogs']);
    Media::factory()->create(['collection' => 'portfolio']);
    Media::factory()->create(['collection' => null]);

    $response = $this->actingAs($admin)->getJson('/admin/media/picker-list?collection=blogs');

    $response->assertStatus(200);
    expect(count($response->json()))->toBe(1)
        ->and($response->json('0.id'))->toBe($blogMedia->id);
});
