<?php

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Faq;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\User;
use App\Services\HtmlSanitizer;
use App\Services\MediaUsageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function auditFixSuperAdmin(): User
{
    return User::factory()->create(['role' => UserRole::SuperAdmin]);
}

it('treats a submitted sort_order of 0 as auto-assign on FAQ create', function () {
    Faq::factory()->create(['sort_order' => 3]);

    $this->actingAs(auditFixSuperAdmin())
        ->post(route('admin.faqs.store'), [
            'question' => ['id' => 'Pertanyaan?'],
            'answer' => ['id' => 'Jawaban.'],
            'sort_order' => 0,
        ])
        ->assertRedirect();

    $latest = Faq::latest('id')->first();

    expect($latest->sort_order)->toBe(4);
});

it('rejects deleting an upload path containing a traversal segment', function () {
    Storage::fake('public');

    $this->actingAs(auditFixSuperAdmin())
        ->delete(route('admin.upload.destroy'), ['path' => 'uploads/../.env'])
        ->assertStatus(422)
        ->assertJson(['error' => 'Invalid upload path.']);
});

it('attaches portfolio categories from category_ids on create', function () {
    $service = Service::factory()->create();
    $category = Category::factory()->create(['type' => 'portfolio']);

    $this->actingAs(auditFixSuperAdmin())
        ->post(route('admin.portfolio.store'), [
            'service_id' => $service->id,
            'title' => ['id' => 'Seragam PDH'],
            'slug' => ['id' => 'seragam-pdh'],
            'photo' => '/storage/portfolio/cover.jpg',
            'category_ids' => [$category->id],
        ])
        ->assertRedirect();

    $portfolio = PortfolioItem::where('slug->id', 'seragam-pdh')->first();

    expect($portfolio->categories()->pluck('categories.id'))->toContain($category->id);
});

it('keeps existing portfolio categories when category_ids is not submitted on update', function () {
    $portfolio = PortfolioItem::factory()->create();
    $category = Category::factory()->create(['type' => 'portfolio']);
    $portfolio->categories()->attach($category);

    $this->actingAs(auditFixSuperAdmin())
        ->put(route('admin.portfolio.update', $portfolio), [
            'service_id' => $portfolio->service_id,
            'title' => ['id' => 'Judul Baru'],
            'slug' => ['id' => 'judul-baru'],
            'photo' => '/uploads/folio.jpg',
        ])
        ->assertRedirect();

    expect($portfolio->fresh()->categories->pluck('id'))->toContain($category->id);
});

it('removes dangerous hrefs with control-character obfuscated schemes', function () {
    $sanitizer = app(HtmlSanitizer::class);

    $output = $sanitizer->clean('<a href="&#106;avascript:alert(1)">klik</a><a href="jav&#x0A;ascript:alert(1)">klik2</a><a href="https://example.com">aman</a>');

    expect($output)
        ->not->toContain('javascript')
        ->and($output)->toContain('href="https://example.com"');
});

it('caches a null alt lookup so repeated resolveAlt calls do not query again', function () {
    $service = app(MediaUsageService::class);
    $service->flushAltCache();

    $connection = DB::connection();

    $connection->flushQueryLog();
    $connection->enableQueryLog();

    $service->resolveAlt('/storage/uploads/missing.webp');
    $firstQueries = count($connection->getQueryLog());

    $connection->flushQueryLog();
    $service->resolveAlt('/storage/uploads/missing.webp');
    $secondQueries = count($connection->getQueryLog());

    expect($firstQueries)->toBeGreaterThan(0)
        ->and($secondQueries)->toBe(0);
});
