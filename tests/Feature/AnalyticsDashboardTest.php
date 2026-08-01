<?php

use App\Enums\UserRole;
use App\Models\User;
use App\Services\AnalyticsService;

test('analytics service returns valid structure in dummy mode', function () {
    config(['analytics.property_id' => null]);
    config(['analytics.service_account_key' => null]);

    $service = new AnalyticsService;

    expect($service->isReady())->toBeFalse();

    $overview = $service->getOverview(30);

    expect($overview)->toHaveKeys([
        'today',
        'yesterday',
        'total',
        'visitorTrend',
        'sources',
        'devices',
        'topPages',
        'geoStats',
        'period',
    ])
        ->and($overview['period'])->toBe(30)
        ->and($overview['today'])->toHaveKeys(['visitors', 'pageviews', 'sessions', 'bounceRate', 'avgDuration'])
        ->and($overview['visitorTrend'])->not->toBeEmpty();
});

test('admin dashboard renders analytics section successfully', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('Analytics Ringkasan');
    $response->assertSee('Visitors Today');
    $response->assertViewHas('data');
    $response->assertViewHas('isDummy');
});

test('admin dashboard accepts days preset from query string', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', ['days' => 7]));

    $response->assertOk();
    $response->assertViewHas('days', 7);
    $response->assertViewHas('data', fn (array $data) => $data['period'] === 7);
    $response->assertSee('7H');
});

test('admin dashboard falls back to 30 days for invalid preset', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', ['days' => 999]));

    $response->assertOk();
    $response->assertViewHas('days', 30);
    $response->assertViewHas('data', fn (array $data) => $data['period'] === 30);
});
