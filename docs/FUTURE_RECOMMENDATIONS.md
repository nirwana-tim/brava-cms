# Backlog Rekomendasi Peningkatan Masa Depan (Future Recommendations) - Brava CMS

> [!CAUTION]
> **PENTING / PERHATIAN UNTUK DEVELOPER & AI AGENTS:**  
> Seluruh item dalam dokumen ini adalah **Rekomendasi Peningkatan Jangka Panjang (Backlog)**.  
> **DILARANG KERAS** mengeksekusi, memodifikasi kode, atau mengimplementasikan item apa pun di bawah ini tanpa **izin dan persetujuan eksplisit dari User** terlebih dahulu!  
> Proyek ini sedang dalam mode **Sprint**, di mana prioritas utama adalah menyelesaikan fitur fungsional inti agar siap rilis (*Production-Ready First*).

---

## Daftar Rekomendasi (Backlog Checklist)

Berikut adalah daftar peningkatan teknis yang dapat dipertimbangkan setelah fase Sprint utama selesai atau saat lalu lintas website mulai berkembang pesat:

### 1. [ ] Composite Indexing pada Database
- **Tujuan**: Meningkatkan kecepatan query pada kombinasi kondisi filter ketika volume data sudah mencapai ribuan hingga puluhan ribu baris.
- **Waktu yang Tepat untuk Eksekusi**: Ketika tabel `blogs`, `portfolio_items`, atau `promos` memiliki > 5.000 data.
- **Berkas yang Akan Terpengaruh**:
  - Migration baru untuk menambahkan indeks gabungan (*composite index*):
    - Tabel `blogs`: `index(['status', 'published_at'])`
    - Tabel `promos`: `index(['is_active', 'is_highlighted', 'valid_until'])`

---

### 2. [x] Konversi Gambar Otomatis ke WebP / AVIF
> ✅ **SUDAH DITERAPKAN**: `App\Services\MediaService::storeWithCompression()` mengompresi & meng-encode gambar menjadi **WebP (quality 85)** melalui `intervention/image` (lihat kode untuk filter MIME). **AVIF belum dikerjakan** sehingga tetap ada di backlog — item di bawah hanya menyisakan *enhancement* ke AVIF / responsive sizes.
- **Tujuan**: Mengurangi ukuran payload API dan mempercepat waktu halaman di frontend.
- **Status**: WebP ✓ (implemented) · AVIF & responsive sizes — backlog.
- **Berkas Terkait**:
  - `app/Services/MediaService.php` (konversi WebP)
  - `app/Http/Controllers/Admin/UploadController.php`
  - `app/Http/Controllers/Admin/MediaController.php`

---

### 3. [ ] Audit Trail / Log Jejak Aktivitas Admin
- **Tujuan**: Mencatat riwayat siapa (*user ID* & nama), kapan, dan apa yang diubah pada modul sensitif (misalnya mengganti status Hero Banner Promo, menghapus artikel blog, atau memodifikasi peran tim).
- **Waktu yang Tepat untuk Eksekusi**: Ketika tim pengelola konten (Admin & Staff) berjumlah > 3 orang dan dibutuhkan akuntabilitas perubahan data.
- **Berkas yang Akan Terpengaruh**:
  - Pembuatan tabel/migration baru `activity_logs`.
  - Service atau Trait `LogsActivity` pada model `Promo`, `Blog`, `PortfolioItem`, dan `User`.
  - Halaman UI Admin baru untuk memantau log aktivitas.

---

### 4. [~] Pengaturan Ketat CORS & Otorisasi Token (Laravel Sanctum)
> 🟡 **SEBAGIAN DITERAPKAN**: `config/cors.php` sudah memakai `allowed_origins` via `CORS_ALLOWED_ORIGINS` (+ `allowed_origins_patterns` via `CORS_ALLOWED_ORIGINS_PATTERNS`) pada route `api/*`. Yang **belum**: otorisasi `auth:sanctum` untuk endpoint non-publik (API saat ini publik, hanya di-rate-limit).
- **Tujuan**: Membatasi domain luar yang dapat mengonsumsi API publik Brava CMS serta mengamankan endpoint non-publik untuk kebutuhan aplikasi seluler atau portal eksternal.
- **Waktu yang Tepat untuk Eksekusi**: Sebelum integrasi dengan aplikasi seluler (*Mobile App*) atau antarmuka klien eksternal di luar domain utama website Brava.
- **Berkas yang Akan Terpengaruh**:
  - `config/cors.php` (menyesuaikan `allowed_origins`).
  - `routes/api.php` (menambahkan middleware `auth:sanctum` untuk endpoint khusus yang memerlukan autentikasi token).

---

## Catatan Eksekusi
- Sebelum memulai item apa pun dari dokumen ini, tanyakan kepada User: *"Apakah saat ini kita sudah siap untuk mengerjakan item rekomendasi [Nama Item] dari FUTURE_RECOMMENDATIONS.md?"*
- Jika User belum mengizinkan, **lewati** dan fokus pada tugas utama yang sedang dikerjakan.
