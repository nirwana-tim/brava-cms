<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\PageSeo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

test('page seo edit renders section link and page key as current item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    PageSeo::create([
        'page_key' => 'about',
        'meta_title' => ['id' => 'Tentang Kami'],
        'meta_description' => ['id' => 'Deskripsi'],
        'robots_index' => true,
        'robots_follow' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.page-seo.edit', 'about'))
        ->assertOk()
        ->assertSee(route('admin.page-seo.index'), false)
        ->assertSee('About', false);
});

test('page seo show renders section link and page key as current item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    PageSeo::create([
        'page_key' => 'contact',
        'meta_title' => ['id' => 'Kontak'],
        'meta_description' => ['id' => 'Deskripsi'],
        'robots_index' => true,
        'robots_follow' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.page-seo.show', 'contact'))
        ->assertOk()
        ->assertSee(route('admin.page-seo.index'), false)
        ->assertSee('Contact', false);
});
