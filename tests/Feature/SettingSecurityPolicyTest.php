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
