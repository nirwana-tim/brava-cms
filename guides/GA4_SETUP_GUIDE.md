# Panduan Manual Aktivasi & Pengaturan Google Analytics 4 (GA4)

Dokumen ini berisi panduan langkah-demi-langkah (*step-by-step*) dari A sampai Z untuk mengaktifkan integrasi **Google Analytics 4 (GA4)** pada dasbor admin **Brava CMS** secara **100% gratis** tanpa memerlukan kartu kredit.

---

## Daftar Isi
1. [Langkah 1: Membuat Properti GA4 & Mendapatkan Property ID](#langkah-1-membuat-properti-ga4--mendapatkan-property-id)
2. [Langkah 2: Mengaktifkan Google Analytics Data API di Google Cloud](#langkah-2-mengaktifkan-google-analytics-data-api-di-google-cloud)
3. [Langkah 3: Membuat Service Account & Mengunduh File Key JSON](#langkah-3-membuat-service-account--mengunduh-file-key-json)
4. [Langkah 4: Memberikan Akses Viewer kepada Service Account di GA4](#langkah-4-memberikan-akses-viewer-kepada-service-account-di-ga4)
5. [Langkah 5: Mengisi Pengaturan GA4 di Panel Admin CMS](#langkah-5-mengisi-pengaturan-ga4-di-panel-admin-cms)
6. [Langkah 6 (Opsional): Konfigurasi File `.env`](#langkah-6-opsional-konfigurasi-file-env)
7. [Tanya Jawab & Troubleshooting](#tanya-jawab--troubleshooting)

---

## Langkah 1: Membuat Properti GA4 & Mendapatkan Property ID

1. Buka [Google Analytics](https://analytics.google.com/) dan login menggunakan akun Google kamu.
2. Klik ikon **Admin (Gear ⚙️)** di pojok kiri bawah.
3. Pada kolom tengah (*Property*), klik tombol **+ Create Property** (Buat Properti).
4. Isi data properti:
   - **Property name**: `Brava Compro` (atau nama brand website kamu)
   - **Reporting time zone**: `Indonesia` — `(GMT+07:00) Jakarta time`
   - **Currency**: `Indonesian Rupiah (IDR Rp)`
   - Klik **Next**.
5. Pilih kategori bisnis dan ukuran perusahaan, lalu klik **Next** → pilih **Examine user behavior** → klik **Create**.
6. Pada menu *Start collecting data*, pilih platform **Web**.
7. Masukkan URL website kamu (contoh: `https://brandkamu.com`) dan nama *stream* (contoh: `Web Compro`), lalu klik **Create stream**.
8. Untuk mendapatkan **Property ID** yang akan dimasukkan ke Settings CMS:
   - Buka menu **Admin** → pada kolom *Property*, klik **Property Details**.
   - Di kanan atas terlihat angka 9 digit pada label **PROPERTY ID** (contoh: `123456789`).
   - **Catatan Penting**: Gunakan angka **PROPERTY ID** (`123456789`), **bukan** Measurement ID yang diawali huruf G (`G-XXXXXX`).

---

## Langkah 2: Mengaktifkan Google Analytics Data API di Google Cloud

Agar dasbor admin Brava CMS dapat mengambil data laporan pengunjung dari Google secara otomatis, kita perlu mengaktifkan API resminya (100% gratis):

1. Buka [Google Cloud Console](https://console.cloud.google.com/) menggunakan akun Google yang sama.
2. Di pojok kiri atas (sebelah logo Google Cloud), klik *dropdown* nama project → klik **New Project**.
3. Beri nama project (contoh: `Brava CMS Analytics`), lalu klik **Create**.
4. Pastikan project yang baru kamu buat sudah terpilih di *dropdown* atas.
5. Pada bilah pencarian atas (*Search products and resources*), ketik:
   `Google Analytics Data API`
6. Klik hasil **Google Analytics Data API**, lalu klik tombol biru **Enable** (Aktifkan).

---

## Langkah 3: Membuat Service Account & Mengunduh File Key JSON

*Service Account* bertindak sebagai "robot petugas" yang diizinkan oleh sistem untuk membaca laporan analitik tanpa perlu login manual browser setiap hari:

1. Di Google Cloud Console, buka menu navigasi kiri (≡) → **IAM & Admin** → **Service Accounts** ([atau klik link direct ini](https://console.cloud.google.com/iam-admin/serviceaccounts)).
2. Klik tombol **+ Create Service Account** di bagian atas.
3. Isi data berikut:
   - **Service account name**: `ga4-reader`
   - **Service account ID**: Otomatis terisi (misal: `ga4-reader@brava-cms-analytics.iam.gserviceaccount.com`).
4. Klik **Create and Continue**, lalu klik **Done** di bawah (tidak perlu memilih *role* di halaman Cloud Console).
5. Pada tabel daftar Service Account, **salin dan simpan alamat email Service Account** tersebut (contoh: `ga4-reader@brava-cms-analytics.iam.gserviceaccount.com`).
6. Klik email Service Account tersebut untuk membuka detailnya, lalu beralih ke tab **Keys** (di bagian atas).
7. Klik tombol **Add Key** → pilih **Create new key**.
8. Pilih tipe format **JSON**, lalu klik **Create**.
9. Sebuah file `.json` akan otomatis diunduh ke komputer kamu.

---

## Langkah 4: Memberikan Akses Viewer kepada Service Account di GA4

Sekarang kita perlu memberi izin kepada email Service Account tadi agar bisa membaca laporan di Google Analytics kita:

1. Kembali ke dasbor [Google Analytics](https://analytics.google.com/), buka menu **Admin** (Gear ⚙️).
2. Di kolom *Property*, klik **Property Access Management** (Manajemen Akses Properti).
3. Klik tombol biru **+ (Plus)** di pojok kanan atas → pilih **Add users**.
4. Pada kotak **Email addresses**, tempelkan email Service Account yang kamu salin pada Langkah 3 (contoh: `ga4-reader@brava-cms-analytics.iam.gserviceaccount.com`).
5. Hilangkan centang pada *Notify new users by email*.
6. Pada bagian *Direct roles and data restrictions*, pilih peran **Viewer** (Penglihat).
7. Klik tombol **Add** di kanan atas.

---

## Langkah 5: Mengisi Pengaturan GA4 di Panel Admin CMS

Setelah memiliki **Property ID** (Langkah 1) dan **isi file JSON service account** (Langkah 3), masukkan keduanya langsung dari panel admin — tanpa perlu menyentuh server atau file `.env`:

1. Login ke CMS sebagai **Super Admin** (pengaturan ini hanya bisa diubah oleh superadmin).
2. Buka menu **Settings** → gulir ke grup **Technical / System Settings**.
3. Isi dua field berikut:
   - **GA4 Property ID (dashboard)**: tempel angka Property ID dari Langkah 1 (contoh: `123456789`).
   - **GA4 Service Account Key**: buka file `.json` yang kamu unduh di Langkah 3 dengan editor teks (Notepad/VS Code), **salin seluruh isinya**, lalu tempel ke field (textarea).
4. Klik **Save Settings**.
5. Buka **Dashboard** — tanda "Data dummy" akan hilang dan laporan langsung menampilkan data riil GA4.

> **Catatan keamanan**: Field Service Account Key hanya dapat dilihat/diubah oleh superadmin dan **tidak pernah** dikirim ke API publik (`/api/v1/settings`). Jika ingin menghapus akses, kosongkan kedua field ini.

---

## Langkah 6 (Opsional): Konfigurasi File `.env`

Metode di Langkah 5 sudah cukup untuk memakai dashboard GA4. Cara `.env` berikut hanya **fallback** bila kamu lebih suka menyimpan konfigurasi di sisi server (misal deployment otomatis):

1. Buka file `.env` di direktori utama project Brava CMS kamu.
2. Tambahkan atau perbarui konfigurasi berikut di bagian bawah file:

```env
# Google Analytics 4 (GA4) Dashboard Settings
GA4_PROPERTY_ID=123456789
GA4_SERVICE_ACCOUNT_KEY=app/analytics/service-account-key.json

# Pengaturan usia cache (dalam menit) agar responsif dan tidak boros kuota Google
GA4_CACHE_FRESH=30
GA4_CACHE_STALE=60
```

- Ganti `123456789` dengan **Property ID** asli kamu dari Langkah 1.
- Path `app/analytics/service-account-key.json` akan otomatis dibaca di dalam folder `storage/` oleh sistem (*Smart Path Resolution*).
- Prioritas pembacaan: **Setting admin CMS (Langkah 5) didahulukan**; jika kosong, sistem baru memakai nilai `.env`/file ini.

---

## Tanya Jawab & Troubleshooting

### 1. Bagaimana cara menguji apakah dasbor sudah terkoneksi dengan GA4 asli?
Buka halaman admin (`https://domain-anda.com/admin`).
- **Jika sudah terkoneksi**: Tanda kuning `"Data dummy..."` akan hilang dan digantikan oleh teks `"Data diperbarui setiap 30 menit."`
- Semua kartu statistik dan grafik akan menampilkan data riil pengunjung website kamu.

### 2. Mengapa grafik saya masih nol (0) setelah dipasang?
- Jika properti GA4 kamu baru dibuat hari ini, Google biasanya membutuhkan waktu **12 hingga 24 jam** pertama untuk memproses dan mengagregasi data laporan reguler.
- Pastikan kode pelacak GA4 (*Google Tag* / `G-XXXXXX`) sudah dimasukkan di field **Settings → Google Analytics ID** (`google_analytics_id`).

### 3. Apa yang terjadi jika saya mengosongkan **GA4 Property ID** dan **Service Account Key** di pengaturan admin?
- Sistem akan otomatis masuk ke **Mode Dummy Dinamis**.
- Dasbor tetap tampil menarik dengan data simulasi yang mengikuti rute halaman asli website *compro* (`/`, `/services`, `/portfolio`, `/blog`, `/about`, `/contact`) dan mengambil judul artikel/layanan dari database kamu. Mode ini sangat cocok untuk tahap demo/presentasi ke klien sebelum website naik ke production.
- Data dummy **tidak pernah di-cache** — begitu kedua field diisi kembali, dashboard langsung menampilkan data asli pada request berikutnya.

### 4. Apakah kuota API Google aman dan tidak akan kena limit?
- **Sangat aman!** Google memberikan kuota gratis **25.000 request per hari**.
- Berkat sistem *caching* Laravel selama 30 menit, dasbor Brava CMS hanya akan memanggil API Google maksimal **48 kali dalam sehari** (sekitar ~1% dari total kuota gratis harianmu).

### 5. Bagaimana widget "Pengunjung Aktif Sekarang" bekerja?
- Saat GA4 sudah terkoneksi, dasbor menampilkan kartu **Pengunjung Aktif Sekarang** dengan jumlah pengunjung aktif dan daftar halaman yang sedang dikunjungi.
- Data ini memakai **GA4 Realtime API** (klien & kuota gratis yang sama, terpisah dari laporan harian — tidak saling menghabiskan kuota) dan hanya mencakup **±30 menit terakhir**.
- Widget di-refresh setiap ~1 menit (bukan realtime per detik), dan otomatis **hilang** saat mode dummy / GA4 belum terkoneksi.
