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

test('contact api rejects oversized messages over 5000 chars', function () {
    $response = $this->postJson('/api/contact', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => str_repeat('a', 5001),
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['message']);
});
