<?php

use App\Enums\UserRole;
use App\Http\Middleware\EnsureStaffOrAdmin;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Promo;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::store('api')->flush();
    Cache::flush();
});

test('duplicate service slug is rejected by validation', function () {
    Service::create(['title' => 'Konveksi', 'slug' => 'konveksi', 'description' => 'a']);
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post('/admin/services', [
        'title' => 'Konveksi Lain',
        'slug' => 'konveksi',
    ])->assertSessionHasErrors('slug.id');

    expect(Service::count())->toBe(1);
});

test('duplicate blog slug is rejected by validation', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Blog::factory()->create(['title' => 'A', 'slug' => 'same-slug']);

    $this->actingAs($admin)->post('/admin/blogs', [
        'title' => 'B',
        'slug' => 'same-slug',
        'status' => 'draft',
    ])->assertSessionHasErrors('slug.id');
});

test('updating a resource to its own slug passes validation', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::create(['title' => 'Konveksi', 'slug' => 'konveksi', 'description' => 'a']);

    $this->actingAs($admin)->put('/admin/services/'.$service->id, [
        'title' => 'Konveksi Update',
        'slug' => 'konveksi',
    ])->assertRedirect('/admin/services');

    expect($service->fresh()->getTranslation('title', 'id'))->toBe('Konveksi Update');
});

test('duplicate category slug is rejected per type scope', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post('/admin/categories', [
        'name' => 'Ujian',
        'slug' => 'ujian',
        'type' => 'blog',
    ])->assertRedirect('/admin/categories');

    $this->actingAs($admin)->post('/admin/categories', [
        'name' => 'Ujian Dua',
        'slug' => 'ujian',
        'type' => 'blog',
    ])->assertSessionHasErrors('slug.id');

    expect(Category::count())->toBe(1);
});

test('team member account is not created unless create_user_account is checked', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post('/admin/team', [
        'name' => 'No Login',
        'position' => 'Staff',
        'email' => 'nologin@brava.id',
        'create_user_account' => '0',
    ])->assertRedirect('/admin/team');

    expect(TeamMember::where('email', 'nologin@brava.id')->exists())->toBeTrue()
        ->and(User::where('email', 'nologin@brava.id')->exists())->toBeFalse();
});

test('team member role is synced on update', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $team = TeamMember::create([
        'user_id' => $admin->id,
        'name' => $admin->name,
        'position' => 'Administrator',
        'email' => $admin->email,
        'is_active' => true,
    ]);

    $this->actingAs($admin)->withoutMiddleware(EnsureStaffOrAdmin::class)->put('/admin/team/'.$team->id, [
        'name' => $admin->name,
        'position' => 'Administrator',
        'email' => $admin->email,
    ])->assertRedirect('/admin/team');

    expect($admin->fresh()->role)->toBe(UserRole::Admin);
});

test('sitemap excludes expired promos', function () {
    Promo::factory()->expired()->create(['slug' => 'expired-promo']);
    Promo::factory()->create(['slug' => 'live-promo']);

    $response = $this->getJson('/api/v1/sitemap');

    $slugs = collect($response->json('data'))->where('type', 'promo')->pluck('slug')->all();
    expect($slugs)->toContain('live-promo')
        ->and($slugs)->not->toContain('expired-promo');
});

test('canonical url stays id-prefixed when english slug is missing', function () {
    $blog = Blog::factory()->create([
        'title' => 'Halo',
        'slug' => 'halo',
        'status' => 'published',
    ]);

    $response = $this->withHeaders(['lang' => 'en'])->getJson('/api/v1/blogs/'.$blog->slug);
    $response->assertOk()
        ->assertJsonPath('data.seo.canonical_url', config('app.frontend_url').'/id/blogs/'.$blog->slug);
});

test('services are ordered by id as tiebreaker in public list', function () {
    $a = Service::create(['title' => 'Alpha', 'slug' => 'alpha', 'description' => 'a', 'sort_order' => 0]);
    $b = Service::create(['title' => 'Beta', 'slug' => 'beta', 'description' => 'b', 'sort_order' => 0]);

    $response = $this->getJson('/api/v1/services');
    $ids = collect($response->json('data'))->pluck('id')->all();

    expect($response->assertOk())
        ->and($ids)->toBe([$a->id, $b->id]);
});
