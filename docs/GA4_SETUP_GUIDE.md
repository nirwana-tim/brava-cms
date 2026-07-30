# Panduan Manual Aktivasi & Pengaturan Google Analytics 4 (GA4)

Dokumen ini berisi panduan langkah-demi-langkah (*step-by-step*) dari A sampai Z untuk mengaktifkan integrasi **Google Analytics 4 (GA4)** pada dasbor admin **Brava CMS** secara **100% gratis** tanpa memerlukan kartu kredit.

---

## Daftar Isi
1. [Langkah 1: Membuat Properti GA4 & Mendapatkan Property ID](#langkah-1-membuat-properti-ga4--mendapatkan-property-id)
2. [Langkah 2: Mengaktifkan Google Analytics Data API di Google Cloud](#langkah-2-mengaktifkan-google-analytics-data-api-di-google-cloud)
3. [Langkah 3: Membuat Service Account & Mengunduh File Key JSON](#langkah-3-membuat-service-account--mengunduh-file-key-json)
4. [Langkah 4: Memberikan Akses Viewer kepada Service Account di GA4](#langkah-4-memberikan-akses-viewer-kepada-service-account-di-ga4)
5. [Langkah 5: Memasang File JSON Key di Brava CMS](#langkah-5-memasang-file-json-key-di-brava-cms)
6. [Langkah 6: Konfigurasi File `.env`](#langkah-6-konfigurasi-file-env)
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
8. Untuk mendapatkan **Property ID** yang akan dimasukkan ke `.env`:
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

## Langkah 5: Memasang File JSON Key di Brava CMS

1. Ubah nama file `.json` yang kamu unduh pada Langkah 3 menjadi:
   `service-account-key.json`
2. Simpan atau pindahkan file tersebut ke dalam folder penyimpanan project Brava CMS pada direktori berikut:
   ```text
   storage/app/analytics/service-account-key.json
   ```
   *(Jika folder `analytics/` belum ada di dalam `storage/app/`, silakan buat folder tersebut terlebih dahulu).*

---

## Langkah 6: Konfigurasi File `.env`

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

---

## Tanya Jawab & Troubleshooting

### **1. Bagaimana cara menguji apakah dasbor sudah terkoneksi dengan GA4 asli?**
Buka halaman admin (`http://localhost:8000/admin` atau domain production kamu).
- **Jika sudah terkoneksi**: Tanda kuning `"Data dummy — atur GA4_PROPERTY_ID..."` akan hilang dan digantikan oleh teks `"Data diperbarui setiap 30 menit."`
- Semua kartu statistik dan grafik akan menampilkan data riil pengunjung website kamu.

### **2. Mengapa grafik saya masih nol (0) setelah dipasang?**
- Jika properti GA4 kamu baru dibuat hari ini, Google biasanya membutuhkan waktu **12 hingga 24 jam** pertama untuk memproses dan mengagregasi data laporan reguler.
- Pastikan kode pelacak GA4 (*Google Tag* / `G-XXXXXX`) sudah terpasang di HTML website *compro* kamu.

### **3. Apa yang terjadi jika saya mengosongkan `GA4_PROPERTY_ID` di `.env`?**
- Sistem akan otomatis masuk ke **Mode Dummy Dinamis**.
- Dasbor tetap tampil menarik dengan data simulasi yang mengikuti rute halaman asli website *compro* (`/`, `/services`, `/portfolio`, `/blog`, `/about`, `/contact`) dan mengambil judul artikel/layanan dari database kamu. Mode ini sangat cocok untuk tahap demo/presentasi ke klien sebelum website naik ke production.

### **4. Apakah kuota API Google aman dan tidak akan kena limit?**
- **Sangat aman!** Google memberikan kuota gratis **25.000 request per hari**.
- Berkat sistem *caching* Laravel selama 30 menit, dasbor Brava CMS hanya akan memanggil API Google maksimal **48 kali dalam sehari** (sekitar ~1% dari total kuota gratis harianmu).
