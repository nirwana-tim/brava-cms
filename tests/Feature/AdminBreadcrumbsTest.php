<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\User;

test('admin breadcrumbs resolve dashboard page to a single non-link item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertOk()
        ->assertSee('Dashboard', false);
});

test('admin breadcrumbs render section label on index pages', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get('/admin/blogs')
        ->assertOk()
        ->assertSee('Dashboard', false)
        ->assertSee('Blogs', false);
});

test('create page appends new section label as current item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get('/admin/blogs/create')
        ->assertOk()
        ->assertSee('New Blog', false);
});

test('edit page uses the bound record title as the current item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $blog = Blog::factory()->create([
        'author_id' => $admin->id,
        'title' => 'Judul Breadcrumb Unik',
    ]);

    $this->actingAs($admin)
        ->get("/admin/blogs/{$blog->id}/edit")
        ->assertOk()
        ->assertSee('Judul Breadcrumb Unik', false);
});

test('for generates a link to the section index before the current item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get('/admin/blogs/create')
        ->assertOk()
        ->assertSee(route('admin.blogs.index'), false);
});

test('profile page renders My Profile breadcrumb', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('My Profile', false);
});
