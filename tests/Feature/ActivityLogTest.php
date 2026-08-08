<?php

use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\Blog;
use App\Models\Promo;
use App\Models\User;

test('regular admin cannot access activity logs page', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get('/admin/activity-logs')
        ->assertStatus(403);
});

test('super admin can access activity logs page', function () {
    $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superAdmin)
        ->get('/admin/activity-logs')
        ->assertOk()
        ->assertSee('Activity Logs');
});

test('creating a blog records a created activity log', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin);

    $blog = Blog::factory()->create([
        'author_id' => $admin->id,
        'title' => 'Judul Blog Singkat',
    ]);

    $log = ActivityLog::where('loggable_type', Blog::class)
        ->where('loggable_id', $blog->id)
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->event)->toBe('created')
        ->and($log->user_id)->toBe($admin->id)
        ->and($log->description)->toContain('Judul Blog Singkat');
});

test('updating a blog records an updated activity log with property changes', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin);

    $blog = Blog::factory()->create(['author_id' => $admin->id]);

    $blog->update(['title' => 'Judul Baru Diperbarui']);

    $log = ActivityLog::where('loggable_type', Blog::class)
        ->where('loggable_id', $blog->id)
        ->latest('id')
        ->first();

    $newTitle = $log->properties['new']['title'] ?? null;

    expect($log->event)->toBe('updated')
        ->and(is_string($newTitle) && str_contains($newTitle, 'Judul Baru Diperbarui'))->toBeTrue()
        ->and(array_key_exists('old', $log->properties))->toBeTrue();
});

test('soft deleting a blog records a deleted activity log', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin);

    $blog = Blog::factory()->create(['author_id' => $admin->id]);

    $blog->delete();

    expect(ActivityLog::where('loggable_type', Blog::class)
        ->where('loggable_id', $blog->id)
        ->where('event', 'deleted')
        ->exists())->toBeTrue();
});

test('restoring a blog records a restored activity log', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin);

    $blog = Blog::factory()->create(['author_id' => $admin->id]);
    $blog->delete();

    $blog->restore();

    expect(ActivityLog::where('loggable_type', Blog::class)
        ->where('loggable_id', $blog->id)
        ->where('event', 'restored')
        ->exists())->toBeTrue();
});

test('force deleting a blog records a force_deleted activity log', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin);

    $blog = Blog::factory()->create(['author_id' => $admin->id]);
    $blog->delete();

    $blog->forceDelete();

    expect(ActivityLog::where('loggable_type', Blog::class)
        ->where('loggable_id', $blog->id)
        ->where('event', 'force_deleted')
        ->exists())->toBeTrue();
});

test('creating a promo records an activity log', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin);

    $promo = Promo::factory()->create();

    expect(ActivityLog::where('loggable_type', Promo::class)
        ->where('loggable_id', $promo->id)
        ->where('event', 'created')
        ->exists())->toBeTrue();
});

test('updating a user role records an activity log', function () {
    $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superAdmin);

    $target = User::factory()->create(['role' => UserRole::Staff]);
    $target->update(['role' => UserRole::Admin]);

    expect(ActivityLog::where('loggable_type', User::class)
        ->where('loggable_id', $target->id)
        ->where('event', 'updated')
        ->exists())->toBeTrue();
});

test('activity log page filters by event', function () {
    $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superAdmin);

    Blog::factory()->create(['author_id' => $superAdmin->id]);

    $this->get('/admin/activity-logs?event=created')
        ->assertOk()
        ->assertSee('Created');
});

test('composite indexes exist on blogs and promos tables', function () {
    $blogIndexes = Schema::getIndexes('blogs');
    $promoIndexes = Schema::getIndexes('promos');

    $hasBlogComposite = collect($blogIndexes)->contains(function (array $index) {
        return $index['columns'] === ['status', 'published_at'];
    });

    $hasPromoComposite = collect($promoIndexes)->contains(function (array $index) {
        return $index['columns'] === ['is_active', 'is_highlighted', 'valid_until'];
    });

    expect($hasBlogComposite)->toBeTrue();
    expect($hasPromoComposite)->toBeTrue();
});
