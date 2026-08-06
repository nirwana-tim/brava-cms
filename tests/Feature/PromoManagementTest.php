<?php

use App\Enums\UserRole;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

beforeEach(fn () => Cache::flush());

test('admin can view promos index and see highlight badge', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    Promo::factory()->highlighted()->create([
        'title' => '40% Diskon Seragam Perusahaan',
        'badge_text' => 'PROMO TERBATAS',
    ]);

    $response = $this->actingAs($admin)->get('/admin/promos');

    $response->assertStatus(200);
    $response->assertSee('40% Diskon Seragam Perusahaan');
    $response->assertSee('HERO BANNER');
});

test('single highlight guarantee automatically unhighlights previous promo', function () {
    $promoA = Promo::factory()->highlighted()->create(['title' => 'Promo A']);
    $promoB = Promo::factory()->create(['title' => 'Promo B', 'is_highlighted' => false]);

    expect($promoA->fresh()->is_highlighted)->toBeTrue();
    expect($promoB->fresh()->is_highlighted)->toBeFalse();

    // Set Promo B as highlighted
    $promoB->update(['is_highlighted' => true]);

    expect($promoA->fresh()->is_highlighted)->toBeFalse();
    expect($promoB->fresh()->is_highlighted)->toBeTrue();
});

test('cannot highlight inactive or expired promo', function () {
    $inactivePromo = Promo::factory()->inactive()->create();
    $expiredPromo = Promo::factory()->expired()->create();

    expect(fn () => $inactivePromo->update(['is_highlighted' => true]))
        ->toThrow(InvalidArgumentException::class, 'Promo yang non-aktif atau sudah kedaluwarsa tidak dapat dijadikan Highlight.');

    expect(fn () => $expiredPromo->update(['is_highlighted' => true]))
        ->toThrow(InvalidArgumentException::class, 'Promo yang non-aktif atau sudah kedaluwarsa tidak dapat dijadikan Highlight.');
});

test('api highlight endpoint returns active highlighted promo or falls back to latest active', function () {
    $promo = Promo::factory()->highlighted()->create([
        'title' => 'Hero Promo',
        'slug' => 'hero-promo',
    ]);

    $response = $this->getJson('/api/v1/promos/highlight');

    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Hero Promo')
        ->assertJsonPath('data.is_highlighted', true);
});

test('api highlight endpoint falls back to regular running promo if highlighted promo expires overnight', function () {
    $expiredHighlight = Promo::factory()->highlighted()->create([
        'title' => 'Expired Hero',
        'valid_until' => now()->addHour(),
    ]);

    // Simulate overnight expiration without triggering model saving rule
    Promo::withoutEvents(fn () => $expiredHighlight->update(['valid_until' => now()->subDay()]));

    $activeRunning = Promo::factory()->create([
        'title' => 'Active Regular Promo',
        'is_highlighted' => false,
    ]);

    $response = $this->getJson('/api/v1/promos/highlight');

    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Active Regular Promo');
});

test('api list endpoint excludes highlighted, inactive, and expired', function () {
    $highlight = Promo::factory()->highlighted()->create(['title' => 'Highlight Promo']);
    $regular1 = Promo::factory()->create(['title' => 'Regular Active 1', 'is_highlighted' => false]);
    $regular2 = Promo::factory()->create(['title' => 'Regular Active 2', 'is_highlighted' => false]);
    $expired = Promo::factory()->expired()->create(['title' => 'Expired Promo']);
    $inactive = Promo::factory()->inactive()->create(['title' => 'Inactive Promo']);

    $response = $this->getJson('/api/v1/promos');

    $response->assertStatus(200);
    $titles = collect($response->json('data'))->pluck('title')->toArray();

    expect($titles)->toContain('Regular Active 1', 'Regular Active 2');
    expect($titles)->not->toContain('Highlight Promo', 'Inactive Promo', 'Expired Promo');
});

test('api list endpoint returns state field and coming soon promo', function () {
    $highlight = Promo::factory()->create(['title' => 'Hero Bg', 'is_highlighted' => true]);
    $active = Promo::factory()->create(['title' => 'Active Promo', 'is_highlighted' => false]);
    $coming = Promo::factory()->comingSoon()->create(['title' => 'Coming Promo']);
    $expired = Promo::factory()->expired()->create(['title' => 'Expired Promo']);

    $response = $this->getJson('/api/v1/promos');
    $response->assertStatus(200);

    $data = collect($response->json('data'));
    $titles = $data->pluck('title')->toArray();

    expect($titles)->toContain('Active Promo', 'Coming Promo');
    expect($titles)->not->toContain('Hero Bg', 'Expired Promo');

    $activeItem = $data->firstWhere('title', 'Active Promo');
    expect($activeItem['state'])->toBe('active');
    expect($activeItem['is_coming_soon'])->toBeFalse();

    $comingItem = $data->firstWhere('title', 'Coming Promo');
    expect($comingItem['state'])->toBe('coming_soon');
    expect($comingItem['is_coming_soon'])->toBeTrue();
});

test('is_coming_soon accessor returns true when valid_from is in future', function () {
    $promo = Promo::factory()->comingSoon()->create();

    expect($promo->is_coming_soon)->toBeTrue();
    expect($promo->is_expired)->toBeFalse();

    $active = Promo::factory()->create();

    expect($active->is_coming_soon)->toBeFalse();
    expect($active->is_expired)->toBeFalse();

    $expired = Promo::factory()->expired()->create();

    expect($expired->is_coming_soon)->toBeFalse();
    expect($expired->is_expired)->toBeTrue();
});

test('wa_url attribute formats whatsapp url correctly with default or custom template', function () {
    $promoDefault = Promo::factory()->create([
        'title' => 'Diskon 20%',
        'wa_template' => null,
    ]);

    expect($promoDefault->wa_url)->toContain('https://wa.me/')
        ->and($promoDefault->wa_url)->toContain(urlencode('Halo Brava, saya tertarik untuk mengklaim promo: Diskon 20%.'));

    $promoCustom = Promo::factory()->create([
        'title' => 'Diskon 40%',
        'wa_template' => 'Halo min mau klaim promo custom ini',
    ]);

    expect($promoCustom->wa_url)->toContain(urlencode('Halo min mau klaim promo custom ini'));
});
