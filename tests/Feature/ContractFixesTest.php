<?php

use App\Enums\UserRole;
use App\Models\Media;
use App\Models\PortfolioItem;
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

    $response = $this->getJson('/api/settings');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.general.site_name', 'Brava CMS')
        ->assertJsonPath('data.adsense.adsense_enabled', true);
});

test('api settings grouped payload is flushed from cache on model save', function () {
    $setting = Setting::updateOrCreate(
        ['key' => 'site_name'],
        ['value' => 'Brava CMS', 'group' => 'general', 'type' => 'text']
    );

    expect($this->getJson('/api/settings')->json('data.general.site_name'))->toBe('Brava CMS');

    $setting->update(['value' => 'Renamed']);

    expect($this->getJson('/api/settings')->json('data.general.site_name'))->toBe('Renamed');
});

test('contact endpoint accepts valid payload with 200 contract shape', function () {
    $this->postJson('/api/contact', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+6281234567890',
        'subject' => 'Question',
        'message' => 'Hello world',
    ])->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Thank you for your message. We will get back to you soon.');
});

test('contact endpoint returns 422 contract shape on invalid payload', function () {
    $this->postJson('/api/contact', ['name' => ''])
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['message', 'errors']);
});

test('contact endpoint is rate limited to 5 requests per minute', function () {
    $payload = ['name' => 'A', 'email' => 'a@example.com', 'message' => 'x'];

    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/contact', $payload)->assertOk();
    }

    $this->postJson('/api/contact', $payload)->assertStatus(429);
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
    $other = PortfolioItem::factory()->create();
    $attached = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $other->id,
    ]);

    $this->actingAs($admin)->post(route('admin.portfolio.store'), [
        'title' => 'New Item',
        'slug' => 'new-item',
        'photo' => '/storage/portfolio/cover.jpg',
        'gallery_media_ids' => (string) $attached->id,
    ])->assertRedirect();

    expect($attached->fresh()->mediable_id)->toBe($other->id);
});

test('portfolio store attaches only unattached media to the new item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $free = Media::factory()->create(['mediable_id' => null, 'mediable_type' => null]);

    $this->actingAs($admin)->post(route('admin.portfolio.store'), [
        'title' => 'New Item',
        'slug' => 'new-item',
        'photo' => '/storage/portfolio/cover.jpg',
        'gallery_media_ids' => (string) $free->id,
    ])->assertRedirect();

    $portfolio = PortfolioItem::where('slug', 'new-item')->firstOrFail();

    expect($portfolio->media()->count())->toBe(1)
        ->and($free->fresh()->mediable_id)->toBe($portfolio->id);
});
