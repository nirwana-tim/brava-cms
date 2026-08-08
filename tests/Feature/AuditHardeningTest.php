<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Media;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\MediaUsageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    Cache::store('api')->flush();
    Cache::flush();
});

test('replacing a blog featured image deletes the old upload file', function () {
    Storage::disk('public')->put('uploads/old-cover.jpg', 'old');

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $blog = Blog::factory()->create([
        'featured_image' => '/storage/uploads/old-cover.jpg',
        'og_image' => null,
    ]);

    $this->actingAs($admin)->put(route('admin.blogs.update', $blog), [
        'title' => $blog->title,
        'slug' => $blog->slug,
        'status' => 'published',
        'featured_image' => '/storage/uploads/new-cover.jpg',
    ]);

    expect(Storage::disk('public')->exists('uploads/old-cover.jpg'))->toBeFalse();
    expect($blog->fresh()->featured_image)->toBe('/storage/uploads/new-cover.jpg');
});

test('blog update keeps media library images untouched', function () {
    Storage::disk('public')->put('media/library.jpg', 'old');

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $blog = Blog::factory()->create([
        'featured_image' => '/storage/media/library.jpg',
        'og_image' => null,
    ]);

    $this->actingAs($admin)->put(route('admin.blogs.update', $blog), [
        'title' => $blog->title,
        'slug' => $blog->slug,
        'status' => 'published',
        'featured_image' => '/storage/uploads/new-cover.jpg',
    ]);

    expect(Storage::disk('public')->exists('media/library.jpg'))->toBeTrue();
});

test('replacing a service photo deletes the old upload file', function () {
    Storage::disk('public')->put('uploads/old-photo.jpg', 'old');

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $service = Service::factory()->create([
        'photo' => '/storage/uploads/old-photo.jpg',
    ]);

    $this->actingAs($admin)->put(route('admin.services.update', $service), [
        'title' => $service->title,
        'slug' => $service->slug,
        'photo' => '/storage/uploads/new-photo.jpg',
        'is_active' => true,
    ]);

    expect(Storage::disk('public')->exists('uploads/old-photo.jpg'))->toBeFalse();
});

test('settings referencing media counts as in use', function () {
    $media = Media::factory()->create(['path' => 'media/logo.png']);
    Storage::disk('public')->put($media->path, 'content');

    Setting::updateOrCreate(
        ['key' => 'default_og_image'],
        [
            'value' => $media->url,
            'group' => 'seo',
            'type' => 'text',
        ]
    );

    $usage = app(MediaUsageService::class)->usageSummary($media);

    expect($usage)->toContain('Setting "default_og_image"');
});

test('profile deletion removes the avatar upload file', function () {
    Storage::disk('public')->put('uploads/avatar.jpg', 'old');

    $user = User::factory()->create([
        'role' => UserRole::Admin,
        'avatar' => '/storage/uploads/avatar.jpg',
    ]);

    $this->actingAs($user)->delete(route('profile.destroy'), [
        'password' => 'password',
    ]);

    expect(Storage::disk('public')->exists('uploads/avatar.jpg'))->toBeFalse();
});

test('upload destroy endpoint deletes a quick upload file', function () {
    Storage::disk('public')->put('uploads/tmp.jpg', 'content');

    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)->delete(route('admin.upload.destroy'), [
        'path' => 'uploads/tmp.jpg',
    ])->assertOk();

    Storage::disk('public')->assertMissing('uploads/tmp.jpg');
});

test('upload destroy endpoint rejects media library paths', function () {
    Storage::disk('public')->put('media/library.jpg', 'content');

    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)->delete(route('admin.upload.destroy'), [
        'path' => 'media/library.jpg',
    ])->assertStatus(422);

    Storage::disk('public')->assertExists('media/library.jpg');
});

test('staff can access admin dashboard', function () {
    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)->get('/admin')->assertOk();
});

test('resolveAlts pre-warms the alt cache with a single batch', function () {
    $service = app(MediaUsageService::class);

    $first = Media::factory()->create(['path' => 'media/one.jpg', 'alt_text' => 'First']);
    $second = Media::factory()->create(['path' => 'media/two.jpg', 'alt_text' => 'Second']);

    $service->resolveAlts([$first->url, $second->url, null, '']);

    expect($service->resolveAlt($first->url))->toBe('First')
        ->and($service->resolveAlt($second->url))->toBe('Second');
});

test('resolveAlts leaves external urls untouched', function () {
    $service = app(MediaUsageService::class);

    $service->resolveAlts(['https://cdn.example.com/photo.jpg', '/storage/media/unknown.jpg']);

    expect($service->resolveAlt('https://cdn.example.com/photo.jpg'))->toBeNull()
        ->and($service->resolveAlt('/storage/media/unknown.jpg'))->toBeNull();
});

test('force deleting a testimonial through trash removes its avatar file', function () {
    Storage::disk('public')->put('uploads/old-photo.jpg', 'old');

    $superadmin = User::factory()->superAdmin()->create();

    $testimonial = Testimonial::factory()->create([
        'avatar' => '/storage/uploads/old-photo.jpg',
    ]);

    $testimonial->delete();

    $this->actingAs($superadmin)->delete("/admin/trash/testimonials/{$testimonial->id}/force-delete")
        ->assertRedirect();

    expect(Storage::disk('public')->exists('uploads/old-photo.jpg'))->toBeFalse();
});

test('portfolio cover replacement deletes the old upload file', function () {
    Storage::disk('public')->put('uploads/old-cover.jpg', 'old');

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $portfolio = PortfolioItem::factory()->create([
        'photo' => '/storage/uploads/old-cover.jpg',
    ]);

    $newMedia = Media::factory()->create([
        'path' => 'media/new-cover.jpg',
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $portfolio->id,
    ]);
    Storage::disk('public')->put($newMedia->path, 'new');

    $this->actingAs($admin)->post(route('admin.portfolio.media.set-cover', [$portfolio, $newMedia]))
        ->assertJson(['success' => true]);

    expect(Storage::disk('public')->exists('uploads/old-cover.jpg'))->toBeFalse();
});
