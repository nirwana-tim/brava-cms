<x-admin.layouts.app>
    <x-slot name="title">{{ __('Dashboard') }}</x-slot>

    {{-- CMS Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="rounded-2xl p-6" style="background-color: var(--brand-primary-400)">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-3xl font-bold" style="color: #ffffff">{{ $stats['services'] }}</p>
                    <p class="text-sm mt-1" style="color: rgba(255, 255, 255, 0.85)">Services</p>
                </div>
                <svg class="w-10 h-10 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="color: rgba(255, 255, 255, 0.85)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>
        <div class="rounded-2xl p-6" style="background-color: var(--brand-primary-400)">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-3xl font-bold" style="color: #ffffff">{{ $stats['blogs'] }}</p>
                    <p class="text-sm mt-1" style="color: rgba(255, 255, 255, 0.85)">Blog Posts</p>
                </div>
                <svg class="w-10 h-10 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="color: rgba(255, 255, 255, 0.85)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
        </div>
        <div class="rounded-2xl p-6" style="background-color: var(--brand-primary-400)">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-3xl font-bold" style="color: #ffffff">{{ $stats['promos'] }}</p>
                    <p class="text-sm mt-1" style="color: rgba(255, 255, 255, 0.85)">Promo</p>
                </div>
                <svg class="w-10 h-10 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="color: rgba(255, 255, 255, 0.85)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 15L15 9M9.5 9.5H9.51M14.5 14.5H14.51" />
                </svg>
            </div>
        </div>
        <div class="rounded-2xl p-6" style="background-color: var(--brand-primary-400)">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-3xl font-bold" style="color: #ffffff">{{ $stats['users'] }}</p>
                    <p class="text-sm mt-1" style="color: rgba(255, 255, 255, 0.85)">Users</p>
                </div>
                <svg class="w-10 h-10 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="color: rgba(255, 255, 255, 0.85)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    @if ($canViewAnalytics)
        {{-- Analytics Section --}}
        <div class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-1">
                <h2 class="text-lg font-semibold" style="color: var(--heading-text)">Summary Analytics</h2>
                <div class="flex flex-wrap items-center gap-1.5">
                    @foreach ([7 => '7H', 30 => '30H', 90 => '90H', 365 => '1Y'] as $value => $label)
                        <a href="{{ route('admin.dashboard', ['days' => $value]) }}"
                            class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium transition
                        {{ $days === $value ? 'bg-[var(--sidebar-link-active-bg)] text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                            style="color: {{ $days === $value ? '' : 'var(--table-text)' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
            @if ($isDummy)
                <p class="text-sm mb-4" style="color: var(--muted-text)">
                    <span class="text-amber-600 dark:text-amber-400">Data dummy</span> —
                    atur <code class="px-1 py-0.5 rounded text-xs font-mono"
                        style="background-color: var(--table-header-bg); color: var(--table-text)">GA4 Property
                        ID</code>
                    &amp; <code class="px-1 py-0.5 rounded text-xs font-mono"
                        style="background-color: var(--table-header-bg); color: var(--table-text)">Service Account
                        Key</code>
                    di <strong>Settings → Technical Settings</strong> untuk data sungguhan.
                </p>
            @else
                <p class="text-sm mb-4" style="color: var(--muted-text)">
                    Data diperbarui setiap {{ config('analytics.cache_ttl.fresh', 120) }} menit.
                </p>
            @endif

            @if ($realtime)
                <div class="card mb-6">
                    <div class="card-header flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Pengunjung Aktif Sekarang
                        </h3>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold"
                            style="color: var(--flash-success-text)">
                            <span class="w-2 h-2 rounded-full inline-block"
                                style="background-color: var(--flash-success-text); animation: pulse 1.5s infinite"></span>
                            LIVE
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div>
                                <p class="text-3xl font-bold" style="color: var(--heading-text)">
                                    {{ number_format($realtime['activeUsers']) }}</p>
                                <p class="text-xs mt-1" style="color: var(--muted-text)">pengunjung aktif dalam 30 menit
                                    terakhir</p>
                            </div>
                            <div class="lg:col-span-2">
                                @if (!empty($realtime['topPages']))
                                    <table class="w-full">
                                        <thead>
                                            <tr style="border-bottom: 1px solid var(--table-border)">
                                                <th class="text-left py-2 text-xs font-semibold uppercase tracking-wider"
                                                    style="color: var(--muted-text)">Halaman Aktif</th>
                                                <th class="text-right py-2 text-xs font-semibold uppercase tracking-wider"
                                                    style="color: var(--muted-text)">Pengguna</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($realtime['topPages'] as $page)
                                                <tr style="border-bottom: 1px solid var(--table-border)">
                                                    <td class="py-2 text-sm font-mono"
                                                        style="color: var(--table-text)">
                                                        {{ $page['pagePath'] }}</td>
                                                    <td class="py-2 text-sm text-right"
                                                        style="color: var(--muted-text)">
                                                        {{ number_format($page['activeUsers']) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-sm" style="color: var(--muted-text)">Belum ada aktivitas
                                        terdeteksi.
                                    </p>
                                @endif
                                <p class="text-xs mt-2" style="color: var(--muted-text)">Diperbarui setiap menit.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <style>
                @keyframes pulse {

                    0%,
                    100% {
                        opacity: 1;
                    }

                    50% {
                        opacity: 0.3;
                    }
                }
            </style>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">
                        Visitors
                        Today</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">
                        {{ number_format($data['today']['visitors']) }}</p>
                    <p class="text-xs mt-1" style="color: var(--muted-text)">
                        vs yesterday:
                        @php $diff = $data['today']['visitors'] - $data['yesterday']['visitors']; @endphp
                        <span
                            style="color: {{ $diff >= 0 ? 'var(--flash-success-text)' : 'var(--flash-error-text)' }}">
                            {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">
                        Pageviews
                        Today</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">
                        {{ number_format($data['today']['pageviews']) }}</p>
                    <p class="text-xs mt-1" style="color: var(--muted-text)">{{ $data['period'] }}H:
                        {{ number_format($data['total']['pageviews']) }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">
                        Sessions
                        Today</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">
                        {{ number_format($data['today']['sessions']) }}</p>
                    <p class="text-xs mt-1" style="color: var(--muted-text)">{{ $data['period'] }}H:
                        {{ number_format($data['total']['sessions']) }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Bounce
                        Rate</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">
                        {{ $data['today']['bounceRate'] }}%</p>
                    <p class="text-xs mt-1" style="color: var(--muted-text)">Avg {{ $data['period'] }}H:
                        {{ $data['total']['avgBounceRate'] }}%</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Avg
                        Duration</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">
                        {{ gmdate('i:s', $data['today']['avgDuration']) }}</p>
                    <p class="text-xs mt-1" style="color: var(--muted-text)">Avg {{ $data['period'] }}H:
                        {{ gmdate('i:s', $data['total']['avgDuration']) }}</p>
                </div>
            </div>
        </div>

        {{-- Charts Row 1 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Visitor Trend --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Visitor Trend
                        ({{ $data['period'] }}H)</h3>
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full inline-block" style="background-color: #faaf36"></span>
                            <span class="text-xs font-medium" style="color: var(--table-text)">Visitors</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full inline-block" style="background-color: #2336b7"></span>
                            <span class="text-xs font-medium" style="color: var(--table-text)">Pageviews</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="visitorTrendChart" height="200"></canvas>
                </div>
            </div>

            {{-- Traffic Sources by Platform --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Traffic Sources</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-44 h-44 shrink-0">
                            <canvas id="trafficSourcesChart"></canvas>
                        </div>
                        <div class="flex-1 w-full flex flex-col justify-center gap-3">
                            @foreach ($data['sources'] as $source)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full inline-block"
                                            style="background-color: {{ $source['color'] }}"></span>
                                        <span style="color: var(--table-text)">{{ $source['source'] }}</span>
                                    </div>
                                    <span style="color: var(--muted-text)">{{ $source['percentage'] }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row 2 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Device Breakdown --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Device Breakdown</h3>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart" height="200"></canvas>
                </div>
            </div>

            {{-- Insight Cards --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Marketing Insights</h3>
                </div>
                <div class="card-body space-y-4">
                    @php $topSource = $data['sources']->sortByDesc('percentage')->first(); @endphp
                    @php $topDevice = $data['devices']->sortByDesc('percentage')->first(); @endphp
                    @php $topCity = $data['geoStats']->sortByDesc('percentage')->first(); @endphp
                    <div class="p-4 rounded-lg" style="background-color: var(--brand-primary-300)">
                        <p class="text-sm font-semibold" style="color: var(--brand-neutral-black)">Top Channel
                        </p>
                        <p class="text-lg font-bold mt-1" style="color: var(--brand-neutral-black)">
                            {{ $topSource['source'] ?? '-' }}</p>
                        <p class="text-xs" style="color: var(--brand-neutral-black-400)">
                            {{ $topSource['percentage'] ?? 0 }}% dari
                            total traffic</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background-color: var(--brand-secondary-300)">
                        <p class="text-sm font-semibold" style="color: var(--brand-neutral-black)">Dominan Device</p>
                        <p class="text-lg font-bold mt-1" style="color: var(--brand-neutral-black)">
                            {{ $topDevice['device'] ?? '-' }}</p>
                        <p class="text-xs" style="color: var(--brand-neutral-black-400)">
                            {{ $topDevice['percentage'] ?? 0 }}%
                            pengguna via {{ $topDevice['device'] ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background-color: var(--brand-neutral-600)">
                        <p class="text-sm font-semibold" style="color: var(--brand-neutral-black)">Kota Teraktif</p>
                        <p class="text-lg font-bold mt-1" style="color: var(--brand-neutral-black)">
                            {{ $topCity['city'] ?? '-' }}</p>
                        <p class="text-xs" style="color: var(--brand-neutral-black-400)">
                            {{ $topCity['percentage'] ?? 0 }}%
                            traffic dari {{ $topCity['city'] ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row 3 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Pages --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Top Pages</h3>
                </div>
                <div class="card-body p-0">
                    <table class="w-full">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--table-border)">
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider"
                                    style="color: var(--muted-text)">Page</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider"
                                    style="color: var(--muted-text)">Views</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider"
                                    style="color: var(--muted-text)">Avg Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['topPages'] as $page)
                                <tr style="border-bottom: 1px solid var(--table-border)">
                                    <td class="px-4 py-3 text-sm" style="color: var(--table-text)">
                                        <span class="font-medium">{{ $page['title'] }}</span>
                                        <p class="text-xs" style="color: var(--muted-text)">{{ $page['page'] }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-semibold"
                                        style="color: var(--heading-text)">{{ number_format($page['views']) }}</td>
                                    <td class="px-4 py-3 text-sm text-right" style="color: var(--muted-text)">
                                        {{ gmdate('i:s', $page['avgTime']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Geo Stats --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Top Cities</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        @foreach ($data['geoStats'] as $geo)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span style="color: var(--table-text)">{{ $geo['city'] }}</span>
                                    <span style="color: var(--muted-text)">{{ $geo['percentage'] }}%
                                        ({{ $geo['sessions'] }})
                                    </span>
                                </div>
                                <div class="w-full rounded-full h-2" style="background-color: var(--table-header-bg)">
                                    <div class="h-2 rounded-full"
                                        style="width: {{ $geo['percentage'] }}%; background-color: {{ $geo['color'] }}; transition: width 0.5s ease">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($canViewAnalytics)
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const isDark = document.documentElement.classList.contains('dark');
                    const textColor = isDark ? '#9ca3af' : '#6b7280';
                    const gridColor = isDark ? '#374151' : '#e5e7eb';

                    const visitorTrend = document.getElementById('visitorTrendChart');
                    if (visitorTrend) {
                        new Chart(visitorTrend, {
                            type: 'line',
                            data: {
                                labels: @json($data['visitorTrend']->pluck('date')),
                                datasets: [{
                                        label: 'Visitors',
                                        data: @json($data['visitorTrend']->pluck('visitors')),
                                        borderColor: '#faaf36',
                                        backgroundColor: '#ffdda6',
                                        fill: true,
                                        tension: 0.3,
                                        pointRadius: 3,
                                    },
                                    {
                                        label: 'Pageviews',
                                        data: @json($data['visitorTrend']->pluck('pageviews')),
                                        borderColor: '#2336b7',
                                        backgroundColor: '#7b8cf5',
                                        fill: true,
                                        tension: 0.3,
                                        pointRadius: 3,
                                    },
                                ],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: textColor,
                                            font: {
                                                size: 11
                                            }
                                        },
                                        grid: {
                                            color: gridColor
                                        },
                                    },
                                    y: {
                                        ticks: {
                                            color: textColor,
                                            font: {
                                                size: 11
                                            }
                                        },
                                        grid: {
                                            color: gridColor
                                        },
                                        beginAtZero: true,
                                    },
                                },
                            },
                        });
                    }

                    const trafficSources = document.getElementById('trafficSourcesChart');
                    if (trafficSources) {
                        new Chart(trafficSources, {
                            type: 'doughnut',
                            data: {
                                labels: @json($data['sources']->pluck('source')),
                                datasets: [{
                                    data: @json($data['sources']->pluck('sessions')),
                                    backgroundColor: @json($data['sources']->pluck('color')),
                                    borderWidth: 0,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                },
                                cutout: '65%',
                            },
                        });
                    }

                    const deviceChart = document.getElementById('deviceChart');
                    if (deviceChart) {
                        new Chart(deviceChart, {
                            type: 'bar',
                            data: {
                                labels: @json($data['devices']->pluck('device')),
                                datasets: [{
                                    label: 'Sessions',
                                    data: @json($data['devices']->pluck('sessions')),
                                    backgroundColor: @json($data['devices']->pluck('color')),
                                    borderRadius: 6,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: textColor,
                                            font: {
                                                size: 12
                                            }
                                        },
                                        grid: {
                                            display: false
                                        },
                                    },
                                    y: {
                                        ticks: {
                                            color: textColor,
                                            font: {
                                                size: 11
                                            }
                                        },
                                        grid: {
                                            color: gridColor
                                        },
                                        beginAtZero: true,
                                    },
                                },
                            },
                        });
                    }
                });
            </script>
        @endpush
    @endif
</x-admin.layouts.app>
