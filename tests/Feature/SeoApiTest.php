<?php

use App\Models\Blog;
use App\Models\Category;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::store('api')->flush();
    Cache::flush();
});

test('blog api returns absolute image urls and canonical url', function () {
    $blog = Blog::factory()->create([
        'featured_image' => '/storage/media/cover.jpg',
        'og_image' => '/storage/media/og.jpg',
    ]);

    $response = $this->getJson('/api/v1/blogs/'.$blog->slug);

    $response->assertOk();
    $data = $response->json('data');

    expect($data['featured_image'])->toBe(url('/storage/media/cover.jpg'))
        ->and($data['seo']['og_image'])->toBe(url('/storage/media/og.jpg'))
        ->and($data['seo']['canonical_url'])->toBe(config('app.frontend_url').'/id/blogs/'.$blog->slug)
        ->and($data['seo']['schema_type'])->toBe($blog->schema_type)
        ->and($data['updated_at'])->not->toBeNull();
});

test('blog api falls back og image to featured image', function () {
    $blog = Blog::factory()->create([
        'featured_image' => '/storage/media/cover.jpg',
        'og_image' => null,
    ]);

    $response = $this->getJson('/api/v1/blogs/'.$blog->slug);

    expect($response->json('data.seo.og_image'))->toBe(url('/storage/media/cover.jpg'));
});

test('portfolio api returns absolute image urls canonical url and new seo fields', function () {
    $portfolio = PortfolioItem::factory()->create([
        'photo' => '/storage/portfolio/cover.jpg',
        'og_image' => null,
        'robots_follow' => true,
        'schema_type' => 'CreativeWork',
    ]);

    $response = $this->getJson('/api/v1/portfolio/'.$portfolio->slug);

    $response->assertOk();
    $data = $response->json('data');

    expect($data['photo'])->toBe(url('/storage/portfolio/cover.jpg'))
        ->and($data['seo']['og_image'])->toBe(url('/storage/portfolio/cover.jpg'))
        ->and($data['seo']['robots_follow'])->toBeTrue()
        ->and($data['seo']['schema_type'])->toBe('CreativeWork')
        ->and($data['seo']['canonical_url'])->toBe(config('app.frontend_url').'/id/portfolio/'.$portfolio->slug);
});

test('promo api returns seo block with absolute image and canonical url', function () {
    $promo = Promo::factory()->create([
        'image' => '/storage/promos/promo.jpg',
        'description' => 'Diskon 40% untuk seragam perusahaan.',
        'is_active' => true,
    ]);

    $response = $this->getJson('/api/v1/promos/'.$promo->slug);

    $response->assertOk();
    $data = $response->json('data');

    expect($data['image'])->toBe(url('/storage/promos/promo.jpg'))
        ->and($data['seo']['meta_title'])->toBe($promo->title)
        ->and($data['seo']['meta_description'])->toBe('Diskon 40% untuk seragam perusahaan.')
        ->and($data['seo']['og_image'])->toBe(url('/storage/promos/promo.jpg'))
        ->and($data['seo']['robots_index'])->toBeTrue()
        ->and($data['seo']['schema_type'])->toBe('SpecialAnnouncement')
        ->and($data['seo']['canonical_url'])->toBe(config('app.frontend_url').'/id/promos/'.$promo->slug)
        ->and($data['updated_at'])->not->toBeNull();
});

test('list resources expose absolute media urls', function () {
    $blog = Blog::factory()->create(['featured_image' => '/storage/media/list-cover.jpg']);
    $portfolio = PortfolioItem::factory()->create(['photo' => '/storage/portfolio/list.jpg']);
    $service = Service::factory()->create(['photo' => '/storage/services/list.jpg']);

    $blogResponse = $this->getJson('/api/v1/blogs');
    $portfolioResponse = $this->getJson('/api/v1/portfolio');
    $serviceResponse = $this->getJson('/api/v1/services');

    expect($blogResponse->json('data.0.featured_image'))->toBe(url('/storage/media/list-cover.jpg'))
        ->and($portfolioResponse->json('data.0.photo'))->toBe(url('/storage/portfolio/list.jpg'))
        ->and($serviceResponse->json('data.0.photo'))->toBe(url('/storage/services/list.jpg'));
});

