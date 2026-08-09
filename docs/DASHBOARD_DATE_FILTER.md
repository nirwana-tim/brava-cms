# Brava CMS — Dashboard Date Filter

> Fitur filter rentang tanggal pada dashboard admin (`/admin`).
>
> Status: **Phase 1 — DONE (preset 7H / 30H / 90H / 1Y)** · **Phase 2 — Planned (custom date range `?from=&to=`, period comparison)**.

---

## Overview

Data analytics di dashboard di-generate oleh `App\Services\AnalyticsService` dan sudah menerima parameter `int $days`. Jadi menambahkan filter adalah pekerjaan frontend + controller (Phase 1) dan refactor kecil di service (Phase 2).

### Kenapa Ini Gratis / Bukan Paywall

- Tidak ada sistem subscription, plan, billing, atau payment gating di codebase ini.
- Akses `/admin` hanya dibatasi `['auth', 'verified']` + role (`SuperAdmin` / `Admin` / `Staff`).
- Angka "30 hari" murni hardcoded — bukan batasan GA4 maupun lisensi.

### Alur Data Saat Ini (Phase 1 — implemented)

| Layer | File | Catatan |
|-------|------|---------|
| Controller | `app/Http/Controllers/Admin/DashboardController.php:37-39` | `$days = request()->query('days', 30)` + whitelist `[7, 30, 90, 365]` |
| Service | `app/Services/AnalyticsService.php` | `getOverview(int $days = 30)` |
| Cache key | `AnalyticsService.php` | `analytics.overview.{$days}` — sudah per-days |
| GA4 range | `AnalyticsService.php` | `today()->subDays($days)` → `yesterday()` |
| Dummy range | `AnalyticsService.php` | `range($days - 1, 0)` |
| View | `dashboard.blade.php:38-42` | Tombol preset 7H / 30H / 90H / 1Y |
| Chart | Chart.js v4 (`resources/js/app.js`) | 3 chart inline di Blade |

---

## Phase 1 — Preset (7H / 30H / 90H / 1Y) ✅ DONE

> **Status: SUDAH DITERAPKAN.** Implementasi aktual bisa dilihat di `DashboardController.php:37-39` (whitelist `[7,30,90,365]`, fallback 30) dan tombol preset di `dashboard.blade.php:38-42`. Bagian di bawah ini disimpan sebagai catatan implementasi.

### Implementasi yang Ada

1. **`app/Http/Controllers/Admin/DashboardController.php`**
   - Membaca `days` dari query string: `$days = (int) request()->query('days', 30);`
   - Validasi whitelist preset: `in:7,30,90,365`; nilai tidak valid → fallback ke `30`.
   - Memanggil `$analytics->getOverview($days)`.

2. **`resources/views/admin/dashboard.blade.php`**
   - Baris tombol preset (link ke `route('admin.dashboard', ['days' => 90])`) di atas section "Analytics Ringkasan"; tombol aktif diberi highlight.
   - Label periode mengikuti nilai `$days`.

3. **`app/Services/AnalyticsService.php`** — tidak perlu diubah (cache key sudah per-days).

4. **Test — `tests/Feature/AnalyticsDashboardTest.php`**
   - `GET /admin?days=7` → `$data['period']` bernilai `7`.
   - `GET /admin?days=999` (invalid) → fallback ke `30`.

### Risiko & Catatan

- Cache key sudah per-days, preset yang berbeda tidak saling tabrak.
- Data "today"/"yesterday" tetap real-time, tidak ikut ter-filter (memang kartu "hari ini").

---

## Phase 2 — Custom Date Range (`?from=YYYY-MM-DD&to=YYYY-MM-DD`) 🕒 Planned

Belum diimplementasikan. Refactor service dari model `$days` → date range eksplisit.

### File & Perubahan

1. **`app/Services/AnalyticsService.php`**
   - `fetchFromGA(int $days)` (baris 189) → `fetchFromGA(string $startDate, string $endDate)`; hapus `subDays` di baris 191-192.
   - `dummyOverview(int $days)` (baris 367) → `dummyOverview(string $startDate, string $endDate)`; ganti `range($days - 1, 0)` (baris 369) dengan loop tanggal antar start-end.
   - `getOverview()` menerima `?from=` & `?to=`, hitung `$days` untuk key `'period'`.
   - Cache key: `analytics.overview.{start}.{end}`.

2. **Controller & View**
   - Validasi: format `Y-m-d`, `from <= to`, `to` tidak melewati hari ini, rentang maksimal 366 hari.
   - Form 2 input `type="date"` + tombol "Apply" di samping tombol preset.

3. **Test** — validasi tanggal, batas rentang, perilaku cache.

### Bonus (roadmap `docs/ANALYTICS.md` Phase 2)

- **Period comparison (vs previous period)** — tinggal re-run service dengan rentang sebelumnya, baru bisa dibangun setelah Phase 2 selesai.

---

## Urutan Eksekusi & Verifikasi

1. Implementasi Phase 1 → jalankan test → `vendor/bin/pint --dirty --format agent`.
2. Implementasi Phase 2 (sesi terpisah lebih aman) → jalankan test.
3. Cek visual: `npm run dev` / `npm run build` (atau `bun run dev` / `bun run build`) bila UI tidak muncul perubahan.
4. Total: `php artisan test --compact`.

---

## Referensi

- Roadmap asli: [`ANALYTICS.md`](ANALYTICS.md) → bagian "Phase 2 — Enhancement" (date range picker custom, period comparison).
- Service: `app/Services/AnalyticsService.php`
- Controller: `app/Http/Controllers/Admin/DashboardController.php`
- View: `resources/views/admin/dashboard.blade.php`
