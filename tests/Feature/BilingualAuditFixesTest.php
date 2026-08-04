<?php

use App\Enums\PostStatus;
use App\Models\Blog;
use App\Models\Promo;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('public settings api exposes non-translatable contact and social values', function () {
    Setting::create(['key' => 'address', 'value' => 'Jl. Contoh No. 1', 'group' => 'contact', 'type' => 'textarea']);
    Setting::create(['key' => 'email', 'value' => 'hello@brava.id', 'group' => 'contact', 'type' => 'text']);
    Setting::create(['key' => 'phone', 'value' => '+62 812 3456 7890', 'group' => 'contact', 'type' => 'text']);
    Setting::create(['key' => 'whatsapp_number', 'value' => '62811112222', 'group' => 'contact', 'type' => 'text']);
    Setting::create(['key' => 'facebook_url', 'value' => 'https://facebook.com/brava', 'group' => 'social', 'type' => 'text']);

    $this->getJson('/api/settings?lang=id')
        ->assertOk()
        ->assertJsonPath('data.contact.address', 'Jl. Contoh No. 1')
        ->assertJsonPath('data.contact.email', 'hello@brava.id')
        ->assertJsonPath('data.contact.phone', '+62 812 3456 7890')
        ->assertJsonPath('data.contact.whatsapp_number', '62811112222')
        ->assertJsonPath('data.social.facebook_url', 'https://facebook.com/brava');
});

test('settings are translatable only for whitelisted keys', function () {
    $siteName = Setting::create([
        'key' => 'site_name',
        'value' => ['id' => 'Brava', 'en' => 'Brava English'],
        'group' => 'general',
        'type' => 'text',
    ]);

    expect($siteName->getTranslation('value', 'id'))->toBe('Brava');
    expect($siteName->getTranslation('value', 'en'))->toBe('Brava English');
    expect(json_decode($siteName->getRawOriginal('value'), true))->toMatchArray([
        'id' => 'Brava',
        'en' => 'Brava English',
    ]);

    $phone = Setting::create([
        'key' => 'phone',
        'value' => '+62 812 3456 7890',
        'group' => 'contact',
        'type' => 'text',
    ]);

    expect($phone->value)->toBe('+62 812 3456 7890');
    expect($phone->getRawOriginal('value'))->toBe('+62 812 3456 7890');
});

test('legacy plain-string settings are read without being corrupted', function () {
    DB::table('settings')->insert([
        'key' => 'whatsapp_number',
        'value' => '62811112222',
        'group' => 'contact',
        'type' => 'text',
    ]);

    $setting = Setting::where('key', 'whatsapp_number')->first();

    expect($setting->value)->toBe('62811112222');
    expect($setting->getRawOriginal('value'))->toBe('62811112222');
});

test('promo wa url uses the configured whatsapp number', function () {
    Setting::create(['key' => 'whatsapp_number', 'value' => '62811112222', 'group' => 'contact', 'type' => 'text']);
    $promo = Promo::factory()->create(['is_active' => true]);

    $response = $this->getJson('/api/promos/'.$promo->slug.'?lang=id');

    $response->assertOk()->assertJsonPath('data.wa_url', fn ($value) => str_contains($value, 'wa.me/62811112222'));
});

test('promo image alt resolves from translatable data', function () {
    $promo = Promo::factory()->create([
        'is_active' => true,
        'image_alt' => ['id' => 'Banner Promo', 'en' => 'Promo Banner'],
    ]);

    $this->getJson('/api/promos/'.$promo->slug.'?lang=id')
        ->assertOk()
        ->assertJsonPath('data.image_alt', 'Banner Promo');
});

test('slugs.en is null when no english slug exists', function () {
    $user = User::factory()->create();

    $blog = Blog::create([
        'author_id' => $user->id,
        'title' => ['id' => 'Judul ID', 'en' => null],
        'slug' => ['id' => 'judul-id', 'en' => null],
        'excerpt' => ['id' => 'Kutipan', 'en' => null],
        'content' => ['id' => 'Konten', 'en' => null],
        'status' => PostStatus::Published,
    ]);

    $this->getJson('/api/blogs/'.$blog->slug.'?lang=id')
        ->assertOk()
        ->assertJsonPath('data.slugs.en', null);
});

test('api responses expose vary header for language negotiation', function () {
    $this->getJson('/api/settings?lang=id')
        ->assertOk()
        ->assertHeader('Vary', 'Accept-Language, lang')
        ->assertHeaderContains('Cache-Control', 'max-age=900');
});

test('canonical url includes the locale prefix', function () {
    $promo = Promo::factory()->create(['is_active' => true]);

    $this->getJson('/api/promos/'.$promo->slug.'?lang=id')
        ->assertOk()
        ->assertJsonPath('data.seo.canonical_url', fn ($value) => str_contains($value, '/id/promos/'.$promo->slug));
});

test('admin update preserves english seo fields when submitted', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $blog = Blog::create([
        'author_id' => $admin->id,
        'title' => ['id' => 'Judul', 'en' => 'Title'],
        'slug' => ['id' => 'judul', 'en' => 'title'],
        'excerpt' => ['id' => 'Kutipan', 'en' => 'Excerpt'],
        'content' => ['id' => 'Konten', 'en' => 'Content'],
        'meta_title' => ['id' => 'Meta ID', 'en' => 'Meta EN'],
        'status' => PostStatus::Published,
    ]);

    $this->actingAs($admin)->put('/admin/blogs/'.$blog->id, [
        'title' => ['id' => 'Judul', 'en' => 'Title'],
        'slug' => ['id' => 'judul', 'en' => 'title'],
        'excerpt' => ['id' => 'Kutipan', 'en' => 'Excerpt'],
        'content' => ['id' => 'Konten', 'en' => 'Content'],
        'meta_title' => ['id' => 'Meta ID', 'en' => 'Meta EN'],
        'status' => 'published',
        'category_ids' => [],
    ])->assertRedirect('/admin/blogs');

    $blog->refresh();

    expect($blog->getTranslation('meta_title', 'en', false))->toBe('Meta EN');
});
