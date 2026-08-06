<?php

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\FaqService;
use App\Services\TestimonialService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('team member update syncs the id translation to the linked user account', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $staff = User::factory()->staff()->create();

    $team = TeamMember::create([
        'user_id' => $staff->id,
        'name' => $staff->name,
        'position' => 'Content Editor',
        'email' => $staff->email,
        'is_active' => true,
    ]);

    $this->actingAs($admin)->put(route('admin.team.update', $team), [
        'name' => ['id' => 'Nama Baru', 'en' => 'New Name'],
        'position' => ['id' => 'Editor Konten', 'en' => 'Content Editor'],
    ])->assertRedirect(route('admin.team.index'));

    expect($staff->refresh()->name)->toBe('Nama Baru')
        ->and($staff->refresh()->position)->toBe('Editor Konten');
});

test('settings update persists translatable values as a per-locale json payload', function () {
    Setting::create(['key' => 'site_name', 'value' => 'Old', 'group' => 'general', 'type' => 'text']);
    Setting::create(['key' => 'phone', 'value' => '+62 812 1111 2222', 'group' => 'contact', 'type' => 'text']);

    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->put('/admin/settings', [
        'site_name' => ['id' => 'Brava', 'en' => 'Brava English'],
        'phone' => '+62 812 3456 7890',
    ])->assertRedirect('/admin/settings');

    $siteName = Setting::where('key', 'site_name')->first();
    $phone = Setting::where('key', 'phone')->first();

    expect($siteName->getTranslation('value', 'id', false))->toBe('Brava')
        ->and($siteName->getTranslation('value', 'en', false))->toBe('Brava English')
        ->and($phone->value)->toBe('+62 812 3456 7890')
        ->and($phone->getRawOriginal('value'))->toBe('+62 812 3456 7890');
});

test('public settings api returns locale-aware translatable values', function () {
    Setting::create(['key' => 'site_name', 'value' => ['id' => 'Brava', 'en' => 'Brava English'], 'group' => 'general', 'type' => 'text']);

    $this->getJson('/api/v1/settings?lang=id')
        ->assertOk()
        ->assertJsonPath('data.general.site_name', 'Brava');

    $this->getJson('/api/v1/settings?lang=en')
        ->assertOk()
        ->assertJsonPath('data.general.site_name', 'Brava English');
});

test('settings index renders bilingual inputs for translatable settings', function () {
    Setting::create(['key' => 'site_name', 'value' => ['id' => 'Brava', 'en' => 'Brava English'], 'group' => 'general', 'type' => 'text']);
    Setting::create(['key' => 'phone', 'value' => '+62 812 1111 2222', 'group' => 'contact', 'type' => 'text']);

    $superadmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

    $this->actingAs($superadmin)->get('/admin/settings')
        ->assertOk()
        ->assertSee('name="site_name[id]"', false)
        ->assertSee('name="site_name[en]"', false)
        ->assertSee('Brava English')
        ->assertSee('name="phone"', false);
});

test('faq and testimonial caches are scoped per locale', function () {
    app()->setLocale('id');

    app(FaqService::class)->all();
    app(TestimonialService::class)->all();

    expect(Cache::store('api')->has('faqs.all.id'))->toBeTrue()
        ->and(Cache::store('api')->has('faqs.all'))->toBeFalse()
        ->and(Cache::store('api')->has('testimonials.all.id'))->toBeTrue()
        ->and(Cache::store('api')->has('testimonials.all'))->toBeFalse();
});
