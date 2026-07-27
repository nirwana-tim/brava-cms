# Brava CMS — Google Analytics Dashboard

> Plan & Reference — implement after core CMS modules are done.

---

## Overview

Tampilkan data Google Analytics 4 di dashboard admin tanpa perlu login ke GA. Data di-cache dan direfresh periodik.

---

## Prerequisites (Google Cloud)

1. **GA4 Property** — udah punya? Catat `property_id` (angka)
2. **Google Cloud Project** — buat di https://console.cloud.google.com
3. **Enable API** — `Google Analytics Data API` (GA4)
4. **Service Account** — create → download JSON key
5. **GA4 Access** — invite Service Account email sebagai **Viewer** di GA4

---

## Package

```bash
composer require google/analytics-data
```

Resmi dari Google, pake `BetaAnalyticsDataClient`, support REST (gak butuh gRPC).

---

## Service Layer

```
app/Services/AnalyticsService.php
```

### Methods

| Method | Return | Description |
|--------|--------|-------------|
| `getActiveUsers(int $days)` | array | [total, today, yesterday] |
| `getTopPages(int $days, int $limit)` | collection | halaman paling banyak dilihat |
| `getTrafficSources(int $days)` | collection | organic, direct, referral, social, email |
| `getDeviceBreakdown(int $days)` | collection | desktop, mobile, tablet |
| `getGeoStats(int $days)` | collection | sessions per country/city |
| `getSessionsOverview(int $days)` | array | sessions, bounce rate, avg duration |
| `getNewVsReturning(int $days)` | collection | new vs returning visitor ratio |
| `getOverview(int $days = 30)` | array | ringkasan semua metric buat dashboard |

### Caching

Pake `Cache::flexible()` biar gak ngehit API Google tiap refresh:

| Data | TTL (fresh) | TTL (stale) |
|------|-------------|-------------|
| overview | 30 min | 60 min |
| top_pages | 30 min | 60 min |
| traffic_sources | 1 hour | 2 hours |
| device_breakdown | 1 hour | 2 hours |
| geo_stats | 1 hour | 2 hours |

### Error Handling

- Kalo API key expired / invalid → log + return empty (dashboard tetap kebuka)
- Kalo GA4 property gak dikonfigurasi → tampil pesan "Configure Analytics"
- Jangan sampe dashboard error 500 gara-gara analytics mati

---

## Controller & Route

### Admin Route

```php
// routes/admin.php
Route::get('/admin/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
    ->middleware(['auth', 'verified']);
```

### AdminController

```php
class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analytics
    ) {}

    public function index(Request $request): View
    {
        $days = $request->get('days', 30);
        $data = $this->analytics->getOverview((int) $days);

        return view('admin.analytics.index', compact('data', 'days'));
    }
}
```

---

## Admin View

```
resources/views/admin/analytics/index.blade.php
```

### Widget Layout

```
┌─────────────────────────────────────────────────────┐
│  TODAY              VS YESTERDAY    VS 30 DAYS       │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐      │
│  │Users │ │Sessns│ │PgView│ │BncRt │ │AvgDur│      │
│  └──────┘ └──────┘ └──────┘ └──────┘ └──────┘      │
├─────────────────────────────────────────────────────┤
│  ┌────────────┐   ┌────────────┐                    │
│  │ Traffic    │   │ Device     │                    │
│  │ Sources    │   │ Breakdown  │                    │
│  │ (pie/donut)│   │ (bar)      │                    │
│  └────────────┘   └────────────┘                    │
├─────────────────────────────────────────────────────┤
│  ┌────────────────────────────────────────────────┐ │
│  │ Top Pages (sorted by pageviews)                │ │
│  │ Page URL                   | Views | Avg Time  │ │
│  │ /                          | 1200  | 2:30      │ │
│  │ /products                  | 800   | 1:45      │ │
│  │ /blogs/seo-tips            | 450   | 3:10      │ │
│  └────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────┘
```

### Chart Library

Chart.js via CDN atau NPM. Simple, gak perlu Livewire buat dashboard ini — cukup Blade + Alpine.js + Chart.js.

```bash
bun add chart.js
```

Atau dari CDN di layout admin aja.

---

## Data Flow

```
Next.js (Vercel)
  ↓ Google Analytics tracking (client-side JS)
  ↓ Data masuk ke GA4 property
  ↓
Laravel Admin Dashboard
  ↓ AnalyticsService
    ↓ BetaAnalyticsDataClient (google/analytics-data)
    ↓ GA4 Data API → response
  ↓ Cache::flexible() 30-60 min
  ↓ Return ke Blade view + Chart.js render
```

---

## Config (.env)

```env
GA4_PROPERTY_ID=123456789
GA4_SERVICE_ACCOUNT_KEY=storage/app/analytics/service-account-key.json
```

Config file:

```php
// config/analytics.php
return [
    'property_id' => env('GA4_PROPERTY_ID'),
    'service_account_key' => storage_path(env('GA4_SERVICE_ACCOUNT_KEY', 'app/analytics/service-account-key.json')),
];
```

---

## Implementation Priority

### Phase 1 — Core (after CMS modules)
1. Install `google/analytics-data`
2. Bikin `config/analytics.php`
3. Bikin `AnalyticsService` → `getOverview()` aja dulu
4. Bikin `AnalyticsController` + route admin
5. Bikin Blade view + Chart.js (3-4 widget utama)

### Phase 2 — Enhancement
- Filter date range (7d, 30d, 90d)
- Export to CSV
- Period comparison (vs previous period)
- Real-time widget (GA4 Realtime API)

---

## Notes

- **GA4 Data API ada quota**: 50,000 request per project per day — lebih dari cukup buat admin dashboard
- **gRPC optional** — REST works fine for this use case
- **Service Account JSON key jangan di-commit** — masuk `.gitignore`
- Data gak perlu realtime, caching 30-60 menit acceptable
