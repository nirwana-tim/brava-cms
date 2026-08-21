<?php

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setting::create(['key' => 'site_name', 'value' => 'Brava CMS', 'group' => 'general', 'type' => 'text']);
    Setting::create(['key' => 'facebook_url', 'value' => '', 'group' => 'social', 'type' => 'text']);
    Setting::create(['key' => 'whatsapp_number', 'value' => '', 'group' => 'contact', 'type' => 'text']);
});

test('superadmin receives validation error for invalid social url', function () {
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->from('/admin/settings')->put('/admin/settings', [
        'facebook_url' => 'not-a-url',
    ])->assertSessionHasErrors('facebook_url');
});

test('empty social url values are allowed', function () {
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->from('/admin/settings')->put('/admin/settings', [
        'facebook_url' => '',
    ])->assertSessionHasNoErrors();

    expect(in_array(Setting::where('key', 'facebook_url')->value('value'), ['', null], true))->toBeTrue();
});

test('superadmin receives validation error for malformed adsense publisher id', function () {
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->from('/admin/settings')->put('/admin/settings', [
        'adsense_client_id' => 'ca-pub-invalid',
    ])->assertSessionHasErrors('adsense_client_id');
});

test('superadmin receives validation error for non numeric ga4 property id', function () {
    Setting::updateOrCreate(['key' => 'ga4_property_id'], ['value' => '', 'group' => 'system', 'type' => 'text']);
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->from('/admin/settings')->put('/admin/settings', [
        'ga4_property_id' => 'abc-123',
    ])->assertSessionHasErrors('ga4_property_id');
});

test('superadmin receives validation error for invalid ga4 service account key json', function () {
    Setting::updateOrCreate(['key' => 'ga4_service_account_key'], ['value' => '', 'group' => 'system', 'type' => 'textarea']);
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->from('/admin/settings')->put('/admin/settings', [
        'ga4_service_account_key' => 'not json {',
    ])->assertSessionHasErrors('ga4_service_account_key');
});

test('superadmin receives validation error for malformed whatsapp number', function () {
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->from('/admin/settings')->put('/admin/settings', [
        'whatsapp_number' => '+62 812-3456',
    ])->assertSessionHasErrors('whatsapp_number');
});

test('superadmin receives validation error for invalid google analytics id', function () {
    Setting::updateOrCreate(['key' => 'google_analytics_id'], ['value' => '', 'group' => 'seo', 'type' => 'text']);
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->from('/admin/settings')->put('/admin/settings', [
        'google_analytics_id' => 'not-an-id',
    ])->assertSessionHasErrors('google_analytics_id');
});

test('valid values pass validation and are persisted', function () {
    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->put('/admin/settings', [
        'facebook_url' => 'https://facebook.com/brava',
        'whatsapp_number' => '6281234567890',
        'adsense_client_id' => 'ca-pub-1234567890123456',
        'google_analytics_id' => 'G-ABCDEF123',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'facebook_url')->value('value'))->toBe('https://facebook.com/brava')
        ->and(Setting::where('key', 'whatsapp_number')->value('value'))->toBe('6281234567890')
        ->and(Setting::where('key', 'adsense_client_id')->value('value'))->toBe('ca-pub-1234567890123456')
        ->and(Setting::where('key', 'google_analytics_id')->value('value'))->toBe('G-ABCDEF123');
});

test('non translatable json settings keep full payload when id key exists', function () {
    $schema = Setting::updateOrCreate(['key' => 'organization_schema'], ['value' => '{"id":"org-1","@type":"Organization","name":"Brava"}', 'group' => 'seo', 'type' => 'textarea']);
    $ga4 = Setting::updateOrCreate(['key' => 'ga4_service_account_key'], ['value' => '{"client_id":"123","private_key":"secret"}', 'group' => 'system', 'type' => 'textarea']);

    expect($schema->fresh()->value)->toBe('{"id":"org-1","@type":"Organization","name":"Brava"}')
        ->and($ga4->fresh()->value)->toBe('{"client_id":"123","private_key":"secret"}');
});

test('only translatable shaped json id payload is extracted', function () {
    $setting = Setting::create(['key' => 'hero_title', 'value' => ['id' => 'Brava', 'en' => 'Brava'], 'group' => 'general', 'type' => 'text']);

    expect($setting->fresh()->value)->toBe('Brava');
});
