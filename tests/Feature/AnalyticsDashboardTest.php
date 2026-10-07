<?php

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use App\Services\AnalyticsService;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

test('analytics service becomes ready from system settings without env config', function () {
    config(['analytics.property_id' => null]);
    config(['analytics.service_account_key' => null]);

    Setting::updateOrCreate(['key' => 'ga4_property_id'], ['value' => '123456789', 'group' => 'system', 'type' => 'text']);
    Setting::updateOrCreate(
        ['key' => 'ga4_service_account_key'],
        [
            'value' => json_encode(['type' => 'service_account', 'client_email' => 'ga4@test.iam.gserviceaccount.com']),
            'group' => 'system',
            'type' => 'textarea',
        ],
    );

    $service = new AnalyticsService;

    expect($service->isReady())->toBeTrue();
});

test('analytics realtime returns null when reporting is not configured', function () {
    config(['analytics.property_id' => null]);
    config(['analytics.service_account_key' => null]);

    $service = new AnalyticsService;

    expect($service->getRealtime())->toBeNull();
});

test('admin dashboard renders analytics section successfully', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('Summary Analytics');
    $response->assertSeeText('Visitors Today');
    $response->assertViewHas('data');
    $response->assertViewHas('isDummy');
    $response->assertViewHas('realtime', null);
    $response->assertDontSee('Pengunjung Aktif Sekarang');
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

test('ga4 orderby classes can be instantiated correctly', function () {
    $dimOrderBy = new OrderBy;
    $dimOrderBy->setDimension(new DimensionOrderBy(['dimension_name' => 'date']));

    $metricOrderBy = new OrderBy;
    $metricOrderBy->setMetric(new MetricOrderBy(['metric_name' => 'sessions']));

    expect($dimOrderBy->getDimension()->getDimensionName())->toBe('date')
        ->and($metricOrderBy->getMetric()->getMetricName())->toBe('sessions');
});
