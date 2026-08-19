<?php

namespace Tests\Feature\Api;

use App\Models\PageSeo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageSeoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_page_seo_by_key(): void
    {
        PageSeo::create([
            'page_key' => 'about',
            'meta_title' => ['id' => 'Tentang Kami', 'en' => 'About Us'],
            'meta_description' => ['id' => 'Deskripsi tentang kami', 'en' => 'About us description'],
            'robots_index' => true,
            'robots_follow' => true,
            'schema_type' => 'AboutPage',
        ]);

        $response = $this->getJson('/api/v1/page-seo?page=about&lang=id');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.page_key', 'about')
            ->assertJsonPath('data.meta_title', 'Tentang Kami')
            ->assertJsonPath('data.robots_index', true);
    }

    public function test_can_get_all_page_seos(): void
    {
        PageSeo::create([
            'page_key' => 'home',
            'meta_title' => ['id' => 'Beranda', 'en' => 'Home'],
            'robots_index' => true,
        ]);

        PageSeo::create([
            'page_key' => 'contact',
            'meta_title' => ['id' => 'Kontak', 'en' => 'Contact'],
            'robots_index' => true,
        ]);

        $response = $this->getJson('/api/v1/page-seo?lang=id');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'home' => ['page_key', 'meta_title', 'robots_index'],
                    'contact' => ['page_key', 'meta_title', 'robots_index'],
                ],
            ]);
    }

    public function test_returns_404_when_page_not_found(): void
    {
        $response = $this->getJson('/api/v1/page-seo?page=nonexistent');

        $response->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
