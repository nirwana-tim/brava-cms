<?php

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('picker list returns all media when no collection filter is provided', function () {
    $user = User::factory()->create();

    Media::factory()->create(['name' => 'General Photo', 'collection' => 'general']);
    Media::factory()->create(['name' => 'Service Photo', 'collection' => 'services']);
    Media::factory()->create(['name' => 'Blog Photo', 'collection' => 'blogs']);

    $response = $this->actingAs($user)->getJson(route('admin.media.picker-list'));

    $response->assertOk();
    expect($response->json())->toHaveCount(3);
});

test('picker list filters by collection when specified', function () {
    $user = User::factory()->create();

    Media::factory()->create(['name' => 'General Photo', 'collection' => 'general']);
    Media::factory()->create(['name' => 'Service Photo', 'collection' => 'services']);

    $response = $this->actingAs($user)->getJson(route('admin.media.picker-list', ['collection' => 'services']));

    $response->assertOk();
    $data = $response->json();
    expect($data)->toHaveCount(1);
    expect($data[0]['name'])->toBe('Service Photo');
});

test('picker list searches by name or alt text', function () {
    $user = User::factory()->create();

    Media::factory()->create(['name' => 'Brava Default Uniform', 'alt_text' => 'Uniform image', 'collection' => 'general']);
    Media::factory()->create(['name' => 'Another File', 'alt_text' => 'Random alt', 'collection' => 'general']);

    $response = $this->actingAs($user)->getJson(route('admin.media.picker-list', ['search' => 'Brava']));

    $response->assertOk();
    $data = $response->json();
    expect($data)->toHaveCount(1);
    expect($data[0]['name'])->toBe('Brava Default Uniform');
});
