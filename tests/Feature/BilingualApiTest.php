<?php

use App\Enums\PostStatus;
use App\Models\Blog;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('blog api returns english translation when lang=en query param is provided', function () {
    $user = User::factory()->create();

    $blog = Blog::create([
        'author_id' => $user->id,
        'title' => ['id' => 'Judul Bahasa Indonesia', 'en' => 'English Title'],
        'slug' => ['id' => 'judul-id', 'en' => 'english-slug'],
        'excerpt' => ['id' => 'Kutipan ID', 'en' => 'English Excerpt'],
        'content' => ['id' => '<p>Konten ID</p>', 'en' => '<p>English Content</p>'],
        'status' => PostStatus::Published,
    ]);

    // Query in English
    $responseEn = $this->getJson('/api/blogs/english-slug?lang=en');
    $responseEn->assertStatus(200)
        ->assertJsonPath('data.title', 'English Title')
        ->assertJsonPath('data.slug', 'english-slug')
        ->assertJsonPath('data.slugs.id', 'judul-id')
        ->assertJsonPath('data.slugs.en', 'english-slug');

    // Query in Indonesian
    $responseId = $this->getJson('/api/blogs/judul-id?lang=id');
    $responseId->assertStatus(200)
        ->assertJsonPath('data.title', 'Judul Bahasa Indonesia')
        ->assertJsonPath('data.slug', 'judul-id');
});

test('blog api falls back to Indonesian when English translation is missing', function () {
    $user = User::factory()->create();

    $blog = Blog::create([
        'author_id' => $user->id,
        'title' => ['id' => 'Judul HANYA Indonesia', 'en' => null],
        'slug' => ['id' => 'judul-hanya-id', 'en' => null],
        'content' => ['id' => '<p>Konten ID</p>', 'en' => null],
        'status' => PostStatus::Published,
    ]);

    $response = $this->getJson('/api/blogs/judul-hanya-id?lang=en');
    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Judul HANYA Indonesia');
});

test('service and portfolio api support bilingual content and fallback', function () {
    $service = Service::create([
        'title' => ['id' => 'Layanan ID', 'en' => 'Service EN'],
        'slug' => ['id' => 'layanan-id', 'en' => 'service-en'],
        'description' => ['id' => 'Deskripsi ID', 'en' => 'Description EN'],
        'is_active' => true,
    ]);

    $portfolio = PortfolioItem::create([
        'service_id' => $service->id,
        'title' => ['id' => 'Portofolio ID', 'en' => 'Portfolio EN'],
        'slug' => ['id' => 'portofolio-id', 'en' => 'portfolio-en'],
        'description' => ['id' => 'Detail ID', 'en' => 'Detail EN'],
        'client' => ['id' => 'Klien ID', 'en' => 'Client EN'],
        'is_active' => true,
    ]);

    $this->getJson('/api/services?lang=en')
        ->assertStatus(200)
        ->assertJsonPath('data.0.title', 'Service EN');

    $this->getJson('/api/portfolio/portfolio-en?lang=en')
        ->assertStatus(200)
        ->assertJsonPath('data.title', 'Portfolio EN')
        ->assertJsonPath('data.client', 'Client EN');
});

test('admin can store and update bilingual blog post', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post('/admin/blogs', [
        'title' => ['id' => 'Judul Admin ID', 'en' => 'Admin Title EN'],
        'slug' => ['id' => 'slug-admin-id', 'en' => 'slug-admin-en'],
        'excerpt' => ['id' => 'Kutipan Admin ID', 'en' => 'Admin Excerpt EN'],
        'content' => ['id' => 'Konten Admin ID', 'en' => 'Admin Content EN'],
        'status' => 'published',
    ]);

    $response->assertRedirect('/admin/blogs');

    $blog = Blog::latest('id')->first();
    expect($blog->getTranslation('title', 'id'))->toBe('Judul Admin ID');
    expect($blog->getTranslation('title', 'en'))->toBe('Admin Title EN');
    expect($blog->getTranslation('slug', 'id'))->toBe('slug-admin-id');
    expect($blog->getTranslation('slug', 'en'))->toBe('slug-admin-en');
});
