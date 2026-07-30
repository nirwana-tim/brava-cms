# Brava CMS - Panduan Sistem Promo & Penawaran Spesial (Special Offers)

Dokumen ini menjelaskan arsitektur, spesifikasi teknis, alur logika, serta panduan penggunaan modul **Promo & Voucher (Special Offers)** pada Brava CMS dan aplikasinya di Frontend Next.js.

---

## 1. Ikhtisar Sistem
Modul **Promo & Penawaran Spesial** dirancang khusus untuk meningkatkan konversi calon pelanggan (*lead generation*) langsung ke WhatsApp (WA).
Sistem ini mendukung 2 tampilan utama di Frontend Next.js:
1. **Floating Button & Modal Popup (Flash Sale / Voucher Tersedia)**:
   - Tombol mengapung di website yang membuka modal popup berisi **Promo Highlight (1 Promo Utama)**.
   - Tombol **"Klaim Sekarang"** yang terhubung langsung ke WhatsApp dengan template pesan otomatis.
2. **Halaman Daftar Promo (`/promo` atau `/penawaran-spesial`)**:
   - **Hero Banner (Top Highlight)**: Menampilkan 1 promo utama berskala besar (40% Diskon Pemesanan Seragam, dll).
   - **Grid Promo Lainnya (Bottom Section)**: Menampilkan daftar kartu promo aktif lainnya berderet ke bawah dengan tombol klaim.

---

## 2. Struktur Database (`promos`)
Tabel `promos` menyimpan seluruh informasi promosi, kupon, dan voucher:

| Kolom | Tipe Data | Keterangan & Aturan |
| :--- | :--- | :--- |
| `id` | BigInteger (PK) | Primary Key |
| `title` | String(255) | Judul Promo (Contoh: *"40% Diskon Untuk Pemesanan Seragam Perusahaan"*) |
| `slug` | String(255) (Unique) | URL Slug |
| `badge_text` | String(100) (Nullable) | Badge kecil (Contoh: *"PROMO TERBATAS"*, *"SPECIAL OFFER"*) |
| `discount_info` | String(100) (Nullable) | Ringkasan diskon (Contoh: *"40%"*, *"Rp 500.000"*) |
| `description` | Text (Nullable) | Keterangan lengkap & syarat ketentuan |
| `image` | String(500) (Nullable) | URL gambar banner utama |
| `image_alt` | String(255) (Nullable) | Alt text untuk SEO gambar banner |
| `valid_from` | DateTime (Nullable) | Tanggal promo mulai berlaku |
| `valid_until` | DateTime (Nullable) | Tanggal batas akhir promo |
| `wa_template` | Text (Nullable) | Template pesan kustom saat tombol *Klaim Sekarang* diklik |
| `is_highlighted` | Boolean (Default: `false`) | **Hanya maksimal 1 promo aktif yang boleh BERNILAI TRUE** |
| `is_active` | Boolean (Default: `true`) | Status publikasi promo |
| `created_at`, `updated_at`, `deleted_at` | Timestamps | Audit trail & Soft Deletes |

---

## 3. Logika & Aturan Bisnis (Edge Cases & Safety Analysis)

### A. Aturan "Single Highlight Guarantee" (Hanya 1 Highlight)
- Admin tidak perlu mematikan highlight promo lama secara manual.
- Saat Admin mengaktifkan `is_highlighted = true` untuk **Promo B**, sistem di level *Model/Service* secara transaksional mengubah `is_highlighted = false` untuk seluruh promo lainnya.
- **Proteksi Validasi**: Promo yang dalam kondisi **Tidak Aktif (`is_active = false`)** atau **Sudah Kedaluwarsa (`valid_until < now()`)** dilarang oleh sistem untuk dijadikan *Highlight*.

### B. Auto-Expired & Auto-Fallback Logic
- **Auto-Expired**: Kueri API secara otomatis menyaring promo dengan syarat:
  `is_active = true AND (valid_until IS NULL OR valid_until >= NOW())`
- **Auto-Fallback Highlight**: Jika promo *Highlight* utama masa berlakunya habis (expired) tengah malam, API `GET /api/promos/highlight` tidak akan menghasilkan `null`. Sistem akan otomatis melakukan *fallback* ke **Promo Aktif Terbaru** sehingga banner utama di website dan modal popup tidak pernah kosong/rusak.
- **Zero Promo State**: Jika tidak ada sama sekali promo yang aktif di database, API mengembalikan `{"data": null}` dan frontend Next.js menyembunyikan tombol mengapung serta menampilkan state *"Belum ada promo aktif saat ini"*.

### C. WhatsApp Auto-Template Generator
- Jika Admin mengisi `wa_template`, tombol di frontend memformat URL:
  `https://wa.me/{setting_whatsapp_number}?text={urlencode(wa_template)}`
- Jika Admin membiarkan `wa_template` kosong, sistem menggunakan *default fallback*:
  `"Halo Brava, saya tertarik untuk mengklaim promo: {title}."`

---

## 4. Spesifikasi API Endpoints (Next.js Compro)

Semua endpoint dilindungi middleware `throttle:60,1` (maksimal 60 request/menit per IP) dan menggunakan sistem **Laravel Flexible Caching** yang otomatis di-reset saat ada perubahan di CMS.

### A. `GET /api/promos/highlight`
Mengambil 1 promo utama untuk ditampilkan di Modal Popup Voucher & Hero Banner halaman `/promo`.
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "40% Diskon Untuk Pemesanan Seragam Perusahaan",
    "slug": "40-diskon-untuk-pemesanan-seragam-perusahaan",
    "badge_text": "PROMO TERBATAS",
    "discount_info": "40%",
    "description": "Dapatkan potongan harga hingga 40% untuk pemesanan kolektif...",
    "image": "http://localhost:8000/storage/promos/seragam-promo.jpg",
    "image_alt": "Diskon Seragam Perusahaan 40%",
    "valid_from": "2026-07-01 00:00:00",
    "valid_until": "2026-09-30 23:59:59",
    "is_highlighted": true,
    "wa_url": "https://wa.me/6281234567890?text=Halo%20Brava..."
  }
}
```

### B. `GET /api/promos`
Mengambil daftar promo aktif lainnya (selain promo highlight) dalam bentuk paginasi (maksimal 100 item/halaman, default 12).
- Dukungan parameter query: `?per_page=12&page=1`
- Promo yang ter-highlight di atas **otomatis dikecualikan (excluded)** dari daftar ini agar tidak muncul ganda di halaman yang sama.

### C. `GET /api/promos/{slug}`
Mengambil detail 1 promo berdasarkan slug (jika dibutuhkan halaman detail individual).

---

## 5. Panduan Penggunaan Admin CMS
1. Buka menu **Promo & Offers** di sidebar Admin Brava CMS.
2. Klik **Add New Promo**.
3. Isi Judul, Badge (misal: "PROMO TERBATAS"), dan Info Diskon ("40%").
4. Upload gambar banner promo via **Media Picker**.
5. Tentukan tanggal berlaku di kolom **Valid From** dan **Valid Until**.
6. (Opsional) Tulis **WA Template Message** sesuai pesan yang ingin diterima CS saat klien mengklaim.
7. Nyalakan toggle **Highlight as Hero Banner** jika ingin menjadikan promo ini sebagai banner utama dan isi modal popup di website.
8. Klik **Save Promo**.
