<?php

use App\Enums\UserRole;
use App\Models\Media;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::store('api')->flush();
    Cache::flush();
});

test('api settings endpoint returns grouped structure with boolean cast', function () {
    Setting::updateOrCreate(
        ['key' => 'site_name'],
        ['value' => 'Brava CMS', 'group' => 'general', 'type' => 'text']
    );
    Setting::updateOrCreate(
        ['key' => 'adsense_enabled'],
        ['value' => '1', 'group' => 'adsense', 'type' => 'boolean']
    );

    $response = $this->getJson('/api/v1/settings');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.general.site_name', 'Brava CMS');
});

test('api settings endpoint does not expose sensitive groups publicly', function () {
    Setting::updateOrCreate(
        ['key' => 'adsense_enabled'],
        ['value' => '1', 'group' => 'adsense', 'type' => 'boolean']
    );
    Setting::updateOrCreate(
        ['key' => 'adsense_client_id'],
        ['value' => 'ca-pub-123456', 'group' => 'adsense', 'type' => 'text']
    );
    Setting::updateOrCreate(
        ['key' => 'site_name'],
        ['value' => 'Brava CMS', 'group' => 'general', 'type' => 'text']
    );

    $response = $this->getJson('/api/v1/settings');

    $response->assertOk()
        ->assertJsonPath('data.general.site_name', 'Brava CMS')
        ->assertJsonMissingPath('data.adsense')
        ->assertJsonMissingPath('data.adsense_enabled')
        ->assertJsonMissingPath('data.adsense_client_id');
});

test('api settings grouped payload is flushed from cache on model save', function () {
    $setting = Setting::updateOrCreate(
        ['key' => 'site_name'],
        ['value' => 'Brava CMS', 'group' => 'general', 'type' => 'text']
    );

    expect($this->getJson('/api/v1/settings')->json('data.general.site_name'))->toBe('Brava CMS');

    $setting->update(['value' => 'Renamed']);

    expect($this->getJson('/api/v1/settings')->json('data.general.site_name'))->toBe('Renamed');
});

test('inactive user cannot log in', function () {
    $user = User::factory()->create(['is_active' => false, 'password' => 'password']);

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('active user can log in', function () {
    $user = User::factory()->create(['is_active' => true, 'password' => 'password']);

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});

test('portfolio store does not steal media already attached to another item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $other = PortfolioItem::factory()->create();
    $attached = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $other->id,
    ]);

    $this->actingAs($admin)->post(route('admin.portfolio.store'), [
        'service_id' => $service->id,
        'title' => 'New Item',
        'slug' => 'new-item',
        'photo' => '/storage/portfolio/cover.jpg',
        'gallery_media_ids' => (string) $attached->id,
    ])->assertRedirect();

    expect($attached->fresh()->mediable_id)->toBe($other->id);
});

test('portfolio store attaches only unattached media to the new item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create();
    $free = Media::factory()->create(['mediable_id' => null, 'mediable_type' => null]);

    $this->actingAs($admin)->post(route('admin.portfolio.store'), [
        'service_id' => $service->id,
        'title' => 'New Item',
        'slug' => 'new-item',
        'photo' => '/storage/portfolio/cover.jpg',
        'gallery_media_ids' => (string) $free->id,
    ])->assertRedirect();

    $portfolio = PortfolioItem::where('slug->id', 'new-item')->orWhere('slug->en', 'new-item')->firstOrFail();

    expect($portfolio->media()->count())->toBe(1)
        ->and($free->fresh()->mediable_id)->toBe($portfolio->id);
});
