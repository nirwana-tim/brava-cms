<?php

use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::store('api')->flush();
    Cache::flush();
});

test('portfolio admin can store specifications and features', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $service = Service::factory()->create();

    $response = $this->actingAs($user)->post('/admin/portfolio', [
        'service_id' => $service->id,
        'title' => 'Seragam PDH',
        'slug' => 'seragam-pdh',
        'photo' => '/storage/portfolio/cover.jpg',
        'description' => 'Produksi seragam PDH dengan bordir logo.',
        'specifications' => [
            ['key' => 'Material', 'value' => 'Lacoste CVC'],
            ['key' => 'Teknik Logo', 'value' => 'Bordir'],
        ],
        'features' => [
            'Nyaman digunakan',
            'Warna tahan lama',
        ],
    ]);

    $response->assertRedirect();

    $portfolio = PortfolioItem::where('slug->id', 'seragam-pdh')->orWhere('slug->en', 'seragam-pdh')->first();

    expect($portfolio)->not->toBeNull()
        ->and($portfolio->specifications)->toBe([
            ['key' => 'Material', 'value' => 'Lacoste CVC'],
            ['key' => 'Teknik Logo', 'value' => 'Bordir'],
        ])
        ->and($portfolio->features)->toBe(['Nyaman digunakan', 'Warna tahan lama']);
});

test('portfolio specifications require key and value', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $service = Service::factory()->create();

    $response = $this->actingAs($user)->post('/admin/portfolio', [
        'service_id' => $service->id,
        'title' => 'Invalid Specs',
        'slug' => 'invalid-specs',
        'photo' => '/storage/portfolio/cover.jpg',
        'specifications' => [
            ['key' => '', 'value' => 'Lacoste CVC'],
        ],
    ]);

    $response->assertSessionHasErrors('specifications.0.key');
});

test('portfolio api returns specifications and features instead of content', function () {
    $portfolio = PortfolioItem::factory()->create([
        'specifications' => [
            ['key' => 'Material', 'value' => 'Drill'],
        ],
        'features' => ['Adem dipakai'],
    ]);

    $response = $this->getJson('/api/portfolio/'.$portfolio->slug);

    $response->assertOk();
    $data = $response->json('data');

    expect($data['specifications'])->toBe([
        ['key' => 'Material', 'value' => 'Drill'],
    ])
        ->and($data['features'])->toBe(['Adem dipakai'])
        ->and($data)->not->toHaveKey('content');
});

test('portfolio api returns empty arrays when specs and features are null', function () {
    $portfolio = PortfolioItem::factory()->create([
        'specifications' => null,
        'features' => null,
    ]);

    $response = $this->getJson('/api/portfolio/'.$portfolio->slug);

    $response->assertOk();

    expect($response->json('data.specifications'))->toBe([])
        ->and($response->json('data.features'))->toBe([]);
});
