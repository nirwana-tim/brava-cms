<?php

use App\Models\Blog;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('blog list clamps page parameter to a safe range', function () {
    Blog::factory()->published()->create(['title' => ['id' => 'Post A', 'en' => 'Post A']]);

    $this->get('/api/v1/blogs?page=999999')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1000);

    $this->get('/api/v1/blogs?page=1001')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1000);

    $this->get('/api/v1/blogs?page=0')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1);

    $this->get('/api/v1/blogs?page=-5')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1);
});

test('blog list accepts very long search strings without error', function () {
    Blog::factory()->published()->create(['title' => ['id' => 'Post A', 'en' => 'Post A']]);

    $this->get('/api/v1/blogs?search='.str_repeat('a', 500))
        ->assertOk()
        ->assertJsonPath('success', true);
});

test('services list clamps page parameter', function () {
    Service::factory()->create(['title' => ['id' => 'Service A', 'en' => 'Service A']]);

    $this->get('/api/v1/services?page=999999')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1000);

    $this->get('/api/v1/services?page=-5')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1);
});

test('portfolio list clamps page parameter', function () {
    PortfolioItem::factory()->create(['title' => ['id' => 'Project A', 'en' => 'Project A']]);

    $this->get('/api/v1/portfolio?page=999999')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1000);

    $this->get('/api/v1/portfolio?page=-5')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1);
});

test('promos list clamps page parameter', function () {
    Promo::factory()->create(['title' => ['id' => 'Promo A', 'en' => 'Promo A']]);

    $this->get('/api/v1/promos?page=999999')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1000);

    $this->get('/api/v1/promos?page=-5')
        ->assertOk()
        ->assertJsonPath('meta.current_page', 1);
});
