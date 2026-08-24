# Panduan Setup & Monetisasi Google AdSense pada Brava CMS

Dokumen ini berisi panduan langkah-demi-langkah (*step-by-step*) untuk mengintegrasikan **Google AdSense** pada website **Brava Compro** melalui dasbor admin **Brava CMS**.

---

## Daftar Isi
1. [Syarat & Persiapan Sebelum Mendaftar AdSense](#1-syarat--persiapan-sebelum-mendaftar-adsense)
2. [Langkah 1: Mendaftarkan Situs di Google AdSense](#langkah-1-mendaftarkan-situs-di-google-adsense)
3. [Langkah 2: Menyiapkan Berkas `ads.txt`](#langkah-2-menyiapkan-berkas-adstxt)
4. [Langkah 3: Membuat Unit Iklan (Ad Units)](#langkah-3-membuat-unit-iklan-ad-units)
5. [Langkah 4: Konfigurasi di Panel Admin Brava CMS](#langkah-4-konfigurasi-di-panel-admin-brava-cms)
6. [Posisi Penempatan Iklan di Frontend](#6-posisi-penempatan-iklan-di-frontend)
7. [Aturan Keamanan & Kepatuhan Google Publisher Policies](#7-aturan-keamanan--kepatuhan-google-publisher-policies)

---

## 1. Syarat & Persiapan Sebelum Mendaftar AdSense

Agar pendaftaran situs Anda disetujui (*Approved*) oleh tim peninjau Google AdSense, pastikan website Anda memenuhi standar kualitas berikut:

1. **Memiliki Halaman Legal Lengkap:** Halaman *Privacy Policy*, *Terms & Conditions*, dan *Cookies Policy* wajib aktif dan dapat diakses publik (sudah tersedia otomatis di Brava Compro).
2. **Konten Artikel Berkualitas:** Terbitkan minimal **10–15 artikel blog orisinal** dan informatif seputar dunia konveksi, bahan apparel, atau tips seragam kerja (bukan hasil *copy-paste* murni).
3. **Navigasi Jelas:** Header dan navigasi kategori berjalan baik tanpa tautan rusak (*404*).
4. **Domain Kustom TLD:** Gunakan domain tingkat atas sendiri (contoh: `.id`, `.com`, `.co.id`).

---

## 2. Langkah 1: Mendaftarkan Situs di Google AdSense

1. Buka [Google AdSense](https://www.google.com/adsense/) dan login dengan akun Google Anda.
2. Klik tombol **Get Started** (Mulai).
3. Masukkan URL website Anda (contoh: `brava.id` — tanpa `https://` atau subdomain).
4. Pilih preferensi pengiriman email info panduan → Klik **Start using AdSense**.
5. Di dasbor AdSense, selesaikan langkah profil:
   - **Payment Info:** Isi data identitas penerima pembayaran dan alamat pos.
   - **Connect Site:** Klik *Connect your site* untuk mendapatkan **Publisher ID** (format: `ca-pub-XXXXXXXXXXXXXXXX`).

---

## 3. Langkah 2: Menyiapkan Berkas `ads.txt`

Google mewajibkan file `ads.txt` di root domain untuk mencegah penipuan inventaris iklan:

1. Di dasbor AdSense, buka menu **Sites** → Klik situs Anda.
2. Unduh atau salin baris teks `ads.txt` yang diberikan Google, formatnya seperti:
   ```txt
   google.com, pub-1234567890123456, DIRECT, f08c47fec0942fa0
   ```
3. Letakkan baris teks tersebut ke dalam file `ads.txt` pada folder `public/` di frontend `brava-compro` (`brava-compro/public/ads.txt`).
4. Pastikan file dapat diakses melalui browser di alamat: `https://domain-anda.com/ads.txt`.

---

## 4. Langkah 3: Membuat Unit Iklan (Ad Units)

Brava Compro mendukung penempatan 2 slot unit iklan responsif di halaman artikel blog:

1. Di dasbor AdSense, buka menu **Ads** (Iklan) → Tab **By ad unit** (Berdasarkan unit iklan).
2. Pilih tipe **Display ads** (Iklan Display) atau **In-article ads** (Iklan dalam artikel).
3. **Membuat Slot 1 (Atas Artikel):**
   - Beri nama unit iklan: `Brava_Blog_Top`
   - Ukuran iklan (*Ad size*): Pilih **Responsive**.
   - Klik **Create**.
   - Salin angka **Slot ID** dari kode yang muncul (`data-ad-slot="XXXXXXXXXX"`).
4. **Membuat Slot 2 (Bawah Artikel):**
   - Ulangi langkah di atas dengan nama: `Brava_Blog_Bottom`.
   - Klik **Create** dan salin **Slot ID** kedua tersebut.

---

## 5. Langkah 4: Konfigurasi di Panel Admin Brava CMS

Setelah memiliki Publisher ID dan kedua Slot ID, aktifkan AdSense di CMS:

1. Login ke **Brava CMS** sebagai **Super Admin**.
2. Masuk ke menu **Settings** pada sidebar.
3. Gulir ke grup **AdSense Settings**.
4. Isi data berikut:
   * **AdSense Enabled:** Nyalakan sakelar toggle menjadi aktif (Hijau).
   * **AdSense Publisher ID:** Masukkan Publisher ID Anda (contoh: `ca-pub-1234567890123456`).
   * **AdSlot 1 ID:** Masukkan angka Slot ID pertama (contoh: `9876543210`).
   * **AdSlot 2 ID:** Masukkan angka Slot ID kedua (contoh: `1234567890`).
5. Klik tombol **Save Settings**.

---

## 6. Posisi Penempatan Iklan di Frontend

Frontend `brava-compro` secara otomatis merender iklan pada posisi strategis dengan mempertimbangkan kenyamanan pembaca (User Experience):

```
+-------------------------------------------------------------------+
| Halaman Detail Blog (/blogs/[slug])                               |
+-------------------------------------------------------------------+
| [ Breadcrumbs ]                                                   |
| Judul Artikel (H1)                                                |
| Bar Info: Author Avatar • Tanggal Publikasi • Badge Kategori      |
|                                                                   |
| [ 📢 AD UNIT 1 (Slot 1) - Di bawah judul sebelum artikel mulai ]  |
|                                                                   |
| Paragraf 1 Konten Artikel...                                      |
| Gambar Ilustrasi / Penjelasan Bahan Konveksi...                   |
| Paragraf Terakhir Konten Artikel...                               |
|                                                                   |
| [ 📢 AD UNIT 2 (Slot 2) - Di akhir artikel sebelum artikel lain ] |
|                                                                   |
| Section: "Insight & Tren Terbaru" (Rekomendasi Artikel Terkait)   |
| Section: Call To Action (Konsultasi Seragam)                      |
| Footer                                                            |
+-------------------------------------------------------------------+
```

---

## 7. Aturan Keamanan & Kepatuhan Google Publisher Policies

1. **Google Consent Mode v2 & Cookie Banner:**
   * Brava Compro telah dilengkapi integrasi otomatis Google Consent Mode v2. Iklan personalisasi hanya dimuat jika pengunjung menyetujui opsi cookie iklan pada banner *Cookie Consent*.
2. **Jangan Mengklik Iklan Sendiri (*Invalid Clicks*):**
   * Dilarang keras mengklik unit iklan di website Anda sendiri saat memeriksa tampilan. Pelanggaran dapat menyebabkan akun AdSense dinonaktifkan permanen oleh Google.
3. **Responsif Multi-Device:**
   * Komponen AdSense di Brava Compro otomatis menyesuaikan ukuran layar perangkat (Desktop, Tablet, dan Smartphone) tanpa merusak tata letak konten.
