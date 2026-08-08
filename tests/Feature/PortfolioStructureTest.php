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

test('portfolio admin can store localized specifications and features', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $service = Service::factory()->create();

    $response = $this->actingAs($user)->post('/admin/portfolio', [
        'service_id' => $service->id,
        'title' => 'Seragam PDH',
        'slug' => 'seragam-pdh',
        'photo' => '/storage/portfolio/cover.jpg',
        'description' => 'Produksi seragam PDH dengan bordir logo.',
        'specifications' => [
            'id' => [
                ['key' => 'Material', 'value' => 'Lacoste CVC'],
                ['key' => 'Teknik Logo', 'value' => 'Bordir'],
            ],
            'en' => [
                ['key' => 'Material', 'value' => 'Lacoste CVC'],
            ],
        ],
        'features' => [
            'id' => ['Nyaman digunakan', 'Warna tahan lama'],
            'en' => ['Comfortable to use'],
        ],
    ]);

    $response->assertRedirect();

    $portfolio = PortfolioItem::where('slug->id', 'seragam-pdh')->orWhere('slug->en', 'seragam-pdh')->first();

    expect($portfolio)->not->toBeNull()
        ->and($portfolio->specifications)->toBe([
            'id' => [
                ['key' => 'Material', 'value' => 'Lacoste CVC'],
                ['key' => 'Teknik Logo', 'value' => 'Bordir'],
            ],
            'en' => [
                ['key' => 'Material', 'value' => 'Lacoste CVC'],
            ],
        ])
        ->and($portfolio->features)->toBe([
            'id' => ['Nyaman digunakan', 'Warna tahan lama'],
            'en' => ['Comfortable to use'],
        ])
        ->and($portfolio->specificationsFor('id'))->toBe([
            ['key' => 'Material', 'value' => 'Lacoste CVC'],
            ['key' => 'Teknik Logo', 'value' => 'Bordir'],
        ])
        ->and($portfolio->specificationsFor('en'))->toBe([
            ['key' => 'Material', 'value' => 'Lacoste CVC'],
        ])
        ->and($portfolio->featuresFor('id'))->toBe(['Nyaman digunakan', 'Warna tahan lama'])
        ->and($portfolio->featuresFor('en'))->toBe(['Comfortable to use']);
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
            'id' => [
                ['key' => '', 'value' => 'Lacoste CVC'],
            ],
        ],
    ]);

    $response->assertSessionHasErrors('specifications.id.0.key');
});

test('portfolio api returns localized specifications and features instead of content', function () {
    $portfolio = PortfolioItem::factory()->create([
        'specifications' => [
            'id' => [
                ['key' => 'Material', 'value' => 'Drill'],
            ],
            'en' => [
                ['key' => 'Material', 'value' => 'Drill'],
                ['key' => 'Logo Technique', 'value' => 'Embroidery'],
            ],
        ],
        'features' => [
            'id' => ['Adem dipakai'],
            'en' => ['Comfortable to wear'],
        ],
    ]);

    $response = $this->getJson('/api/v1/portfolio/'.$portfolio->slug.'?lang=en');

    $response->assertOk();
    $data = $response->json('data');

    expect($data['specifications'])->toBe([
        ['key' => 'Material', 'value' => 'Drill'],
        ['key' => 'Logo Technique', 'value' => 'Embroidery'],
    ])
        ->and($data['features'])->toBe(['Comfortable to wear'])
        ->and($data)->not->toHaveKey('content');
});

test('portfolio api falls back to indonesian lists when english is empty', function () {
    $portfolio = PortfolioItem::factory()->create([
        'specifications' => [
            'id' => [
                ['key' => 'Material', 'value' => 'Drill'],
            ],
        ],
        'features' => [
            'id' => ['Adem dipakai'],
        ],
    ]);

    $response = $this->getJson('/api/v1/portfolio/'.$portfolio->slug.'?lang=en');

    $response->assertOk();
    $data = $response->json('data');

    expect($data['specifications'])->toBe([
        ['key' => 'Material', 'value' => 'Drill'],
    ])
        ->and($data['features'])->toBe(['Adem dipakai']);
});

test('portfolio api returns empty arrays when specs and features are null', function () {
    $portfolio = PortfolioItem::factory()->create([
        'specifications' => null,
        'features' => null,
    ]);

    $response = $this->getJson('/api/v1/portfolio/'.$portfolio->slug);

    $response->assertOk();

    expect($response->json('data.specifications'))->toBe([])
        ->and($response->json('data.features'))->toBe([]);
});

test('portfolio api still serves legacy flat lists', function () {
    $portfolio = PortfolioItem::factory()->create([
        'specifications' => [
            ['key' => 'Material', 'value' => 'Drill'],
        ],
        'features' => ['Adem dipakai'],
    ]);

    $response = $this->getJson('/api/v1/portfolio/'.$portfolio->slug);

    $response->assertOk();

    expect($response->json('data.specifications'))->toBe([
        ['key' => 'Material', 'value' => 'Drill'],
    ])
        ->and($response->json('data.features'))->toBe(['Adem dipakai']);
});
