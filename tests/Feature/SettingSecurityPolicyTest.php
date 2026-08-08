<?php

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setting::create(['key' => 'site_name', 'value' => 'Brava CMS', 'group' => 'general', 'type' => 'text']);
    Setting::create(['key' => 'email', 'value' => 'hello@brava.id', 'group' => 'contact', 'type' => 'text']);
    Setting::create(['key' => 'google_analytics_id', 'value' => 'G-ORIGINAL123', 'group' => 'seo', 'type' => 'text']);
});

test('adsense settings exist with correct group and type', function () {
    $settings = Setting::where('group', 'adsense')->get()->keyBy('key');

    expect($settings->has('adsense_enabled'))->toBeTrue();
    expect($settings->get('adsense_enabled'))->TobeInstanceOf(Setting::class)
        ->type->toBe('boolean');

    expect($settings->has('adsense_client_id'))->toBeTrue();
    expect($settings->get('adsense_client_id')->group)->toBe('adsense');

    expect($settings->has('adsense_slot_1'))->toBeTrue();
    expect($settings->has('adsense_slot_2'))->toBeTrue();
});

test('social settings include optional platform urls and public api exposes them', function () {
    Setting::updateOrCreate(['key' => 'facebook_url'], ['value' => 'https://facebook.com/brava', 'group' => 'social', 'type' => 'text']);
    Setting::updateOrCreate(['key' => 'instagram_url'], ['value' => 'https://instagram.com/brava', 'group' => 'social', 'type' => 'text']);
    Setting::updateOrCreate(['key' => 'youtube_url'], ['value' => 'https://youtube.com/@brava', 'group' => 'social', 'type' => 'text']);
    Setting::updateOrCreate(['key' => 'tiktok_url'], ['value' => '', 'group' => 'social', 'type' => 'text']);
    Setting::updateOrCreate(['key' => 'x_url'], ['value' => 'https://x.com/brava', 'group' => 'social', 'type' => 'text']);
    Setting::updateOrCreate(['key' => 'linkedin_url'], ['value' => 'https://linkedin.com/company/brava', 'group' => 'social', 'type' => 'text']);

    $settings = Setting::where('group', 'social')->get()->keyBy('key');

    expect($settings->has('facebook_url'))->toBeTrue()
        ->and($settings->has('instagram_url'))->toBeTrue()
        ->and($settings->has('youtube_url'))->toBeTrue()
        ->and($settings->has('tiktok_url'))->toBeTrue()
        ->and($settings->has('x_url'))->toBeTrue()
        ->and($settings->has('linkedin_url'))->toBeTrue();

    $data = $this->getJson('/api/v1/settings')->json('data');

    expect($data['social']['youtube_url'])->toBe('https://youtube.com/@brava')
        ->and($data['social']['x_url'])->toBe('https://x.com/brava')
        ->and($data['social']['tiktok_url'])->toBe('')
        ->and($data['general'])->not->toHaveKey('logo')
        ->and($data['general'])->not->toHaveKey('favicon');
});

test('normal admin sees seo or general branding settings in technical settings card with developer notice', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->get('/admin/settings');

    $response->assertStatus(200);
    $response->assertSee('Technical Settings');
    $response->assertSee('Site Name');
    $response->assertSee('Brava CMS');
    $response->assertSee('hello@brava.id');
    $response->assertSee('G-ORIGINAL123');
    $response->assertSee('Untuk perubahan pengaturan ini, silakan hubungi developer.');
});

test('superadmin can see all settings including seo and sees impact notes', function () {
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $response = $this->actingAs($superadmin)->get('/admin/settings');

    $response->assertStatus(200);
    $response->assertSee('site_name');
    $response->assertSee('G-ORIGINAL123');
    $response->assertSee('ID properti Google Analytics 4');
    $response->assertSee('Nama utama yang tampil di title bar browser');
});

test('normal admin cannot modify seo or general settings via put request', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put('/admin/settings', [
        'site_name' => 'Updated Brand Name',
        'google_analytics_id' => 'G-HACKED999',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'site_name')->first()->value)->toBe('Brava CMS');
    expect(Setting::where('key', 'google_analytics_id')->first()->value)->toBe('G-ORIGINAL123');
});

test('normal admin cannot modify adsense settings via put request', function () {
    Setting::where('key', 'adsense_slot_1')->update(['value' => json_encode(['id' => 'ORIGINAL-SLOT', 'en' => null])]);

    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put('/admin/settings', [
        'adsense_slot_1' => 'HACKED-SLOT',
        'adsense_client_id' => 'ca-pub-HACKED',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'adsense_slot_1')->first()->value)->toBe('ORIGINAL-SLOT');
    expect(Setting::where('key', 'adsense_client_id')->first()->value ?? '')->toBe('');
});

test('superadmin can update adsense settings via put request', function () {
    Setting::where('key', 'adsense_client_id')->update(['value' => '']);

    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->put('/admin/settings', [
        'adsense_client_id' => 'ca-pub-1234567890123456',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'adsense_client_id')->value('value'))->toBe('ca-pub-1234567890123456');
});

test('superadmin can update ga4 reporting settings via put request', function () {
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->put('/admin/settings', [
        'ga4_property_id' => '987654321',
        'ga4_service_account_key' => '{"type":"service_account","client_email":"ga4@test.iam.gserviceaccount.com"}',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'ga4_property_id')->value('value'))->toBe('987654321')
        ->and(Setting::where('key', 'ga4_service_account_key')->value('value'))->toBe('{"type":"service_account","client_email":"ga4@test.iam.gserviceaccount.com"}');
});

test('normal admin cannot modify ga4 reporting settings via put request', function () {
    Setting::updateOrCreate(['key' => 'ga4_property_id'], ['value' => 'ORIGINAL-ID', 'group' => 'system', 'type' => 'text']);

    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put('/admin/settings', [
        'ga4_property_id' => 'HACKED-ID',
        'ga4_service_account_key' => '{"private_key":"hacked"}',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'ga4_property_id')->value('value'))->toBe('ORIGINAL-ID');
});

test('public settings endpoint exposes adsense identifiers but never system groups', function () {
    Setting::updateOrCreate(['key' => 'adsense_enabled'], ['value' => '1', 'group' => 'adsense', 'type' => 'boolean']);
    Setting::updateOrCreate(['key' => 'adsense_client_id'], ['value' => 'ca-pub-1234567890123456', 'group' => 'adsense', 'type' => 'text']);
    Setting::updateOrCreate(['key' => 'mail_password'], ['value' => 'smtp-secret', 'group' => 'system', 'type' => 'text']);
    Setting::updateOrCreate(['key' => 'ga4_property_id'], ['value' => '123456789', 'group' => 'system', 'type' => 'text']);
    Setting::updateOrCreate(['key' => 'ga4_service_account_key'], ['value' => '{"private_key":"secret-key","client_email":"ga4@test.iam.gserviceaccount.com"}', 'group' => 'system', 'type' => 'textarea']);

    $response = $this->getJson('/api/v1/settings');

    $response->assertOk();

    $data = $response->json('data');

    expect($data)->toHaveKey('adsense')
        ->and($data['adsense']['adsense_enabled'])->toBeTrue()
        ->and($data['adsense']['adsense_client_id'])->toBe('ca-pub-1234567890123456')
        ->and($data)->not->toHaveKey('system')
        ->and(collect($data)->flatten()->all())->not->toContain('smtp-secret')
        ->and(collect($data)->flatten()->all())->not->toContain('secret-key');
});
