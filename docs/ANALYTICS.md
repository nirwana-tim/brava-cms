# Brava CMS — Google Analytics Dashboard

> Integrasi GA4 untuk website compro — data ditampilkan langsung di dashboard admin (tanpa menu sidebar terpisah).
>
> **Panduan Setup Lengkap:** Untuk panduan manual langkah-demi-langkah dari awal membuat akun GA4 hingga download file JSON Service Account Google Cloud, silakan baca dokumentasi [`GA4_SETUP_GUIDE.md`](GA4_SETUP_GUIDE.md).

---

## Overview

Semua data Google Analytics 4 muncul langsung di halaman dashboard utama (`/admin`). Gak perlu navigasi ke halaman lain. Data di-cache 30 menit (stale 60 menit) — sangat segar dan responsif, dengan konsumsi hanya ~1,15% dari kuota gratis 25.000 request/hari GA API.

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
   GA4_SERVICE_ACCOUNT_KEY=app/analytics/service-account-key.json
   GA4_CACHE_FRESH=30
   GA4_CACHE_STALE=60
   ```

> **Smart Path Resolution:** Kamu bisa menulis path JSON key secara relatif terhadap folder storage (`app/analytics/...`), menggunakan prefiks (`storage/app/...`), atau absolute path — sistem otomatis memprosesnya tanpa risiko path ganda.
> **Kosongin `GA4_PROPERTY_ID`** → dashboard otomatis beralih ke mode **Data Dummy Dinamis** (mengikuti rute halaman compro asli seperti `/`, `/services`, `/portfolio`, `/blog`, `/about`, `/contact`, serta mengambil slug/judul asli dari database).

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

> **Catatan Top Pages (Mode Dummy):** Pada mode dummy, daftar halaman secara otomatis menyesuaikan dengan rute Company Profile Brava CMS (`/`, `/services`, `/portfolio`, `/blog`, `/about`, `/contact`) dan menambahkan slug asli dari tabel `services`, `portfolio_items`, dan `blogs` jika tersedia di database.

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

> **Penting (GA4 Data API v1beta OrderBy):**
> - Mengurutkan berdasarkan **Dimension** (contoh: `'date'`) wajib menggunakan `OrderByDimension` (`new OrderByDimension(['dimension_name' => 'date'])`).
> - Mengurutkan berdasarkan **Metric** (contoh: `'sessions'`) wajib menggunakan `OrderByMetric` (`new OrderByMetric(['metric_name' => 'sessions'])`).
> - Penggunaan yang terbalik akan memicu error HTTP `400 Invalid Argument` dari server Google Analytics.

---

## Caching

`Cache::flexible()` — stale-while-revalidate:

| TTL Fresh (dari API) | TTL Stale (pake data lama) |
|----------------------|---------------------------|
| 30 menit | 60 menit |

Konfigurasi `.env`:
```env
GA4_CACHE_FRESH=30
GA4_CACHE_STALE=60
```

> **Array-Only Cache Serialization:** Sistem menyimpan data cache ke database dalam bentuk **100% PHP Array murni** (`->values()->all()`) untuk mencegah kerusakan *unserialize* (`__PHP_Incomplete_Class`) pada MySQL. Data kemudian secara otomatis dikonversi menjadi `Collection` saat dihidrasi di *layer* service/controller agar langsung kompatibel dengan metode Blade (`->pluck()`, `->sortByDesc()`).

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

Status:
- [x] **Date range preset** (7H / 30H / 90H / 1Y) — selesai, lihat `docs/DASHBOARD_DATE_FILTER.md` (Phase 1).
- [ ] Export to CSV — belum
- [ ] Date range picker custom (`?from=&to=`) — belum (Phase 2 di `docs/DASHBOARD_DATE_FILTER.md`)
- [ ] Period comparison (vs previous period) — belum
- [ ] Email report mingguan otomatis — belum
