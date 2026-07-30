# Brava CMS — Google Analytics Dashboard

> Integrasi GA4 untuk website compro — data ditampilkan langsung di dashboard admin (tanpa menu sidebar terpisah).

---

## Overview

Semua data Google Analytics 4 muncul langsung di halaman dashboard utama (`/admin`). Gak perlu navigasi ke halaman lain. Data di-cache 2 jam (stale 4 jam) — maksimal 12 request/hari ke GA API dari kuota gratis 50.000/hari.

### Untuk Reseller Konveksi

| Insight | Manfaat |
|---------|---------|
| Traffic per platform (IG, TikTok, WA, FB, dll) | Tahu channel marketing mana yang efektif |
| Device breakdown (mobile vs desktop) | Optimasi pengalaman perangkat |
| Kota asal pengunjung | Target reseller per daerah potensial |
| Top pages | Produk/jasa mana yang lagi trend |

---

## Cara Aktivasi

1. Buat GA4 property di https://analytics.google.com (gratis)
2. Buat Google Cloud Project → enable **Google Analytics Data API**
3. Buat Service Account → download JSON key
4. Invite email service account sebagai **Viewer** di GA4
5. Taruh JSON key di `storage/app/analytics/service-account-key.json`
6. Isi `.env`:
   ```env
   GA4_PROPERTY_ID=123456789
   GA4_SERVICE_ACCOUNT_KEY=storage/app/analytics/service-account-key.json
   ```

> **Kosongin `GA4_PROPERTY_ID`** → dashboard otomatis pake data dummy (angka random) biar UI keliatan.

---

## Yang Ada di Dashboard

### Baris 1 — CMS Stats
Services, Blog Posts, Categories, Users (dari database lokal).

### Baris 2 — Analytics Stat Cards
| Card | Metrik GA4 |
|------|-----------|
| Visitors Today | `activeUsers` (hari ini) |
| Pageviews Today | `screenPageViews` |
| Sessions Today | `sessions` |
| Bounce Rate | `bounceRate` |
| Avg Duration | `averageSessionDuration` |

### Baris 3 — Grafik
| Chart | Tipe | Dimensi GA4 | Metrik GA4 |
|-------|------|-------------|------------|
| Visitor Trend | Line chart | `date` | `activeUsers`, `screenPageViews` |
| Traffic Sources | Donut chart | `sessionSource` | `sessions` |

**Platform yang otomatis terdeteksi:**
| Source | Warna | Keterangan |
|--------|-------|------------|
| Instagram | Pink | IG, l.instagram.com |
| TikTok | Hitam | tiktok.com |
| WhatsApp | Hijau | wa.me |
| Facebook | Biru | facebook.com, m.facebook.com, l.facebook.com |
| Google | Indigo | google (organic) |
| Direct | Cyan | (direct) |
| Twitter / X | Biru muda | twitter.com, t.co |
| YouTube | Merah | youtube.com |
| LinkedIn | Biru tua | linkedin.com |
| Telegram | Biru langit | telegram |
| Email | Kuning | email, mail |

> **UTM Parameters (recommended):** Biar data lebih akurat, tambah UTM tag di setiap link yang disebar:
> ```
> https://brava.com/produk?utm_source=instagram&utm_medium=social&utm_campaign=promo-juli
> ```

### Baris 4 — Detail
| Widget | Tipe | Dimensi GA4 | Metrik GA4 |
|-------|------|-------------|------------|
| Device Breakdown | Bar chart | `deviceCategory` | `sessions` |
| Top Pages | Table | `pagePath`, `pageTitle` | `screenPageViews`, `averageEngagementTime` |

### Baris 5 — Insight
| Widget | Deskripsi |
|--------|-----------|
| Top Cities | Progress bar per kota → `city` + `sessions` |
| Marketing Insights | 3 card: Top Channel, Dominan Device, Kota Teraktif |

---

## API Reference

### Package
```bash
composer require google/analytics-data
```
Library: `Google\Analytics\Data\V1beta\BetaAnalyticsDataClient`

### Auth
```php
use Google\Auth\Credentials\ServiceAccountCredentials;

$credentials = new ServiceAccountCredentials(
    ['https://www.googleapis.com/auth/analytics.readonly'],
    json_decode(file_get_contents($keyPath), true)
);

$client = new BetaAnalyticsDataClient(['credentials' => $credentials]);
```

### Dimensi & Metrik yang Dipakai

| Query | Dimensi | Metrik |
|-------|---------|--------|
| Overview stats | `date` | `activeUsers`, `screenPageViews`, `sessions`, `bounceRate`, `averageSessionDuration` |
| Traffic sources | `sessionSource` | `sessions` |
| Device breakdown | `deviceCategory` | `sessions` |
| Top pages | `pagePath`, `pageTitle` | `screenPageViews`, `averageEngagementTime` |
| Geo stats | `city` | `sessions` |

---

## Caching

`Cache::flexible()` — stale-while-revalidate:

| TTL Fresh (dari API) | TTL Stale (pake data lama) |
|----------------------|---------------------------|
| 120 menit | 240 menit |

Konfigurasi `.env`:
```
GA4_CACHE_FRESH=120
GA4_CACHE_STALE=240
```

---

## File Structure

```
config/analytics.php
app/Services/AnalyticsService.php        ← service layer (GA4 API + dummy fallback)
app/Http/Controllers/Admin/DashboardController.php  ← updated: inject analytics data
resources/views/admin/dashboard.blade.php           ← all charts inline
resources/js/app.js                      ← Chart.js global registration
storage/app/analytics/                   ← taruh service-account-key.json di sini
```

---

## Phase 2 — Enhancement

- Export to CSV
- Date range picker custom
- Period comparison (vs previous period)
- Email report mingguan otomatis