test('sitemap endpoint returns public content with valid locs', function () {
    $blog = Blog::factory()->published()->create();
    Blog::factory()->draft()->create();
    $portfolio = PortfolioItem::factory()->create(['is_active' => true]);
    PortfolioItem::factory()->create(['is_active' => false]);
    $promo = Promo::factory()->create(['is_active' => true]);
    Promo::factory()->create(['is_active' => false]);
    $service = Service::factory()->create(['is_active' => true]);
    $category = Category::create(['name' => 'Tech', 'slug' => 'tech', 'type' => 'blog']);

    $response = $this->getJson('/api/v1/sitemap');

    $response->assertOk();
    $urls = collect($response->json('data'));

    expect($urls->where('type', 'blog'))->toHaveCount(1)
        ->and($urls->where('type', 'portfolio'))->toHaveCount(1)
        ->and($urls->where('type', 'promo'))->toHaveCount(1)
        ->and($urls->where('type', 'service'))->toHaveCount(1)
        ->and($urls->where('type', 'category'))->toHaveCount(1)
        ->and($urls->where('type', 'blog')->first()['loc'])->toBe(config('app.frontend_url').'/id/blogs/'.$blog->slug)
        ->and($urls->where('type', 'portfolio')->first()['loc'])->toBe(config('app.frontend_url').'/id/portfolio/'.$portfolio->slug)
        ->and($urls->where('type', 'promo')->first()['loc'])->toBe(config('app.frontend_url').'/id/promos/'.$promo->slug)
        ->and($urls->where('type', 'service')->first()['loc'])->toBe(config('app.frontend_url').'/id/services/'.$service->slug)
        ->and($urls->where('type', 'category')->first()['loc'])->toBe(config('app.frontend_url').'/id/blog?category=tech');
});

test('sitemap endpoint is cached and invalidated on content change', function () {
    Blog::factory()->published()->create();

    $first = $this->getJson('/api/v1/sitemap')->json('data');
    $second = $this->getJson('/api/v1/sitemap')->json('data');

    expect(count($first))->toBe(1)
        ->and(count($second))->toBe(1);

    Blog::factory()->published()->create();
    Cache::store('api')->forget('sitemap.all.id');

    $third = $this->getJson('/api/v1/sitemap')->json('data');
    expect(count($third))->toBe(2);
});

test('portfolio admin can store robots follow and schema type', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $service = Service::factory()->create();

    $response = $this->actingAs($user)->post('/admin/portfolio', [
        'service_id' => $service->id,
        'title' => 'Project Alpha',
        'slug' => 'project-alpha',
        'photo' => '/storage/portfolio/cover.jpg',
        'robots_index' => '1',
        'robots_follow' => '0',
        'schema_type' => 'WebPage',
    ]);

    $response->assertRedirect();

    $portfolio = PortfolioItem::where('slug->id', 'project-alpha')->orWhere('slug->en', 'project-alpha')->first();

    expect($portfolio)->not->toBeNull()
        ->and($portfolio->robots_index)->toBeTrue()
        ->and($portfolio->robots_follow)->toBeFalse()
        ->and($portfolio->schema_type)->toBe('WebPage');
});

test('blog admin can store robots follow and schema type', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($user)->post('/admin/blogs', [
        'title' => 'SEO Article',
        'slug' => 'seo-article',
        'status' => 'published',
        'robots_index' => '1',
        'robots_follow' => '0',
        'schema_type' => 'NewsArticle',
    ]);

    $response->assertRedirect();

    $blog = Blog::where('slug->id', 'seo-article')->orWhere('slug->en', 'seo-article')->first();

    expect($blog)->not->toBeNull()
        ->and($blog->robots_follow)->toBeFalse()
        ->and($blog->schema_type)->toBe('NewsArticle');
});

test('draft blogs are excluded from sitemap', function () {
    Blog::factory()->draft()->create(['slug' => 'hidden-draft']);

    $response = $this->getJson('/api/v1/sitemap');

    expect(collect($response->json('data'))->where('slug', 'hidden-draft'))->toHaveCount(0);
});

test('draft blogs do not expose seo or canonical via api', function () {
    Blog::factory()->draft()->create(['slug' => 'hidden-draft']);

    $this->getJson('/api/v1/blogs/hidden-draft')->assertStatus(404);
});

test('successful get api responses include cache control header', function () {
    Blog::factory()->published()->create();

    $response = $this->get('/api/v1/blogs');

    $response->assertOk();
    $cacheControl = $response->headers->get('Cache-Control');

    expect($cacheControl)->toContain('public')
        ->and($cacheControl)->toContain('max-age=900')
        ->and($cacheControl)->toContain('s-maxage=900');
});

test('post api requests are not publicly cached', function () {
    $response = $this->postJson('/api/v1/nonexistent');

    $response->assertStatus(404);
    expect($response->headers->get('Cache-Control'))->not->toContain('public');
});

test('non-api routes do not receive public cache control header', function () {
    $response = $this->get('/login');

    expect($response->headers->get('Cache-Control'))->not->toContain('public');
});
