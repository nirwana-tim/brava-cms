<x-admin.layouts.app>
    <x-slot name="title">{{ __('Admin Dashboard') }}</x-slot>

    {{-- CMS Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['services'] }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Services</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['blogs'] }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Blog Posts</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['categories'] }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Categories</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['users'] }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Users</div>
        </div>
    </div>

    {{-- Analytics Section --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-1" style="color: var(--heading-text)">Analytics Ringkasan</h2>
        @if ($isDummy)
            <p class="text-sm mb-4" style="color: var(--muted-text)">
                <span class="text-amber-600 dark:text-amber-400">Data dummy</span> —
                atur <code class="px-1 py-0.5 rounded text-xs font-mono" style="background-color: var(--table-header-bg); color: var(--table-text)">GA4_PROPERTY_ID</code>
                di <code class="px-1 py-0.5 rounded text-xs font-mono" style="background-color: var(--table-header-bg); color: var(--table-text)">.env</code>
                untuk data sungguhan.
            </p>
        @else
            <p class="text-sm mb-4" style="color: var(--muted-text)">
                Data diperbarui setiap {{ config('analytics.cache_ttl.fresh', 120) }} menit.
            </p>
        @endif
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="card">
            <div class="card-body">
                <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Visitors Today</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">{{ number_format($data['today']['visitors']) }}</p>
                <p class="text-xs mt-1" style="color: var(--muted-text)">
                    vs yesterday:
                    @php $diff = $data['today']['visitors'] - $data['yesterday']['visitors']; @endphp
                    <span style="color: {{ $diff >= 0 ? 'var(--flash-success-text)' : 'var(--flash-error-text)' }}">
                        {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                    </span>
                </p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Pageviews Today</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">{{ number_format($data['today']['pageviews']) }}</p>
                <p class="text-xs mt-1" style="color: var(--muted-text)">30H: {{ number_format($data['total']['pageviews']) }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Sessions Today</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">{{ number_format($data['today']['sessions']) }}</p>
                <p class="text-xs mt-1" style="color: var(--muted-text)">30H: {{ number_format($data['total']['sessions']) }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Bounce Rate</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">{{ $data['today']['bounceRate'] }}%</p>
                <p class="text-xs mt-1" style="color: var(--muted-text)">Avg 30H: {{ $data['total']['avgBounceRate'] }}%</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Avg Duration</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--heading-text)">{{ gmdate('i:s', $data['today']['avgDuration']) }}</p>
                <p class="text-xs mt-1" style="color: var(--muted-text)">Avg 30H: {{ gmdate('i:s', $data['total']['avgDuration']) }}</p>
            </div>
        </div>
    </div>

    {{-- Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Visitor Trend --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Visitor Trend (30H)</h3>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <canvas id="trafficSourcesChart" height="200"></canvas>
                    <div class="flex flex-col justify-center gap-2">
                        @foreach ($data['sources'] as $source)
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full inline-block" style="background-color: {{ $source['color'] }}"></span>
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

        {{-- Top Pages --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold" style="color: var(--heading-text)">Top Pages</h3>
            </div>
            <div class="card-body p-0">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--table-border)">
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Page</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Views</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Avg Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['topPages'] as $page)
                            <tr style="border-bottom: 1px solid var(--table-border)">
                                <td class="px-4 py-3 text-sm" style="color: var(--table-text)">
                                    <span class="font-medium">{{ $page['title'] }}</span>
                                    <p class="text-xs" style="color: var(--muted-text)">{{ $page['page'] }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-semibold" style="color: var(--heading-text)">{{ number_format($page['views']) }}</td>
                                <td class="px-4 py-3 text-sm text-right" style="color: var(--muted-text)">{{ gmdate('i:s', $page['avgTime']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Charts Row 3 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                                <span style="color: var(--muted-text)">{{ $geo['percentage'] }}% ({{ $geo['sessions'] }})</span>
                            </div>
                            <div class="w-full rounded-full h-2" style="background-color: var(--table-header-bg)">
                                <div class="h-2 rounded-full" style="width: {{ $geo['percentage'] }}%; background-color: {{ $geo['color'] }}; transition: width 0.5s ease"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
                <div class="p-4 rounded-lg" style="background-color: var(--sidebar-link-active-bg)">
                    <p class="text-sm font-semibold" style="color: var(--sidebar-link-active-text)">Top Channel</p>
                    <p class="text-lg font-bold mt-1" style="color: var(--heading-text)">{{ $topSource['source'] ?? '-' }}</p>
                    <p class="text-xs" style="color: var(--muted-text)">{{ $topSource['percentage'] ?? 0 }}% dari total traffic</p>
                </div>
                <div class="p-4 rounded-lg" style="background-color: var(--badge-draft-bg)">
                    <p class="text-sm font-semibold" style="color: var(--badge-draft-text)">Dominan Device</p>
                    <p class="text-lg font-bold mt-1" style="color: var(--heading-text)">{{ $topDevice['device'] ?? '-' }}</p>
                    <p class="text-xs" style="color: var(--muted-text)">{{ $topDevice['percentage'] ?? 0 }}% pengguna via {{ $topDevice['device'] ?? '-' }}</p>
                </div>
                <div class="p-4 rounded-lg" style="background-color: var(--flash-success-bg)">
                    <p class="text-sm font-semibold" style="color: var(--flash-success-text)">Kota Teraktif</p>
                    <p class="text-lg font-bold mt-1" style="color: var(--heading-text)">{{ $topCity['city'] ?? '-' }}</p>
                    <p class="text-xs" style="color: var(--muted-text)">{{ $topCity['percentage'] ?? 0 }}% traffic dari {{ $topCity['city'] ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#6b7280';
        const gridColor = isDark ? '#374151' : '#e5e7eb';

        const visitorTrend = document.getElementById('visitorTrendChart');
        if (visitorTrend) {
            new Chart(visitorTrend, {
                type: 'line',
                data: {
                    labels: @json($data['visitorTrend']->pluck('date')),
                    datasets: [
                        {
                            label: 'Visitors',
                            data: @json($data['visitorTrend']->pluck('visitors')),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 3,
                        },
                        {
                            label: 'Pageviews',
                            data: @json($data['visitorTrend']->pluck('pageviews')),
                            borderColor: '#06b6d4',
                            backgroundColor: 'rgba(6, 182, 212, 0.1)',
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
                            labels: { color: textColor, font: { size: 12 } },
                        },
                    },
                    scales: {
                        x: {
                            ticks: { color: textColor, font: { size: 11 } },
                            grid: { color: gridColor },
                        },
                        y: {
                            ticks: { color: textColor, font: { size: 11 } },
                            grid: { color: gridColor },
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
                    maintainAspectRatio: true,
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
                            ticks: { color: textColor, font: { size: 12 } },
                            grid: { display: false },
                        },
                        y: {
                            ticks: { color: textColor, font: { size: 11 } },
                            grid: { color: gridColor },
                            beginAtZero: true,
                        },
                    },
                },
            });
        }
    });
    </script>
    @endpush
</x-admin.layouts.app>
