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
    $response->assertSee('⚠️ Kritis (SuperAdmin)');
    $response->assertSee('⚠️ System Branding');
});

test('normal admin cannot modify seo or general settings via put request', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put('/admin/settings', [
        'site_name' => 'Updated Brand Name',
        'google_analytics_id' => 'G-HACKED999',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'site_name')->value('value'))->toBe('Brava CMS');
    expect(Setting::where('key', 'google_analytics_id')->value('value'))->toBe('G-ORIGINAL123');
});

test('normal admin cannot modify adsense settings via put request', function () {
    Setting::where('key', 'adsense_slot_1')->update(['value' => 'ORIGINAL-SLOT']);

    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->put('/admin/settings', [
        'adsense_slot_1' => 'HACKED-SLOT',
        'adsense_client_id' => 'ca-pub-HACKED',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'adsense_slot_1')->value('value'))->toBe('ORIGINAL-SLOT');
    expect(Setting::where('key', 'adsense_client_id')->value('value'))->toBe('');
});

test('superadmin can update adsense settings via put request', function () {
    Setting::where('key', 'adsense_client_id')->update(['value' => '']);

    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->put('/admin/settings', [
        'adsense_client_id' => 'ca-pub-1234567890123456',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'adsense_client_id')->value('value'))->toBe('ca-pub-1234567890123456');
});
