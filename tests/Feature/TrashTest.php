<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Promo;
use App\Models\User;

test('regular admin cannot access recycle bin', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $response = $this
        ->actingAs($admin)
        ->get('/admin/trash');

    $response->assertStatus(403);
});

test('super admin can access recycle bin', function () {
    $superAdmin = User::factory()->create([
        'role' => UserRole::SuperAdmin,
    ]);

    $response = $this
        ->actingAs($superAdmin)
        ->get('/admin/trash');

    $response->assertOk();
});

test('super admin can restore soft-deleted blog post', function () {
    $superAdmin = User::factory()->create([
        'role' => UserRole::SuperAdmin,
    ]);

    $blog = Blog::factory()->create([
        'author_id' => $superAdmin->id,
    ]);
    $blog->delete();

    expect(Blog::onlyTrashed()->count())->toBe(1);

    $response = $this
        ->actingAs($superAdmin)
        ->post("/admin/trash/blogs/{$blog->id}/restore");

    $response->assertRedirect('/admin/trash?type=blogs');
    expect(Blog::onlyTrashed()->count())->toBe(0);
    expect(Blog::count())->toBe(1);
});

test('super admin can permanently force delete a soft-deleted blog post', function () {
    $superAdmin = User::factory()->create([
        'role' => UserRole::SuperAdmin,
    ]);

    $blog = Blog::factory()->create([
        'author_id' => $superAdmin->id,
    ]);
    $blog->delete();

    expect(Blog::onlyTrashed()->count())->toBe(1);

    $response = $this
        ->actingAs($superAdmin)
        ->delete("/admin/trash/blogs/{$blog->id}/force-delete");

    $response->assertRedirect('/admin/trash?type=blogs');
    expect(Blog::withTrashed()->count())->toBe(0);
});

test('super admin can restore soft-deleted promo', function () {
    $superAdmin = User::factory()->create([
        'role' => UserRole::SuperAdmin,
    ]);

    $promo = Promo::factory()->create();
    $promo->delete();

    expect(Promo::onlyTrashed()->count())->toBe(1);

    $response = $this
        ->actingAs($superAdmin)
        ->post("/admin/trash/promos/{$promo->id}/restore");

    $response->assertRedirect('/admin/trash?type=promos');
    expect(Promo::onlyTrashed()->count())->toBe(0);
    expect(Promo::count())->toBe(1);
});
