<?php

use App\Enums\UserRole;
use App\Models\PageSeo;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    PageSeo::create([
        'page_key' => 'about',
        'meta_title' => ['id' => 'Tentang Kami', 'en' => 'About Us'],
        'meta_description' => ['id' => 'Deskripsi tentang kami', 'en' => 'About us description'],
        'robots_index' => true,
        'robots_follow' => true,
        'schema_type' => 'AboutPage',
    ]);
});

test('staff, admin and super admin can view page seo index', function () {
    foreach ([UserRole::Staff, UserRole::Admin, UserRole::SuperAdmin] as $role) {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)->get(route('admin.page-seo.index'))
            ->assertOk()
            ->assertSee('about')
            ->assertSee('Tentang Kami');
    }
});

test('guest is redirected from page seo pages', function () {
    $this->get(route('admin.page-seo.index'))->assertRedirect(route('login'));
    $this->get(route('admin.page-seo.edit', 'about'))->assertRedirect(route('login'));
});

test('can view page seo edit form', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->get(route('admin.page-seo.edit', 'about'))
        ->assertOk()
        ->assertSee('about')
        ->assertSee('canonical_url');
});

test('unknown page key returns 404', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->get(route('admin.page-seo.edit', 'nonexistent'))->assertNotFound();
});

test('staff, admin and super admin can view page seo detail', function () {
    foreach ([UserRole::Staff, UserRole::Admin, UserRole::SuperAdmin] as $role) {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)->get(route('admin.page-seo.show', 'about'))
            ->assertOk()
            ->assertSee('AboutPage')
            ->assertSee('Tentang Kami')
            ->assertSee('About Us');
    }
});

test('guest is redirected from page seo detail', function () {
    $this->get(route('admin.page-seo.show', 'about'))->assertRedirect(route('login'));
});

test('can update page seo metadata', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put(route('admin.page-seo.update', 'about'), [
        'meta_title' => ['id' => 'Tentang Kami BRAVA', 'en' => 'About BRAVA'],
        'meta_description' => ['id' => 'Deskripsi baru', 'en' => 'New description'],
        'og_image' => '/storage/seo/about.jpg',
        'og_image_alt' => ['id' => 'Tentang BRAVA', 'en' => 'About BRAVA'],
        'canonical_url' => 'https://brava.id/about',
        'schema_type' => 'AboutPage',
        'robots_index' => '1',
        'robots_follow' => '0',
    ])->assertRedirect(route('admin.page-seo.index'));

    $this->assertDatabaseHas('page_seos', [
        'page_key' => 'about',
        'meta_title->id' => 'Tentang Kami BRAVA',
        'meta_title->en' => 'About BRAVA',
        'meta_description->id' => 'Deskripsi baru',
        'meta_description->en' => 'New description',
        'og_image' => '/storage/seo/about.jpg',
        'canonical_url' => 'https://brava.id/about',
        'schema_type' => 'AboutPage',
        'robots_index' => 1,
        'robots_follow' => 0,
    ]);
});

test('unchecked robots toggles are saved as false', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put(route('admin.page-seo.update', 'about'), [
        'meta_title' => ['id' => 'Tentang Kami BRAVA'],
    ])->assertRedirect(route('admin.page-seo.index'));

    $this->assertDatabaseHas('page_seos', [
        'page_key' => 'about',
        'robots_index' => 0,
        'robots_follow' => 0,
    ]);
});

test('rejects invalid schema type', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put(route('admin.page-seo.update', 'about'), [
        'schema_type' => 'BogusPage',
    ])->assertSessionHasErrors('schema_type');
});

test('rejects invalid canonical url', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put(route('admin.page-seo.update', 'about'), [
        'canonical_url' => 'not-a-url',
    ])->assertSessionHasErrors('canonical_url');
});

test('guest is redirected from global defaults page', function () {
    $this->get(route('admin.page-seo.defaults'))->assertRedirect(route('login'));
    $this->put(route('admin.page-seo.defaults.update'))->assertRedirect(route('login'));
});

test('only superadmin can access global defaults editor', function () {
    foreach ([UserRole::Admin, UserRole::Staff] as $role) {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)->get(route('admin.page-seo.defaults'))->assertForbidden();
        $this->actingAs($user)->put(route('admin.page-seo.defaults.update'), [
            'default_meta_title' => ['id' => 'Hacked'],
        ])->assertForbidden();
    }

    $superadmin = User::factory()->superAdmin()->create();

    $this->actingAs($superadmin)->get(route('admin.page-seo.defaults'))
        ->assertOk()
        ->assertSee('Global SEO Defaults');
});

test('superadmin can update global defaults', function () {
    $superadmin = User::factory()->superAdmin()->create();

    $this->actingAs($superadmin)->put(route('admin.page-seo.defaults.update'), [
        'default_meta_title' => ['id' => 'Judul Global BRAVA', 'en' => 'BRAVA Global Title'],
        'default_meta_description' => ['id' => 'Deskripsi global.', 'en' => 'Global description.'],
        'default_og_image' => '/storage/seo/global-og.jpg',
    ])->assertRedirect(route('admin.page-seo.defaults'));

    $title = Setting::where('key', 'default_meta_title')->first();
    $description = Setting::where('key', 'default_meta_description')->first();
    $ogImage = Setting::where('key', 'default_og_image')->first();

    expect($title)->not->toBeNull()
        ->and($title->getTranslation('value', 'id', false))->toBe('Judul Global BRAVA')
        ->and($title->getTranslation('value', 'en', false))->toBe('BRAVA Global Title')
        ->and($description->getTranslation('value', 'id', false))->toBe('Deskripsi global.')
        ->and($ogImage->value)->toBe('/storage/seo/global-og.jpg');
});
