<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\Setting;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderByDimension;
use Google\Analytics\Data\V1beta\OrderByMetric;
use Google\Analytics\Data\V1beta\RunRealtimeReportRequest;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    private const COLOR_MAP = [
        'instagram' => '#ec4899',
        'instagram.com' => '#ec4899',
        'l.instagram.com' => '#ec4899',
        'tiktok' => '#111827',
        'tiktok.com' => '#111827',
        'whatsapp' => '#22c55e',
        'wa.me' => '#22c55e',
        'facebook' => '#3b82f6',
        'facebook.com' => '#3b82f6',
        'm.facebook.com' => '#3b82f6',
        'l.facebook.com' => '#3b82f6',
        'twitter' => '#1d9bf0',
        'twitter.com' => '#1d9bf0',
        't.co' => '#1d9bf0',
        'youtube' => '#ef4444',
        'youtube.com' => '#ef4444',
        'linkedin' => '#0a66c2',
        'linkedin.com' => '#0a66c2',
        'telegram' => '#26a5e4',
        'google' => '#4f46e5',
        'bing' => '#10b981',
        '(direct)' => '#06b6d4',
        'direct' => '#06b6d4',
        'email' => '#f59e0b',
        'mail' => '#f59e0b',
    ];

    private const FALLBACK_COLOR = '#6b7280';

    private const SOURCE_LABELS = [
        'instagram' => 'Instagram',
        'instagram.com' => 'Instagram',
        'l.instagram.com' => 'Instagram',
        'tiktok' => 'TikTok',
        'tiktok.com' => 'TikTok',
        'whatsapp' => 'WhatsApp',
        'wa.me' => 'WhatsApp',
        'facebook' => 'Facebook',
        'facebook.com' => 'Facebook',
        'm.facebook.com' => 'Facebook',
        'l.facebook.com' => 'Facebook',
        'twitter' => 'Twitter / X',
        'twitter.com' => 'Twitter / X',
        't.co' => 'Twitter / X',
        'youtube' => 'YouTube',
        'youtube.com' => 'YouTube',
        'linkedin' => 'LinkedIn',
        'linkedin.com' => 'LinkedIn',
        'telegram' => 'Telegram',
        'google' => 'Google',
        'bing' => 'Bing',
        '(direct)' => 'Direct',
        'direct' => 'Direct',
        'email' => 'Email',
        'mail' => 'Email',
    ];

    private ?BetaAnalyticsDataClient $client = null;

    public function getOverview(int $days = 30): array
    {
        if (! $this->isReady()) {
            return $this->normalize($this->dummyOverview($days));
        }

        $cacheKey = "analytics.overview.{$days}";

        $result = Cache::store('api')->flexible($cacheKey, $this->ttl(), fn () => $this->fetchFromGA($days));

        return $this->normalize($result);
    }

    private function normalize(array $result): array
    {
        $result['visitorTrend'] = collect($result['visitorTrend'] ?? []);
        $result['sources'] = collect($result['sources'] ?? []);
        $result['devices'] = collect($result['devices'] ?? []);
        $result['topPages'] = collect($result['topPages'] ?? []);
        $result['geoStats'] = collect($result['geoStats'] ?? []);

        return $result;
    }

    public function isReady(): bool
    {
        return ! empty($this->propertyId()) && ! empty($this->serviceAccountKeyData());
    }

    /**
     * GA4 property ID. Prefers the CMS `system` setting (plug-and-play via the
     * admin panel); falls back to the `GA4_PROPERTY_ID` env / config value.
     */
    private function propertyId(): string
    {
        $fromSettings = Setting::query()->where('key', 'ga4_property_id')->value('value');

        return is_string($fromSettings) && $fromSettings !== ''
            ? $fromSettings
            : (string) config('analytics.property_id', '');
    }

    /**
     * Decoded service-account key. Prefers the CMS `system` setting (full JSON
     * pasted in the admin panel); falls back to the key file path from config.
     */
    private function serviceAccountKeyData(): ?array
    {
        $fromSettings = Setting::query()->where('key', 'ga4_service_account_key')->value('value');

        if (is_string($fromSettings) && $fromSettings !== '') {
            $decoded = json_decode($fromSettings, true);

            if (is_array($decoded) && ! empty($decoded)) {
                return $decoded;
            }
        }

        $keyPath = config('analytics.service_account_key');

        if (is_string($keyPath) && $keyPath !== '' && file_exists($keyPath)) {
            $decoded = json_decode((string) file_get_contents($keyPath), true);

            return is_array($decoded) && ! empty($decoded) ? $decoded : null;
        }

        return null;
    }

    private function ttl(): array
    {
        return [
            config('analytics.cache_ttl.fresh', 120) * 60,
            config('analytics.cache_ttl.stale', 240) * 60,
        ];
    }

    private function client(): BetaAnalyticsDataClient
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $keyData = $this->serviceAccountKeyData();

        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/analytics.readonly'],
            $keyData,
        );

        $this->client = new BetaAnalyticsDataClient([
            'credentials' => $credentials,
        ]);

        return $this->client;
    }

    private function property(): string
    {
        return 'properties/'.$this->propertyId();
    }

    /**
     * Near-real-time snapshot of active visitors (last ~30 minutes).
     * Returns null when GA4 reporting is not configured or the API call fails,
     * so the dashboard widget simply hides instead of erroring.
     */
    public function getRealtime(): ?array
    {
        if (! $this->isReady()) {
            return null;
        }

        return Cache::store('api')->flexible('analytics.realtime', [60, 180], function () {
            try {
                return $this->fetchRealtime();
            } catch (\Throwable $e) {
                report($e);
                Cache::store('api')->forget('analytics.realtime');

                return null;
            }
        });
    }

    private function fetchRealtime(): array
    {
        $totalRequest = (new RunRealtimeReportRequest)
            ->setProperty($this->property())
            ->setMetrics([new Metric(['name' => 'activeUsers'])]);

        $activeUsers = 0;
        $totalRows = $this->client()->runRealtimeReport($totalRequest)->getRows();

        if (! empty($totalRows)) {
            $activeUsers = (int) $totalRows[0]->getMetricValues()[0]->getValue();
        }

        $pagesRequest = (new RunRealtimeReportRequest)
            ->setProperty($this->property())
            ->setDimensions([new Dimension(['name' => 'unregisteredPagePath'])])
            ->setMetrics([new Metric(['name' => 'activeUsers'])])
            ->setLimit(10);

        $topPages = [];

        foreach ($this->client()->runRealtimeReport($pagesRequest)->getRows() as $row) {
            $topPages[] = [
                'pagePath' => $row->getDimensionValues()[0]->getValue(),
                'activeUsers' => (int) $row->getMetricValues()[0]->getValue(),
            ];
        }

        return [
            'activeUsers' => $activeUsers,
            'topPages' => $topPages,
            'updatedAt' => Carbon::now()->timestamp,
        ];
    }

    private function runReport(array $dimensions, array $metrics, string $startDate, string $endDate, ?array $orderBy = null, ?int $limit = null): array
    {
        $request = (new RunReportRequest)
            ->setProperty($this->property())
            ->setDimensions(array_map(fn (string $name) => new Dimension(['name' => $name]), $dimensions))
            ->setMetrics(array_map(fn (string $name) => new Metric(['name' => $name]), $metrics))
            ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])]);

        if ($orderBy !== null) {
            $orderByObj = new OrderBy;
            if (isset($orderBy['dimension'])) {
                $orderByObj->setDimension(new OrderByDimension(['dimension_name' => $orderBy['dimension']]));
            } elseif (isset($orderBy['metric'])) {
                $orderByObj->setMetric(new OrderByMetric(['metric_name' => $orderBy['metric']]));
            }
            $orderByObj->setDesc($orderBy['desc'] ?? true);
            $request->setOrderBys([$orderByObj]);
        }

        if ($limit !== null) {
            $request->setLimit($limit);
        }

        $response = $this->client()->runReport($request);
        $rows = [];

        foreach ($response->getRows() as $row) {
            $entry = [];

            foreach ($row->getDimensionValues() as $i => $value) {
                $entry[$dimensions[$i]] = $value->getValue();
            }

            foreach ($row->getMetricValues() as $i => $value) {
                $entry[$metrics[$i]] = $value->getValue();
            }

            $rows[] = $entry;
        }

        return $rows;
    }

    private function fetchFromGA(int $days): array
    {
        $startDate = Carbon::today()->subDays($days)->format('Y-m-d');
        $endDate = Carbon::yesterday()->format('Y-m-d');
        $todayStr = Carbon::today()->format('Y-m-d');
        $yesterdayStr = Carbon::yesterday()->format('Y-m-d');

        $statsRows = $this->runReport(
            dimensions: ['date'],
            metrics: ['activeUsers', 'screenPageViews', 'sessions', 'bounceRate', 'averageSessionDuration'],
            startDate: $startDate,
            endDate: $endDate,
            orderBy: ['dimension' => 'date', 'desc' => false],
        );

        $todayRows = $this->runReport(
            dimensions: ['date'],
            metrics: ['activeUsers', 'screenPageViews', 'sessions', 'bounceRate', 'averageSessionDuration'],
            startDate: $todayStr,
            endDate: $todayStr,
        );

        $visitorTrend = collect($statsRows)->map(function (array $row) {
            try {
                $dateObj = Carbon::createFromFormat('Ymd', $row['date']);
            } catch (\Exception $e) {
                $dateObj = Carbon::parse($row['date']);
            }

            return [
                'date' => $dateObj->format('M d'),
                'visitors' => (int) ($row['activeUsers'] ?? 0),
                'pageviews' => (int) ($row['screenPageViews'] ?? 0),
                'sessions' => (int) ($row['sessions'] ?? 0),
            ];
        });

        $totalVisitors = $visitorTrend->sum('visitors');
        $totalPageviews = $visitorTrend->sum('pageviews');
        $totalSessions = $visitorTrend->sum('sessions');

        $today = $todayRows[0] ?? [];
        $yesterday = count($statsRows) > 0 ? end($statsRows) : [];

        $sourceRows = $this->runReport(
            dimensions: ['sessionSource'],
            metrics: ['sessions'],
            startDate: $startDate,
            endDate: $endDate,
            orderBy: ['metric' => 'sessions', 'desc' => true],
            limit: 10,
        );

        $sources = collect($sourceRows)
            ->filter(fn (array $row) => ! empty($row['sessionSource']))
            ->map(fn (array $row) => [
                'source' => $this->sourceLabel($row['sessionSource']),
                'sessions' => (int) ($row['sessions'] ?? 0),
                'color' => $this->sourceColor($row['sessionSource']),
            ])
            ->groupBy('source')
            ->map(fn (Collection $group) => [
                'source' => $group->first()['source'],
                'sessions' => $group->sum('sessions'),
                'color' => $group->first()['color'],
            ])
            ->values();

        $totalSessionsSources = $sources->sum('sessions');

        $deviceRows = $this->runReport(
            dimensions: ['deviceCategory'],
            metrics: ['sessions'],
            startDate: $startDate,
            endDate: $endDate,
            orderBy: ['metric' => 'sessions', 'desc' => true],
        );

        $deviceColorMap = ['mobile' => '#4f46e5', 'desktop' => '#06b6d4', 'tablet' => '#f59e0b'];
        $devices = collect($deviceRows)
            ->filter(fn (array $row) => ! empty($row['deviceCategory']))
            ->map(fn (array $row) => [
                'device' => ucfirst($row['deviceCategory']),
                'sessions' => (int) ($row['sessions'] ?? 0),
                'color' => $deviceColorMap[$row['deviceCategory']] ?? self::FALLBACK_COLOR,
            ])
            ->values();

        $totalSessionsDevices = $devices->sum('sessions');

        $topPageRows = $this->runReport(
            dimensions: ['pagePath', 'pageTitle'],
            metrics: ['screenPageViews', 'averageEngagementTime'],
            startDate: $startDate,
            endDate: $endDate,
            orderBy: ['metric' => 'screenPageViews', 'desc' => true],
            limit: 10,
        );

        $topPages = collect($topPageRows)
            ->filter(fn (array $row) => ! empty($row['pagePath']))
            ->map(fn (array $row) => [
                'page' => $row['pagePath'],
                'title' => ! empty($row['pageTitle']) ? $row['pageTitle'] : $row['pagePath'],
                'views' => (int) ($row['screenPageViews'] ?? 0),
                'avgTime' => (int) round((float) ($row['averageEngagementTime'] ?? 0)),
            ]);

        $geoRows = $this->runReport(
            dimensions: ['city'],
            metrics: ['sessions'],
            startDate: $startDate,
            endDate: $endDate,
            orderBy: ['metric' => 'sessions', 'desc' => true],
            limit: 10,
        );

        $geoColors = ['#4f46e5', '#06b6d4', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#6366f1'];
        $geoStats = collect($geoRows)
            ->filter(fn (array $row) => ! empty($row['city']) && $row['city'] !== '(not set)')
            ->values()
            ->map(fn (array $row, int $i) => [
                'city' => $row['city'],
                'sessions' => (int) ($row['sessions'] ?? 0),
                'color' => $geoColors[$i % count($geoColors)],
            ]);

        $totalSessionsGeo = $geoStats->sum('sessions');

        $avgBounceRates = collect($statsRows)->pluck('bounceRate')->filter(fn ($v) => $v !== null && $v !== '');
        $avgDurations = collect($statsRows)->pluck('averageSessionDuration')->filter(fn ($v) => $v !== null && $v !== '');

        return [
            'today' => [
                'visitors' => (int) ($today['activeUsers'] ?? 0),
                'pageviews' => (int) ($today['screenPageViews'] ?? 0),
                'sessions' => (int) ($today['sessions'] ?? 0),
                'bounceRate' => round((float) ($today['bounceRate'] ?? 0), 1),
                'avgDuration' => (int) round((float) ($today['averageSessionDuration'] ?? 0)),
            ],
            'yesterday' => [
                'visitors' => (int) ($yesterday['activeUsers'] ?? 0),
                'pageviews' => (int) ($yesterday['screenPageViews'] ?? 0),
                'sessions' => (int) ($yesterday['sessions'] ?? 0),
                'bounceRate' => round((float) ($yesterday['bounceRate'] ?? 0), 1),
                'avgDuration' => (int) round((float) ($yesterday['averageSessionDuration'] ?? 0)),
            ],
            'total' => [
                'visitors' => $totalVisitors,
                'pageviews' => $totalPageviews,
                'sessions' => $totalSessions,
                'avgBounceRate' => $avgBounceRates->isNotEmpty() ? round($avgBounceRates->avg(), 1) : 0,
                'avgDuration' => $avgDurations->isNotEmpty() ? (int) round($avgDurations->avg()) : 0,
            ],
            'visitorTrend' => $visitorTrend->values()->all(),
            'sources' => $sources->map(fn (array $s) => [
                ...$s,
                'percentage' => $totalSessionsSources > 0
                    ? round($s['sessions'] / $totalSessionsSources * 100, 1)
                    : 0,
            ])->values()->all(),
            'devices' => $devices->map(fn (array $d) => [
                ...$d,
                'percentage' => $totalSessionsDevices > 0
                    ? round($d['sessions'] / $totalSessionsDevices * 100, 1)
                    : 0,
            ])->values()->all(),
            'topPages' => $topPages->sortByDesc('views')->values()->all(),
            'geoStats' => $geoStats->map(fn (array $g) => [
                ...$g,
                'percentage' => $totalSessionsGeo > 0
                    ? round($g['sessions'] / $totalSessionsGeo * 100, 1)
                    : 0,
            ])->values()->all(),
            'period' => $days,
        ];
    }

    private function dummyOverview(int $days): array
    {
        $visitorTrend = collect(range($days - 1, 0))->map(function (int $i) {
            $date = Carbon::today()->subDays($i);

            return [
                'date' => $date->format('M d'),
                'visitors' => rand(40, 200),
                'pageviews' => rand(100, 600),
                'sessions' => rand(50, 250),
            ];
        });

        $totalVisitors = $visitorTrend->sum('visitors');
        $totalPageviews = $visitorTrend->sum('pageviews');
        $totalSessions = $visitorTrend->sum('sessions');

        $today = $visitorTrend->last() ?? ['visitors' => 0, 'pageviews' => 0, 'sessions' => 0];
        $yesterday = $visitorTrend->slice(-2, 1)->first() ?? $today;

        $sources = collect([
            ['source' => 'Google', 'sessions' => rand(150, 400), 'color' => '#4f46e5'],
            ['source' => 'Instagram', 'sessions' => rand(80, 200), 'color' => '#ec4899'],
            ['source' => 'Direct', 'sessions' => rand(60, 180), 'color' => '#06b6d4'],
            ['source' => 'TikTok', 'sessions' => rand(40, 150), 'color' => '#111827'],
            ['source' => 'WhatsApp', 'sessions' => rand(30, 100), 'color' => '#22c55e'],
            ['source' => 'Facebook', 'sessions' => rand(20, 80), 'color' => '#3b82f6'],
            ['source' => 'Email', 'sessions' => rand(10, 50), 'color' => '#f59e0b'],
            ['source' => 'YouTube', 'sessions' => rand(5, 30), 'color' => '#ef4444'],
        ]);

        $devices = collect([
            ['device' => 'Mobile', 'sessions' => rand(300, 600), 'color' => '#4f46e5'],
            ['device' => 'Desktop', 'sessions' => rand(150, 350), 'color' => '#06b6d4'],
            ['device' => 'Tablet', 'sessions' => rand(20, 80), 'color' => '#f59e0b'],
        ]);

        $pages = [
            ['page' => '/', 'title' => 'Beranda', 'views' => rand(150, 300), 'avgTime' => rand(60, 180)],
            ['page' => '/services', 'title' => 'Layanan Kami', 'views' => rand(120, 260), 'avgTime' => rand(90, 200)],
            ['page' => '/portfolio', 'title' => 'Portofolio & Karya', 'views' => rand(100, 230), 'avgTime' => rand(120, 240)],
            ['page' => '/blog', 'title' => 'Artikel & Berita', 'views' => rand(70, 160), 'avgTime' => rand(90, 180)],
            ['page' => '/about', 'title' => 'Tentang Kami', 'views' => rand(50, 130), 'avgTime' => rand(70, 150)],
            ['page' => '/contact', 'title' => 'Hubungi Kami', 'views' => rand(30, 90), 'avgTime' => rand(60, 120)],
        ];

        try {
            if ($service = Service::inRandomOrder()->first()) {
                $pages[] = [
                    'page' => '/services/'.$service->slug,
                    'title' => $service->title,
                    'views' => rand(80, 190),
                    'avgTime' => rand(100, 220),
                ];
            }
            if ($portfolio = PortfolioItem::inRandomOrder()->first()) {
                $pages[] = [
                    'page' => '/portfolio/'.$portfolio->slug,
                    'title' => $portfolio->title,
                    'views' => rand(60, 150),
                    'avgTime' => rand(110, 210),
                ];
            }
            if ($blog = Blog::inRandomOrder()->first()) {
                $pages[] = [
                    'page' => '/blog/'.$blog->slug,
                    'title' => $blog->title,
                    'views' => rand(40, 120),
                    'avgTime' => rand(90, 190),
                ];
            }
        } catch (\Throwable $e) {
            // Fallback gracefully if database table is not ready during tests
        }

        $topPages = collect($pages);

        $geoStats = collect([
            ['city' => 'Jakarta', 'sessions' => rand(100, 300), 'color' => '#4f46e5'],
            ['city' => 'Surabaya', 'sessions' => rand(50, 150), 'color' => '#06b6d4'],
            ['city' => 'Bandung', 'sessions' => rand(40, 120), 'color' => '#f59e0b'],
            ['city' => 'Medan', 'sessions' => rand(30, 80), 'color' => '#10b981'],
            ['city' => 'Semarang', 'sessions' => rand(20, 60), 'color' => '#ef4444'],
            ['city' => 'Makassar', 'sessions' => rand(10, 40), 'color' => '#8b5cf6'],
            ['city' => 'Yogyakarta', 'sessions' => rand(15, 50), 'color' => '#ec4899'],
            ['city' => 'Palembang', 'sessions' => rand(10, 30), 'color' => '#14b8a6'],
        ]);

        $totalSessionsSources = $sources->sum('sessions');
        $totalSessionsDevices = $devices->sum('sessions');
        $totalSessionsGeo = $geoStats->sum('sessions');

        return [
            'today' => [
                'visitors' => $today['visitors'],
                'pageviews' => $today['pageviews'],
                'sessions' => $today['sessions'],
                'bounceRate' => round(rand(35, 65) + rand(0, 99) / 100, 1),
                'avgDuration' => rand(90, 240),
            ],
            'yesterday' => [
                'visitors' => $yesterday['visitors'],
                'pageviews' => $yesterday['pageviews'],
                'sessions' => $yesterday['sessions'],
                'bounceRate' => round(rand(35, 65) + rand(0, 99) / 100, 1),
                'avgDuration' => rand(90, 240),
            ],
            'total' => [
                'visitors' => $totalVisitors,
                'pageviews' => $totalPageviews,
                'sessions' => $totalSessions,
                'avgBounceRate' => round(rand(40, 60) + rand(0, 99) / 100, 1),
                'avgDuration' => rand(120, 200),
            ],
            'visitorTrend' => $visitorTrend->values()->all(),
            'sources' => $sources->map(fn (array $s) => [
                ...$s,
                'percentage' => $totalSessionsSources > 0
                    ? round($s['sessions'] / $totalSessionsSources * 100, 1)
                    : 0,
            ])->values()->all(),
            'devices' => $devices->map(fn (array $d) => [
                ...$d,
                'percentage' => $totalSessionsDevices > 0
                    ? round($d['sessions'] / $totalSessionsDevices * 100, 1)
                    : 0,
            ])->values()->all(),
            'topPages' => $topPages->sortByDesc('views')->values()->all(),
            'geoStats' => $geoStats->map(fn (array $g) => [
                ...$g,
                'percentage' => $totalSessionsGeo > 0
                    ? round($g['sessions'] / $totalSessionsGeo * 100, 1)
                    : 0,
            ])->values()->all(),
            'period' => $days,
        ];
    }

    private function sourceLabel(string $source): string
    {
        return self::SOURCE_LABELS[strtolower($source)] ?? ucfirst($source);
    }

    private function sourceColor(string $source): string
    {
        return self::COLOR_MAP[strtolower($source)] ?? self::FALLBACK_COLOR;
    }
}
