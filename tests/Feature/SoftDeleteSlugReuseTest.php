<?php

use App\Enums\PostStatus;
use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;

function softDeleteAdmin(): User
{
    return User::factory()->create(['role' => UserRole::SuperAdmin]);
}

test('soft-deleted blog slug can be reused for a new blog', function () {
    $admin = softDeleteAdmin();
    $author = User::factory()->create();
    $blog = Blog::create([
        'author_id' => $author->id,
        'title' => ['id' => 'Lama', 'en' => 'Old'],
        'slug' => ['id' => 'slug-lama', 'en' => 'old-slug'],
        'status' => PostStatus::Published,
        'published_at' => now(),
    ]);
    $blog->delete();

    $this->actingAs($admin)->post(route('admin.blogs.store'), [
        'title' => ['id' => 'Baru', 'en' => 'New'],
        'slug' => ['id' => 'slug-lama', 'en' => 'new-slug'],
        'content' => ['id' => 'Isi', 'en' => 'Body'],
        'status' => PostStatus::Draft->value,
    ])->assertRedirect(route('admin.blogs.index'));
});

test('soft-deleted service cannot be selected for a portfolio item', function () {
    $admin = User::factory()->create(['role' => UserRole::SuperAdmin]);
    $service = Service::factory()->create();
    $service->delete();

    $this->actingAs($admin)->post(route('admin.portfolio.store'), [
        'service_id' => $service->id,
        'title' => ['id' => 'Proyek', 'en' => 'Project'],
        'slug' => ['id' => 'proyek', 'en' => 'project'],
    ])->assertSessionHasErrors('service_id');
});

test('soft-deleted category slug can be reused', function () {
    $admin = User::factory()->create(['role' => UserRole::SuperAdmin]);
    $category = Category::create([
        'name' => ['id' => 'Kategori Lama', 'en' => 'Old Category'],
        'slug' => ['id' => 'kategori-lama', 'en' => 'old-category'],
        'type' => 'blog',
    ]);
    $category->delete();

    $this->actingAs($admin)->post(route('admin.categories.store'), [
        'name' => ['id' => 'Kategori Baru', 'en' => 'New Category'],
        'slug' => ['id' => 'kategori-lama', 'en' => 'new-category'],
        'type' => 'blog',
    ])->assertRedirect();
});
