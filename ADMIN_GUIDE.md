# Panduan Penggunaan & Onboarding Admin Brava CMS

- **Aplikasi:** Brava CMS (Content Management System & Headless API)
- **Versi:** 1.0.0 (Production Ready)
- **Stack Teknologi:** Laravel 12 (PHP 8.2+), MySQL / MariaDB, Tailwind CSS v4, Alpine.js v3, Spatie Translatable, Intervention Image (WebP Engine), Google Analytics Data API (GA4 v1beta), REST API Headless.
- **Tujuan Aplikasi:** Sistem manajemen konten terpusat (Backoffice CMS) untuk mengelola seluruh katalog layanan konveksi/apparel, portofolio produksi, artikel blog, promo diskon, testimoni, FAQ, pengaturan metadata SEO global & halaman, serta aset media yang terhubung langsung ke frontend website utama melalui REST API performa tinggi ber-cache.

---

# Daftar Isi

1. [Mengenal Dashboard Admin](#1-mengenal-dashboard-admin)
2. [Cara Login & Keamanan Akun](#2-cara-login--keamanan-akun)
3. [Panduan Setiap Menu](#3-panduan-setiap-menu)
   - [3.1. Services (Layanan Apparel)](#31-services-layanan-apparel)
   - [3.2. Categories (Kategori Blog & Portofolio)](#32-categories-kategori-blog--portofolio)
   - [3.3. Blogs (Artikel & Wawasan Apparel)](#33-blogs-artikel--wawasan-apparel)
   - [3.4. Portfolio (Katalog & Hasil Produksi)](#34-portfolio-katalog--hasil-produksi)
   - [3.5. Promos (Promo, Diskon & Voucher)](#35-promos-promo-diskon--voucher)
   - [3.6. Testimonials (Ulasan & Kepuasan Klien)](#36-testimonials-ulasan--kepuasan-klien)
   - [3.7. FAQs (Tanya Jawab Pelanggan)](#37-faqs-tanya-jawab-pelanggan)
   - [3.8. SEO (Page SEO & Global Defaults)](#38-seo-page-seo--global-defaults)
   - [3.9. Teams (Manajemen Pengguna & Staf)](#39-teams-manajemen-pengguna--staf)
   - [3.10. Media Library (Pusat Aset & Gambar)](#310-media-library-pusat-aset--gambar)
   - [3.11. Settings (Pengaturan Situs & Teknis)](#311-settings-pengaturan-situs--teknis)
   - [3.12. Recycle Bin / Trash (Pemulihan Data Terhapus)](#312-recycle-bin--trash-pemulihan-data-terhapus)
   - [3.13. Activity Logs (Audit Trail Jejak Aktivitas)](#313-activity-logs-audit-trail-jejak-aktivitas)
4. [Panduan Lengkap SEO & Pengaruhnya](#4-panduan-lengkap-seo--pengaruhnya)
5. [Alur Kerja Konten (Content Workflow)](#5-alur-kerja-konten-content-workflow)
6. [Aturan Bisnis Sistem (Business Rules)](#6-aturan-bisnis-sistem-business-rules)
7. [Matriks Hak Akses & Peran (Role-Based Access)](#7-matriks-hak-akses--peran-role-based-access)
8. [Integrasi Sistem Pihak Ketiga & Background Tasks](#8-integrasi-sistem-pihak-ketiga--background-tasks)
9. [Panduan Troubleshooting (Penyelesaian Masalah)](#9-panduan-troubleshooting-penyelesaian-masalah)
10. [Checklist Sebelum Publish Konten](#10-checklist-sebelum-publish-konten)
11. [Ringkasan Praktik Terbaik (Do & Don't)](#11-ringkasan-praktik-terbaik-do--dont)

---

# 1. Mengenal Dashboard Admin

Dashboard adalah pusat kendali dan pemantauan performa website Brava. Saat pertama kali masuk, admin akan melihat ringkasan volume konten dan analitik trafik pengunjung yang terintegrasi langsung dengan Google Analytics 4 (GA4).

```
+-----------------------------------------------------------------------------------+
| BRAVA CMS DASHBOARD                                                               |
+------------------+------------------+--------------------+------------------------+
| 📦 Services (12) | 📰 Blog Posts(48)| 🏷️ Promos (3)      | 👥 Team/Users (8)      |
+------------------+------------------+--------------------+------------------------+
| [🔴 LIVE] Pengunjung Aktif Saat Ini: 14 User (Realtime 30 menit terakhir)         |
+-----------------------------------------------------------------------------------+
| 📊 Filter Periode: [ 7H ] [ 30H (Default) ] [ 90H ] [ 1Y ]                        |
|                                                                                   |
| • Visitors Today   • Pageviews Today  • Sessions Today  • Bounce Rate  • Duration |
|   142 (+18 vs H-1)   480 (Total 14.2k)  190 (Total 5.8k)  42.5%          02:45    |
+-----------------------------------------------------------------------------------+
| 📈 Grafik Tren Trafik (Visitors vs Pageviews) | 🍩 Sumber Trafik (Google/IG/Direct)|
+-----------------------------------------------+-----------------------------------+
| 📱 Perangkat (Mobile 68%, Desktop 32%)        | 🏙️ Kota Teraktif (Jakarta, Sby)   |
| 📄 Top 10 Halaman Terpopuler                  | 💡 Marketing Insight Otomatis     |
+-----------------------------------------------------------------------------------+
```

### Arti Setiap Card & Statistik:

1. **Statistik Utama Konten (4 Card Teratas):**
   - **Services:** Total layanan aktif/nonaktif yang terdaftar. (Sumber: Tabel `services`).
   - **Blog Posts:** Total seluruh postingan artikel blog. (Sumber: Tabel `blogs`).
   - **Promo:** Total promo/voucher yang ada di sistem. (Sumber: Tabel `promos`).
   - **Users:** Total akun pengguna aktif non-SuperAdmin yang terhubung ke data tim. (Sumber: Tabel `users` berelasi dengan `team_members`).

2. **Widget Realtime Live Traffic (Hanya Admin & Super Admin):**
   - Menampilkan jumlah user yang sedang aktif browsing website dalam 30 menit terakhir serta rincian URL halaman yang sedang mereka buka.
   - Sumber: Google Analytics Data API (`RunRealtimeReportRequest`). Cache otomatis refresh tiap 1–3 menit.

3. **Statistik Ringkasan Trafik (5 Card Metrik):**
   - **Visitors Today:** Jumlah pengunjung unik hari ini, dilengkapi perbandingan selisih (+ / -) terhadap kemarin.
   - **Pageviews Today & Total Periode:** Total tayangan halaman (impresi pembacaan artikel/layanan).
   - **Sessions Today & Total Periode:** Jumlah sesi kunjungan interaktif di website.
   - **Bounce Rate:** Persentase pengunjung yang keluar setelah membuka 1 halaman tanpa interaksi lanjutan (semakin kecil persentasenya, semakin bagus).
   - **Avg Duration:** Rata-rata durasi waktu yang dihabiskan pengunjung per sesi (format: `Menit:Detik`).

4. **Grafik & Analitik Visual:**
   - **Visitor Trend:** Grafik garis pergerakan harian antara pengunjung unik (*Visitors*) dan total tayangan (*Pageviews*).
   - **Traffic Sources:** Diagram lingkaran asal saluran pengunjung (Google Organic, Instagram, TikTok, WhatsApp, Direct, Facebook, Email, YouTube).
   - **Device Breakdown:** Distribusi pengguna berdasarkan perangkat (Mobile, Desktop, Tablet).
   - **Marketing Insights:** Rangkuman otomatis kanal pemasaran terbaik (*Top Channel*), perangkat dominan (*Dominant Device*), dan kota asal pengunjung terbanyak (*Top City*).
   - **Top Pages:** 10 halaman dengan jumlah tayangan tertinggi dan rata-rata waktu baca.
   - **Top Cities:** Sebaran geografis kota asal pengunjung di Indonesia.

> [!NOTE]
> Jika sistem belum dikonfigurasi dengan kredensial Google Analytics asli di menu Settings, sistem akan menampilkan **Data Simulasi/Dummy** agar tata letak dashboard tetap rapi, disertai notifikasi peringatan.

---

# 2. Cara Login & Keamanan Akun

Sistem Brava CMS menerapkan keamanan login tingkat enterprise untuk mencegah pembobolan brute-force dan pengambilalihan akun.

### 2.1. Proses Login
1. Buka browser dan arahkan ke alamat URL admin: `https://domain-anda.com/login` (atau `http://localhost/admin` jika menggunakan server lokal).
2. Masukkan **Email** dan **Password** yang telah didaftarkan oleh Super Admin.
3. Centang opsi **"Remember me"** jika ingin browser mengingat sesi login Anda di perangkat pribadi.
4. Klik tombol **Log in**. Jika berhasil, sistem akan mengarahkan Anda langsung ke halaman Dashboard Admin.

### 2.2. Aturan & Validasi Keamanan Login
- **Status Akun Wajib Aktif:** Pengguna yang statusnya dinonaktifkan (`is_active = false`) atau yang profil timnya telah dihapus ke Recycle Bin **tidak akan bisa login**, meskipun password yang dimasukkan benar.
- **Proteksi Brute-Force Rate Limiting:** Sistem membatasi percobaan login maksimal **5 kali percobaan salah per 1 menit**. Jika melebihi batas, akun dan alamat IP akan terkunci (*Lockout*) sementara dengan pesan hitung mundur detik/menit.

### 2.3. Lupa Password
> [!IMPORTANT]
> Demi alasan privasi dan keamanan server, rute publik `forgot-password` dan `reset-password` dinonaktifkan (menghasilkan status *404 Not Found*). Jika seorang Admin atau Staf lupa password, hubungi **Super Admin** untuk mereset password baru melalui menu **Teams → Reset Password**.

### 2.4. Manajemen Profil & Logout
- **Edit Profil Sendiri:** Klik avatar di pojok kanan atas → Pilih **Profile** (atau akses rute `/profile`) untuk mengubah Nama, Email, Foto Profil, Jabatan, dan Password pribadi Anda.
- **Logout:** Klik tombol **Log Out** pada sidebar bawah atau menu dropdown avatar. Sistem akan memunculkan dialog konfirmasi untuk mencegah logout tidak sengaja, lalu membersihkan seluruh sesi (*invalidate session*) dan token keamanan (*regenerate CSRF token*).

---

# 3. Panduan Setiap Menu

Brava CMS dilengkapi fitur **Dwi-Bahasa (Bilingual: Bahasa Indonesia & English)** pada hampir seluruh modul konten. Admin dapat berpindah antara tab **Bahasa Indonesia (Default)** dan tab **English** saat membuat atau mengedit konten.

---

## 3.1. Services (Layanan Apparel)

### Fungsi
Mengelola kategori besar layanan konveksi/garmen yang ditawarkan oleh Brava (contoh: *Kemeja PDH/PDL, Rompi Safety / Vest, Jersey Custom, Kaos Polo Bordir, Jaket Bomber Perusahaan*). Halaman ini menjadi induk layanan yang nanti dikaitkan ke portofolio.

### Cara Menggunakan
1. Masuk ke menu **Services** pada sidebar.
2. Klik tombol **+ Add Service** di kanan atas.
3. Pada tab **Bahasa Indonesia**, isi Judul Layanan, Slug, dan Deskripsi lengkap layanan.
4. Pada tab **English**, isi versi Bahasa Inggris (jika dikosongkan, sistem otomatis menggunakan teks bahasa Indonesia sebagai fallback).
5. Atur **Photo Alt Text** untuk aksesibilitas dan SEO gambar.
6. Upload gambar layanan atau pilih dari **Media Picker**.
7. Tentukan **Sort Order** (urutan tampil di web, angka 1 tampil paling awal).
8. Pastikan sakelar **Active** dalam posisi aktif (hijau).
9. Klik **Save Service**.

### Field yang Harus Diisi

| Field | Wajib | Tipe Data | Fungsi & Pengaruh ke Website |
| :--- | :---: | :--- | :--- |
| **Title (ID)** | Ya | Teks (Maks 255) | Nama layanan dalam Bahasa Indonesia (H1 di halaman detail layanan). |
| **Title (EN)** | Tidak | Teks (Maks 255) | Nama layanan versi Bahasa Inggris untuk pengunjung internasional. |
| **Slug (ID)** | Ya | Teks Unik | Struktur URL halaman layanan (misal: `/id/services/kemeja-pdh-custom`). |
| **Slug (EN)** | Tidak | Teks | Struktur URL versi Inggris (misal: `/en/services/custom-pdh-shirts`). |
| **Description (ID/EN)** | Tidak | Rich Text / Teks | Penjelasan spesifikasi bahan standar, minimal order (MOQ), dan benefit layanan. |
| **Photo** | Tidak | Gambar (URL/File) | Foto representasi layanan (tampil di kartu grid katalog homepage & services). |
| **Photo Alt Text** | Tidak | Teks (Maks 255) | Label pembaca layar & kata kunci gambar untuk Google Image Search. |
| **Sort Order** | Ya | Angka Positif | Menentukan urutan prioritas posisi layanan di frontend (1, 2, 3, dst). |
| **Active** | Ya | Toggle Boolean | Jika nonaktif, layanan disembunyikan dari frontend dan API publik. |

### Yang Terjadi Setelah Disimpan
- Layanan langsung tampil pada menu *Services* di website dan dropdown filter portofolio.
- Cache API publik (`/api/v1/services`) dan sitemap XML otomatis di-refresh (*cache purge*).

### Rule Sistem
- **Slug ID wajib unik:** Tidak boleh ada dua layanan dengan slug bahasa Indonesia yang persis sama.
- **Relasi Portofolio:** Jika sebuah layanan memiliki relasi dengan item portofolio, menghapus layanan akan memasukkannya ke Recycle Bin (Soft Delete), sehingga relasi data portofolio tetap aman.

### Tips Admin
> [!TIP]
> Isi deskripsi layanan dengan menyertakan jenis kain unggulan (misal: American Drill, Japan Drill, Dryfit, Lacoste) dan kisaran waktu pengerjaan agar calon klien mendapatkan kejelasan informasi sebelum memesan via WhatsApp.

---

## 3.2. Categories (Kategori Blog & Portofolio)

### Fungsi
Mengelompokkan artikel blog dan hasil karya portofolio agar pengunjung website mudah memfilter konten berdasarkan topik atau jenis industri klien.

### Cara Menggunakan
1. Buka menu **Categories** di sidebar.
2. Klik **+ Add Category**.
3. Pilih **Type** kategori: pilih `Blog` untuk artikel atau `Portfolio` untuk hasil produksi.
4. Isi Nama Kategori (ID & EN) dan Slug.
5. (Opsional) Isi Deskripsi singkat mengenai kategori tersebut.
6. Klik **Save Category**.

### Field yang Harus Diisi

| Field | Wajib | Tipe Data | Fungsi & Pengaruh ke Website |
| :--- | :---: | :--- | :--- |
| **Type** | Ya | Dropdown (`blog`, `portfolio`) | Menentukan apakah kategori ini muncul di modul Blog atau Portofolio. |
| **Name (ID)** | Ya | Teks (Maks 255) | Nama kategori bahasa Indonesia (misal: *Tips Bahan, Instansi Pemerintah*). |
| **Name (EN)** | Tidak | Teks (Maks 255) | Nama kategori bahasa Inggris (misal: *Fabric Guides, Corporate Client*). |
| **Slug (ID)** | Ya | Teks Unik | URL filter kategori di frontend (misal: `/blogs?category=tips-bahan`). |
| **Slug (EN)** | Tidak | Teks | URL filter kategori bahasa Inggris. |
| **Description (ID/EN)** | Tidak | Teks | Penjelasan maksud klasifikasi kategori. |

### Yang Terjadi Setelah Disimpan
- Kategori langsung dapat dipilih saat membuat artikel blog atau menambahkan portofolio baru.
- Muncul sebagai tombol tab filter di halaman publik `/blogs` dan `/portfolio`.

### Rule Sistem
- Slug kategori ID tidak boleh duplikat dengan kategori lain yang masih aktif.
- Kategori yang dihapus akan masuk ke Recycle Bin dan relasinya pada postingan lama tidak akan merusak tampilan.

---

## 3.3. Blogs (Artikel & Wawasan Apparel)

### Fungsi
Media publikasi artikel edukasi, panduan memilih bahan kain, tren seragam kantor, berita perusahaan, dan teknik *Search Engine Optimization (SEO)* untuk mendatangkan pengunjung organik dari Google.

### Cara Menggunakan
1. Buka menu **Blogs** di sidebar.
2. Klik tombol **+ Add Blog Post**.
3. Pada tab **Bahasa Indonesia**:
   - Tulis **Judul Artikel (Title)** yang memikat dan mengandung kata kunci.
   - Slug otomatis terbentuk dari judul (bisa dikustomisasi).
   - Isi **Ringkasan (Excerpt)** sebagai pengantar artikel (1–2 kalimat singkat).
   - Tulis isi artikel lengkap pada editor **Content** (bisa menyisipkan heading, list, kutipan, dan foto).
4. Pada tab **English**, lengkapi terjemahan konten artikel jika ada.
5. Pilih **Kategori** artikel (bisa memilih lebih dari 1 kategori).
6. Upload **Featured Image** (gambar utama artikel) dan isi **Alt Text**.
7. Atur panel **SEO & OpenGraph Settings**:
   - Isi *Meta Title* (50–60 karakter) & *Meta Description* (150–160 karakter).
   - Atur *Custom OG Image* jika ingin thumbnail share sosmed berbeda dari foto cover artikel.
   - Pilih *Schema Type* (disarankan `BlogPosting` atau `Article`).
   - Pastikan *Allow Search Indexing (Robots Index)* dan *Robots Follow* tercentang aktif.
8. Atur **Status**:
   - `Draft`: Konten belum selesai/disimpan sebagai konsep.
   - `Published`: Konten tayang di website.
   - `Archived`: Konten diarsipkan dan disembunyikan dari publik.
9. Tentukan **Published Date** (jika dikosongkan saat memilih status Published, sistem otomatis mengisinya dengan waktu saat ini).
10. Centang **Is Featured** jika ingin artikel ini muncul di banner utama/slider atas blog.
11. Klik **Save Blog Post**.

### Field yang Harus Diisi

| Field | Wajib | Tipe Data | Fungsi & Pengaruh ke Website |
| :--- | :---: | :--- | :--- |
| **Title (ID)** | Ya | Teks (Maks 255) | Judul utama artikel (Tag H1 pada halaman single blog). |
| **Slug (ID)** | Ya | Teks Unik | Alamat URL artikel (misal: `/id/blogs/cara-memilih-bahan-pdh-terbaik`). |
| **Excerpt (ID/EN)** | Tidak | Teks (Maks 500) | Cuplikan singkat artikel pada kartu grid blog & fallback meta description. |
| **Content (ID/EN)** | Tidak | HTML / Rich Text | Isi lengkap artikel (sanitasi HTML otomatis terhadap script berbahaya). |
| **Featured Image** | Tidak | Gambar (URL/File) | Thumbnail kartu artikel dan gambar pembuka di halaman artikel. |
| **Featured Image Alt** | Tidak | Teks (Maks 255) | Deskripsi gambar untuk algoritma Google Image & tunanetra. |
| **Category IDs** | Tidak | Checkbox Multi | Kategori artikel untuk memudahkan pembaca menelusuri topik serupa. |
| **Status** | Ya | Dropdown (`draft`, `published`, `archived`) | Pengendali keterlihatan artikel di frontend API. |
| **Published At** | Tidak | Tanggal & Jam | Jadwal publikasi artikel (mendukung penerbitan masa lalu atau masa depan). |
| **Is Featured** | Ya | Toggle Boolean | Menandai artikel unggulan / headline di beranda blog. |
| **Meta Title (ID/EN)** | Tidak | Teks (Maks 70) | Judul biru yang muncul di halaman hasil pencarian Google (SERP). |
| **Meta Description (ID/EN)** | Tidak | Teks (Maks 160) | Deskripsi abu-abu 2 baris di bawah judul Google Search. |
| **OG Image** | Tidak | Gambar (URL/File) | Banner gambar yang muncul saat link artikel di-share ke WhatsApp / FB. |
| **Schema Type** | Tidak | Dropdown | Structured Data untuk Google: `BlogPosting`, `Article`, `NewsArticle`. |
| **Robots Index & Follow** | Ya | Toggle | Izin untuk Google mengindeks artikel dan merambati link di dalamnya. |

### Yang Terjadi Setelah Disimpan
- Jika status `Published` dan tanggal `published_at` sudah tiba, artikel langsung muncul di halaman `/blogs` dan masuk dalam `/sitemap.xml`.
- Penulis (*Author*) otomatis ditautkan ke akun Admin yang sedang login saat pembuatan artikel.

### Rule Sistem
- **Keunikan Slug:** Slug artikel ID tidak boleh sama dengan artikel lain (kecuali artikel yang sudah dihapus permanen).
- **Fallback SEO Cerdas:** Jika Meta Title atau Meta Description dikosongkan, sistem otomatis mengambil Judul utama dan Excerpt/potongan 160 karakter pertama teks konten sebagai gantinya.
- **Proteksi Hapus Pengguna:** Akun admin yang telah menulis artikel blog tidak dapat dihapus sembarangan dari sistem sebelum artikelnya dialihkan ke penulis lain atau dihapus.

---

## 3.4. Portfolio (Katalog & Hasil Produksi)

### Fungsi
Menampilkan rekam jejak hasil pengerjaan pesanan nyata dari klien (klien korporat, universitas, instansi BUMN/Pemerintah, komunitas). Modul ini merupakan etalase bukti kualitas dan kredibilitas utama Brava.

### Cara Menggunakan
1. Buka menu **Portfolio** di sidebar → Klik **+ Add Portfolio Item**.
2. Pilih induk **Service** terkait (misal: *Rompi PDH & Vest Tactical*).
3. Centang **Categories** yang relevan (misal: *Instansi Pemerintah*).
4. Pada tab **Bahasa Indonesia**:
   - Isi **Judul Portofolio** (contoh: *Produksi Kemeja Tactical KORPRI Nasional*).
   - Isi **Slug** URL.
   - Tulis **Deskripsi** singkat mengenai latar belakang pesanan atau spesifikasi pengerjaan.
   - Masukkan **Spesifikasi Produk** secara dinamis (Klik *+ Tambah Spesifikasi*, isi Key & Value, contoh: `Bahan`: `Japan Drill Original`, `Bordir`: `Komputer 12.000 Stitches`).
   - Masukkan **Fitur Produk** (Klik *+ Tambah Fitur*, contoh: `Ventilasi Udara Airflow Belakang`, `Kantong Tersembunyi`).
5. Upload **Cover Photo** (Wajib) sebagai foto pameran utama.
6. Upload **Gallery Photos** (Maksimal 4 foto tambahan) untuk memperlihatkan detail jahitan, kancing, atau tampak belakang.
7. Isi nama **Client** (misal: *Kementerian Dalam Negeri*) dan **Completion Date** (tanggal serah terima pesanan).
8. Atur panel **SEO & OpenGraph** (Schema Type: `CreativeWork` atau `Product`).
9. Pastikan sakelar **Active** dalam kondisi aktif → Klik **Save Portfolio Item**.

### Field yang Harus Diisi

| Field | Wajib | Tipe Data | Fungsi & Pengaruh ke Website |
| :--- | :---: | :--- | :--- |
| **Service ID** | Ya | Dropdown | Menghubungkan portofolio dengan layanan induknya di katalog. |
| **Title (ID)** | Ya | Teks (Maks 255) | Nama proyek produksi apparel. |
| **Slug (ID)** | Ya | Teks Unik | URL detail portofolio (misal: `/id/portfolio/rompi-tactical-korpri`). |
| **Description (ID/EN)** | Tidak | Teks Area | Rincian pengerjaan, tantangan desain, dan kepuasan klien. |
| **Specifications (ID/EN)** | Tidak | Key-Value Array | Tabel detail bahan, benang, kancing, zipper, jenis sablon/bordir. |
| **Features (ID/EN)** | Tidak | List String Array | Poin-poin keunggulan produk seragam yang dibuat. |
| **Cover Photo** | Ya | Gambar (URL/File) | Foto utama portofolio resolusi tinggi. |
| **Gallery Photos** | Tidak | Multi Gambar (Maks 4) | Foto detail macro jahitan, logo bordir, varian tampak depan/belakang. |
| **Client** | Tidak | Teks (Maks 255) | Nama instansi / perusahaan pemesan. |
| **Completion Date** | Tidak | Tanggal (YYYY-MM-DD) | Tanggal selesai pengerjaan pesanan. |
| **Active** | Ya | Toggle Boolean | Status tampil di katalog portofolio publik. |
| **SEO Settings** | Tidak | Form Group SEO | Metadata pencarian Google & OpenGraph media sosial. |

### Yang Terjadi Setelah Disimpan
- Hasil produksi langsung tampil pada grid portofolio website dan galeri pada halaman detail layanan terkait.
- Jika foto galeri diunggah, pengunjung web dapat melihat foto dalam format lightbox slider interaktif.

### Rule Sistem
- **Maksimal 4 Foto Galeri:** Sistem membatasi maksimal 4 foto galeri pendukung di luar Cover Photo agar loading halaman tetap super cepat.
- **Upload Cover Wajib Didahulukan:** Tombol upload galeri foto akan terkunci sampai Cover Photo telah diatur.
- **Set as Cover:** Pada mode edit, admin dapat menukar foto galeri menjadi Cover Photo dengan satu klik (*Set as Cover*).

---

## 3.5. Promos (Promo, Diskon & Voucher)

### Fungsi
Mengelola kampanye pemasaran, diskon potongan harga, cashback, voucher pemesanan seragam massal, serta tombol pesan instan WhatsApp yang sudah terisi template pesan otomatis (*Pre-filled text*).

### Cara Menggunakan
1. Masuk ke menu **Promos** di sidebar → Klik **+ Add Promo**.
2. Isi **Title** Promo (contoh: *Promo Diskon 15% Seragam Kantor Akhir Tahun*).
3. Isi **Badge Text** (label stiker kecil di pojok banner, contoh: `HEMAT 15%`, `HOT DEAL`, `LIMITED TIME`).
4. Isi **Discount Info** (contoh: `Potongan Rp 15.000 / pcs min order 50 pcs`).
5. Tulis **Description** ketentuan dan syarat promo.
6. Upload **Banner Promo Image** & isi Alt Text.
7. Tentukan periode promo: **Valid From** (Tanggal mulai) dan **Valid Until** (Tanggal berakhir).
8. Tulis pesan **WhatsApp Template** (pesan default yang otomatis muncul saat klien klik klaim via WhatsApp).
9. Jika promo ini adalah program unggulan nomor 1, centang **Set as Highlight (Banner Utama)**.
10. Klik **Save Promo**.

### Field yang Harus Diisi

| Field | Wajib | Tipe Data | Fungsi & Pengaruh ke Website |
| :--- | :---: | :--- | :--- |
| **Title (ID)** | Ya | Teks (Maks 255) | Judul promo yang tampil di halaman promo dan banner. |
| **Badge Text (ID/EN)** | Tidak | Teks Pendek | Badge pita penarik perhatian (misal: `BEST SELLER`, `PROMO KORPORAT`). |
| **Discount Info (ID/EN)** | Tidak | Teks | Rincian nominal diskon atau bonus merchandise. |
| **Description (ID/EN)** | Tidak | Teks Area | Syarat & ketentuan berlaku (S&K). |
| **Banner Image** | Tidak | Gambar (URL/File) | Desain grafis flyer/banner promo. |
| **Valid From** | Tidak | Tanggal & Jam | Waktu promo mulai berlaku (sebelum tanggal ini = status `Coming Soon`). |
| **Valid Until** | Tidak | Tanggal & Jam | Waktu promo berakhir (setelah tanggal ini = status `Expired`). |
| **WhatsApp Template** | Tidak | Teks | Teks chat WhatsApp otomatis saat tombol "Klaim Promo" diklik klien. |
| **Is Highlighted** | Ya | Toggle | Menjadikan promo ini sebagai Hero Banner Utama di halaman promo. |
| **Is Active** | Ya | Toggle | Mengaktifkan atau menonaktifkan promo secara manual kapan saja. |

### Yang Terjadi Setelah Disimpan
- Promo otomatis memiliki tombol URL WhatsApp resmi: `https://wa.me/628xxxxxxxx?text=Halo%20Brava...` yang mengambil nomor telepon CS dari pengaturan sistem.
- Promo yang di-*Highlight* akan tampil paling atas berukuran besar.

### Rule Sistem
- **Aturan Single Highlight:** Hanya boleh ada **1 promo** yang berstatus Highlight di seluruh website. Jika Admin menandai promo baru sebagai Highlight, promo lama otomatis dicabut highlight-nya.
- **Proteksi Kedaluwarsa:** Promo yang dinonaktifkan (`is_active = false`) atau yang sudah lewat tanggal kedaluwarsanya (`valid_until` masa lalu) **dilarang keras dijadikan Highlight**. Sistem akan memunculkan error validasi.
- **Pembersihan Otomatis Tiap Malam (Scheduler Cron):** Server secara otomatis menjalankan perintah `promos:clear-stale-highlights` setiap malam untuk mencabut status highlight dari promo yang masa berlakunya telah habis.

---

## 3.6. Testimonials (Ulasan & Kepuasan Klien)

### Fungsi
Menampilkan testimoni, kutipan ulasan, rating bintang, dan identitas klien yang puas dengan hasil jahitan dan ketepatan waktu pengiriman Brava.

### Cara Menggunakan
1. Masuk ke menu **Testimonials** → Klik **+ Add Testimonial**.
2. Masukkan **Client Name** (bisa disertai nama instansi/jabatan, contoh: *Bpk. Hendra Gunawan - HRD PT Maju Logistik*).
3. Tulis isi **Content / Testimonial Text** ulasan klien.
4. Pilih **Rating** bintang (skala 1 sampai 5).
5. Upload foto **Avatar** klien atau logo perusahaan klien.
6. Tentukan **Sort Order** dan pastikan **Active** menyala.
7. Klik **Save Testimonial**.

### Field yang Harus Diisi

| Field | Wajib | Tipe Data | Fungsi & Pengaruh ke Website |
| :--- | :---: | :--- | :--- |
| **Client Name (ID/EN)**| Ya | Teks (Maks 255) | Nama pemberi ulasan dan perusahaannya. |
| **Content (ID/EN)** | Ya | Teks Area | Kalimat kepuasan mengenai kualitas kain, jahitan, atau keramahan CS. |
| **Rating** | Ya | Angka (1–5) | Jumlah bintang kepuasan (Default: 5). |
| **Avatar** | Tidak | Gambar (URL/File) | Foto profil orang atau logo brand klien. |
| **Avatar Alt Text** | Tidak | Teks | Alt text gambar untuk keramahan SEO. |
| **Sort Order** | Ya | Angka Positif | Urutan penempatan di slider/carousel testimoni homepage. |
| **Active** | Ya | Toggle Boolean | Status tampil di website. |

---

## 3.7. FAQs (Tanya Jawab Pelanggan)

### Fungsi
Menjawab pertanyaan umum calon pelanggan seputar cara pemesanan, minimal order, ketersediaan sampel kain, garansi ukuran, hingga estimasi waktu produksi (mengurangi beban tanya-jawab berulang tim CS).

### Cara Menggunakan
1. Buka menu **FAQs** → Klik **+ Add FAQ**.
2. Pada tab **Bahasa Indonesia**, tulis **Question** (Pertanyaan) dan **Answer** (Jawaban).
3. Pada tab **English**, isi versi Bahasa Inggris.
4. Tentukan nomor urut tampil pada **Sort Order**.
5. Pastikan status **Active** tercentang → Klik **Save FAQ**.

### Field yang Harus Diisi

| Field | Wajib | Tipe Data | Fungsi & Pengaruh ke Website |
| :--- | :---: | :--- | :--- |
| **Question (ID)** | Ya | Teks (Maks 255) | Kalimat pertanyaan yang sering diajukan klien. |
| **Question (EN)** | Tidak | Teks (Maks 255) | Kalimat pertanyaan dalam bahasa Inggris. |
| **Answer (ID/EN)** | Ya | Rich Text / HTML | Penjelasan jawaban lengkap, ramah, dan solutif. |
| **Sort Order** | Ya | Angka Positif | Urutan accordion FAQ dari atas ke bawah. |
| **Active** | Ya | Toggle Boolean | Menampilkan/menyembunyikan FAQ dari website. |

### Rule Sistem
- **Sanitasi HTML Otomatis:** Sistem membersihkan teks jawaban dari script jahat (*XSS cleaner*) secara otomatis sehingga aman jika menyalin teks dari sumber lain.

---

## 3.8. SEO (Page SEO & Global Defaults)

### Fungsi
Pusat pengaturan metadata mesin pencari (Google, Bing, Yahoo) dan pratinjau media sosial (OpenGraph) untuk setiap halaman website secara spesifik maupun fallback global seluruh domain.

```
+-----------------------------------------------------------------------------------+
| MENU SEO: DUA LEVEL KONTROL METADATA                                              |
+-----------------------------------------------------------------------------------+
| 1. GLOBAL DEFAULTS (Super Admin Saja)                                             |
|    → Default Meta Title (ID/EN)                                                   |
|    → Default Meta Description (ID/EN)                                             |
|    → Default OpenGraph Share Image (1200x630 px)                                  |
|    (Menjadi jaring pengaman jika halaman tidak memiliki metadata spesifik)        |
+-----------------------------------------------------------------------------------+
| 2. PAGE-LEVEL SEO (Admin & Super Admin)                                           |
|    Kelola metadata presisi per-halaman statis:                                    |
|    • / (Home)          • /about (Tentang Kami)    • /services (Layanan)           |
|    • /portfolio        • /blogs                   • /promos                       |
|    • /contact                                                                     |
|    (Pengaturan: Meta Title, Meta Description, Custom OG Image, Robots Index/Follow)|
+-----------------------------------------------------------------------------------+
```

### Cara Menggunakan
1. **Mengubah Fallback Global (Super Admin):**
   - Buka **SEO** → Klik tombol **Edit Defaults** pada kotak atas.
   - Perbarui *Default Meta Title*, *Default Meta Description*, dan *Default OpenGraph Image*.
   - Klik **Update**.
2. **Mengubah SEO Per-Halaman (Admin & Super Admin):**
   - Pada tabel halaman (**home, about, services, portfolio, blogs, promos, contact**), klik ikon pensil (**Edit**) pada halaman yang ingin dioptimalkan.
   - Isi *Meta Title (ID & EN)* dan *Meta Description (ID & EN)*.
   - Pilih *OG Image* khusus halaman tersebut.
   - Atur sakelar *Allow Search Indexing (Robots Index)*: Hijau (Index) jika ingin muncul di Google, atau Abu-abu (Noindex) jika ingin disembunyikan.
   - Klik **Update**.

### Rule Sistem
- **Hierarki Fallback:** Jika metadata pada tingkat halaman dikosongkan, API secara cerdas akan mengambil nilai dari *Global Defaults*. Jika Global Defaults kosong, sistem menggunakan data default bawaan sistem. Website tidak akan pernah mengalami error metadata kosong (*null metadata safe*).

---

## 3.9. Teams (Manajemen Pengguna & Staf)

### Fungsi
Mengelola daftar anggota tim perusahaan yang ditampilkan di website (halaman *About Us*) sekaligus mengelola hak akses akun login CMS untuk Admin dan Staf operasional.

### Cara Menggunakan
1. Buka menu **Teams** di sidebar → Klik **+ Add Team Member**.
2. Isi **Name** (Nama Lengkap) dan **Position** (Jabatan di perusahaan, contoh: *Head of Production, Senior Fashion Designer, Quality Control Specialist*).
3. Masukkan **Email** dan nomor **Phone**.
4. Upload foto **Avatar** berlatar belakang rapi.
5. **Membuatkan Akun Login CMS (Opsional):**
   - Centang kotak **"Create user account for login"**.
   - Masukkan **Password** (minimal 8 karakter) dan konfirmasi password.
   - Pilih **Role**: `Admin` (akses penuh konten dan analitik) atau `Staff` (akses input konten saja).
6. Klik **Save Team Member**.

### Mereset Password Staf Lain
1. Pada tabel Teams, klik tombol kunci / gembok (**Reset Password**) pada nama staf terkait.
2. Masukkan password baru dan konfirmasi password → Klik **Update Password**.

### Rule Sistem
- **Proteksi Akun Sendiri:** Admin atau Super Admin yang sedang login **dilarang menonaktifkan atau menghapus akun mereka sendiri**.
- **Hierarki Role:** Hanya *Super Admin* yang dapat melihat dan mengelola akun sesama Super Admin. Admin biasa hanya dapat mengelola staf dan admin setara yang diizinkan.
- **Deaktivasi Otomatis:** Menghapus anggota tim yang memiliki akun login akan secara otomatis menonaktifkan status login akun tersebut (`is_active = false`) demi keamanan.

---

## 3.10. Media Library (Pusat Aset & Gambar)

### Fungsi
Pusat manajemen seluruh berkas digital website (foto produk seragam, infografis bahan kain, banner promo, logo, dan dokumen PDF spesifikasi). Dilengkapi sistem konversi otomatis ke format generasi terbaru **WebP** berbobot sangat ringan.

### Cara Menggunakan
1. Buka menu **Media** di sidebar.
2. Klik tombol **+ Upload Media**.
3. Pilih file dari komputer Anda (Format: JPG, JPEG, PNG, GIF, WEBP; Maksimal 10 MB per file).
4. Tentukan **Collection** (Kategori folder aset, misal: `portfolio`, `blogs`, `services`, `seo`, `general`).
5. Isi **Alt Text** yang menggambarkan isi foto.
6. Klik **Upload**.

### Menggunakan Media Picker pada Formulir Konten
Saat mengisi form Blog, Portfolio, atau Promo, klik tombol **"Pilih dari Media Library"**. Modal galeri akan terbuka:
- Anda dapat mencari gambar berdasarkan nama file atau alt text.
- Memfilter berdasarkan koleksi.
- Mengunggah foto baru secara instan tanpa perlu keluar dari form pengisian.

### Rule Sistem
- **Kompresi & Resizing Otomatis:** Setiap gambar berukuran raksasa yang diunggah akan otomatis di-skala maksimal lebar **1920 piksel** dan dikonversi ke format **WebP Quality 85–100%**. Penghematan ukuran file mencapai 70–85% tanpa menurunkan ketajaman visual.
- **Proteksi Hapus Aman (*Safe Deletion Shield*):** Sistem secara otomatis memeriksa apakah gambar sedang dipakai di postingan Blog, Portofolio, Layanan, atau Testimoni. Jika file gambar masih digunakan, tombol hapus akan **ditolak** dengan pesan peringatan rincian konten yang sedang memakai gambar tersebut.
- **Batas Keamanan Resolusi:** File gambar di atas resolusi **20 Megapiksel** atau dimensi lebih dari 6.000 piksel per sisi akan ditolak oleh sistem demi menjaga stabilitas memori server.

---

## 3.11. Settings (Pengaturan Situs & Teknis)

### Fungsi
Pusat konfigurasi informasi kontak bisnis, tautan media sosial, ID pelacakan iklan/analitik, dan identitas perusahaan.

### Pembagian Kelompok Pengaturan:

```
+------------------------------------------------------------------------------------+
| PENGATURAN UMUM & KONTAK (Dapat diedit oleh Admin & Super Admin)                  |
+------------------------------------------------------------------------------------+
| 🏢 General: Site Name, Site Description                                            |
| 📞 Contact: Nomor WhatsApp CS (format: 628xxx), Telepon, Email Kantor, Alamat Fisik|
| 🌐 Social Media: URL Facebook, Instagram, YouTube, TikTok, X (Twitter), LinkedIn   |
+------------------------------------------------------------------------------------+
| PENGATURAN TEKNIS & SISTEM (Hanya dapat diedit oleh SUPER ADMIN)                  |
+------------------------------------------------------------------------------------+
| 📊 Google Analytics ID (format: G-XXXXXXXXXX)                                      |
| 🔍 Google Site Verification Code (Tag HTML Webmaster)                              |
| 🏷️ Schema.org Organization JSON-LD                                                 |
| 💰 Google AdSense: Enabled Switch, Publisher ID (ca-pub-xxx), AdSlot 1, AdSlot 2   |
| 📈 GA4 Reporting: GA4 Numeric Property ID & Service Account Private Key JSON       |
+------------------------------------------------------------------------------------+
```

### Rule Sistem
- Format nomor WhatsApp wajib diawali kode negara tanpa spasi atau tanda plus (contoh: `6281234567890`). Sistem otomatis menormalisasi nomor yang diawali angka `0` menjadi `62`.
- Pengaturan grup `system` (kunci GA4) disimpan aman di server dan **tidak pernah dibocorkan** ke endpoint API publik demi menjaga keamanan akun Google Cloud perusahaan.

---

## 3.12. Recycle Bin / Trash (Pemulihan Data Terhapus)

### Akses: Khusus Super Admin

### Fungsi
Tempat penampungan sementara (*Soft Delete*) untuk semua data yang dihapus dari 8 modul sistem (Blogs, Portfolio, Services, Testimonials, FAQs, Categories, Teams, Promos). Data yang tidak sengaja terhapus dapat dipulihkan kapan saja dengan satu klik.

### Cara Menggunakan
1. Masuk ke menu **Recycle Bin** di sidebar bawah.
2. Klik tab modul yang ingin diperiksa (contoh: *Blogs*, *Portfolio*, dll).
3. **Memulihkan Data:** Klik tombol hijau **Restore** pada baris data. Data akan langsung kembali aktif di tabel utama.
4. **Menghapus Permanen Satuan:** Klik tombol merah **Force Delete**. Data dan berkas foto fisik terkait di server akan dimusnahkan selamanya.
5. **Mengosongkan Keranjang:** Klik tombol **Empty Trash** di pojok kanan atas untuk memusnahkan seluruh data terhapus pada modul tersebut.

---

## 3.13. Activity Logs (Audit Trail Jejak Aktivitas)

### Akses: Khusus Super Admin

### Fungsi
Merekam seluruh riwayat tindakan operasional yang dilakukan oleh setiap pengguna di sistem secara kronologis (*Who did what and when*).

### Data yang Dicatat:
- **Pengguna (User):** Nama admin/staf yang melakukan aksi.
- **Event:** Jenis aksi (`created`, `updated`, `deleted` (masuk trash), `restored`, `force_deleted`).
- **Modul Terkait (Loggable):** Nama item dan jenis modul yang diubah.
- **Deskripsi:** Detail perubahan ringkas.
- **Waktu:** Tanggal dan jam presisi aktivitas.

---

# 4. Panduan Lengkap SEO & Pengaruhnya

Bagian ini adalah panduan fundamental bagi Admin untuk memaksimalkan peringkat website Brava di mesin pencari Google.

```
+-----------------------------------------------------------------------------------+
| ANATOMI HASIL PENCARIAN GOOGLE (SERP) & PENGARUH KONTEN ADMIN                     |
+-----------------------------------------------------------------------------------+
| 🌐 https://bravakonveksi.com > id > blogs > panduan-kain-drill                     |
|                                                                                   |
| 🔵 Panduan Lengkap Memilih Bahan Kemeja Drill | Brava Konveksi   <-- META TITLE   |
|                                                                      (50-60 Karakter)|
| ⚫ Sedang mencari bahan seragam kerja berkualitas? Simak perbedaan  <-- META DESC   |
|    American Drill vs Japan Drill beserta kisaran harga terbaik di sini... (150-160) |
+-----------------------------------------------------------------------------------+
| 🖼️ PREVIEW SHARE WHATSAPP & MEDIA SOSIAL (OPEN GRAPH)                            |
| +-----------------------------------------------+                                 |
| |                                               | <-- OPEN GRAPH IMAGE            |
| |         [ BANNER GAMBAR 1200 x 630 px ]       |     (Rasio Ideal 1.91:1)        |
| |                                               |                                 |
| +-----------------------------------------------+                                 |
| | Panduan Lengkap Memilih Bahan Kemeja Drill    | <-- OG TITLE                    |
| | Sedang mencari bahan seragam kerja berkua...  | <-- OG DESCRIPTION              |
| | bravakonveksi.com                             |                                 |
| +-----------------------------------------------+                                 |
+-----------------------------------------------------------------------------------+
```

---

### 4.1. Meta Title (Judul Halaman Pencarian)
- **Fungsi:** Judul biru utama yang diklik oleh pencari di Google dan teks pada tab browser.
- **Pengaruh ke Google:** Faktor bobot paling tinggi dalam algoritma Google untuk mencocokkan kata kunci (*keyword matching*).
- **Dampak Jika Salah Diisi:** Jika terlalu panjang (>70 karakter), judul akan terpotong jelek dengan tanda titik-titik `...`. Jika terlalu pendek atau tidak relevan, Google akan menggantinya secara otomatis dengan teks acak dari halaman web Anda.
- **Cara Mengisi yang Benar:** Tulis kata kunci utama di depan, diikuti identitas brand di belakang. Panjang ideal: **50–60 karakter**.
  - *Contoh Benar:* `Vendor Konveksi Seragam Kerja Kantor & Pabrik | Brava Apparel` (61 karakter).
  - *Contoh Salah:* `Halaman artikel blog mengenai cara jahit seragam terbaru dan terlengkap tahun ini di jawa timur surabaya indonesia` (Terlalu panjang, keyword berantakan).

---

### 4.2. Meta Description (Deskripsi Ringkasan Pencarian)
- **Fungsi:** Paragraf penjelasan singkat di bawah judul pada hasil pencarian Google.
- **Pengaruh ke Google:** Sangat memengaruhi *Click-Through Rate (CTR)* atau minat orang untuk mengklik website Anda dibandingkan website kompetitor.
- **Dampak Jika Salah Diisi:** Jika melebihi 160 karakter, teks terpotong. Jika berisi spam kata kunci tanpa kalimat persuasif yang jelas, orang tidak akan tertarik mengklik.
- **Cara Mengisi yang Benar:** Tulis kalimat persuasif yang merangkum solusi, keunggulan, dan ajakan bertindak (*Call to Action*). Panjang ideal: **140–160 karakter**.
  - *Contoh Benar:* `Pabrik konveksi seragam kerja, kemeja PDH, rompi, dan jersey custom di Surabaya. Gratis sampel bahan, jahitan rapi bergaransi. Konsultasi sekarang!` (153 karakter).

---

### 4.3. Slug (Struktur URL Ramah Mesin Pencari)
- **Fungsi:** Alamat URL permanen sebuah konten di address bar browser.
- **Pengaruh ke Google:** Memberikan kejelasan konteks topik kepada Google bot dan meningkatkan rasa aman pengguna sebelum mengklik tautan.
- **Dampak Jika Salah Diisi:** Mengubah slug pada artikel yang sudah lama tayang di Google akan menyebabkan tautan lama menjadi rusak (*Error 404 Not Found*) dan kehilangan trafik organik jika tidak dialihkan.
- **Cara Mengisi yang Benar:** Gunakan huruf kecil semua, pisahkan tiap kata dengan tanda strip/minus (`-`), hilangkan kata sambung yang tidak perlu.
  - *Contoh Benar:* `tips-memilih-bahan-kemeja-pdh`
  - *Contoh Salah:* `Tips_Memilih Bahan Kemeja PDH 2026!! (BARU)` (Mengandung spasi dan simbol terlarang).

---

### 4.4. Open Graph (OG Title, OG Description, OG Image)
- **Fungsi:** Protokol metadata yang mengatur tampilan cuplikan kartu gambar, judul, dan deskripsi saat tautan website dibagikan ke WhatsApp, Telegram, Facebook, LinkedIn, atau Twitter/X.
- **Pengaruh:** Meningkatkan daya tarik visual di media sosial dan mendongkrak trafik rujukan (*referral traffic*).
- **Dampak Jika Salah Diisi:** Jika OG Image tidak diisi atau ukurannya tidak standar, chat WhatsApp hanya akan menampilkan tautan teks biasa tanpa kartu gambar, atau gambar terpotong tidak proporsional.
- **Cara Mengisi yang Benar:** Unggah gambar dengan rasio persegi panjang **1.91 : 1** (Dimensi optimal: **1200 x 630 piksel**, format WebP/JPG di bawah 300 KB).

---

### 4.5. Alt Image (Alternative Text Gambar)
- **Fungsi:** Teks pengganti yang dibaca oleh robot mesin pencari dan aplikasi pembaca suara tunanetra (*screen reader*) ketika gambar sedang dimuat.
- **Pengaruh ke Google:** Merupakan pintu masuk utama agar foto produk seragam dan portofolio Anda menempati peringkat atas pada **Google Images Search**.
- **Dampak Jika Salah Diisi:** Dibiarkan kosong membuat Google mengabaikan gambar Anda sebagai aset grafis tak bermakna.
- **Cara Mengisi yang Benar:** Deskripsikan isi foto secara spesifik dan natural.
  - *Contoh Benar:* `Kemeja seragam kerja lapangan PDH warna navy bahan Japan Drill bordir logo PT Pertamina`
  - *Contoh Salah:* `IMG_20260822_WA001.jpg` atau spam keyword `seragam kemeja baju kaos celana murah surabaya`.

---

### 4.6. Robots Index & Robots Follow
- **Fungsi:**
  - `Robots Index (True)`: Mengizinkan Google menyimpan halaman ke dalam database pencarian.
  - `Robots Follow (True)`: Mengizinkan Google menelusuri link yang ada di dalam halaman tersebut.
- **Pengaruh ke Google:** Jika *Robots Index* dimatikan (`Noindex`), halaman tersebut **dihapus dan diblokir total dari seluruh hasil pencarian Google**.
- **Dampak Jika Salah Diisi:** Mematikan tombol Index pada artikel blog atau portofolio penting akan mematikan seluruh potensi kunjungan pelanggan dari Google secara instan.
- **Cara Mengisi yang Benar:** Selalu biarkan sakelar *Robots Index* dan *Robots Follow* dalam posisi **AKTIF (Hijau)** untuk seluruh halaman publik. Hanya matikan jika halaman tersebut adalah halaman tes internal.

---

### 4.7. Canonical URL (Penetapan URL Asli)
- **Fungsi:** Tag di balik layar yang memberitahu Google halaman mana yang merupakan "sumber asli" jika ada konten yang mirip.
- **Implementasi Sistem Brava:** Sistem Brava CMS membuat tag canonical URL secara **otomatis 100%** berdasarkan domain resmi dan bahasa aktif (contoh: `<link rel="canonical" href="https://bravakonveksi.com/id/blogs/judul-artikel">`), sehingga Admin terbebas dari ancaman sanksi konten duplikat (*duplicate content penalty*).

---

### 4.8. Structured Data / Schema.org (JSON-LD)
- **Fungsi:** Kode data terstruktur berstandar internasional yang disematkan otomatis oleh API ke dalam format JSON-LD agar Google memahami entitas konten secara mendalam (misal: membedakan antara artikel berita, produk jualan, atau profil perusahaan).
- **Pilihan Schema pada Brava CMS:**
  - `BlogPosting` / `Article`: Untuk postingan wawasan apparel (membuat artikel berpeluang masuk carousel berita Google).
  - `CreativeWork` / `Product`: Untuk portofolio hasil produksi seragam.
  - `SpecialAnnouncement`: Untuk promo dan voucher diskon.
  - `Organization`: Identitas profil perusahaan Brava secara global.

---

### 4.9. XML Sitemap Dinamis (`/api/v1/sitemap`)
- **Fungsi:** Peta situs XML berstandar mesin pencari yang mendata seluruh tautan halaman statis, layanan, portofolio, artikel blog, promo aktif, dan kategori.
- **Pengaruh:** Mempercepat robot Google mengindeks artikel atau portofolio baru yang baru saja Anda terbitkan tanpa harus menunggu berminggu-minggu.

---

# 5. Alur Kerja Konten (Content Workflow)

Diagram alur berikut mengilustrasikan bagaimana admin memproduksi dan mempublikasikan konten berkualitas tinggi dari awal hingga tayang di website publik:

```
[ ADMIN DASHBOARD ]
        │
        ▼
[ PILIH MODUL KONTEN ] ───► (Blog / Portfolio / Service / Promo)
        │
        ▼
[ INPUT KONTEN UTAMA ]
  ├─ Judul Konten (Bahasa Indonesia)
  ├─ Generate / Periksa Slug Otomatis (Huruf kecil & strip)
  ├─ Isi Teks Lengkap / Deskripsi / Spesifikasi
  └─ Terjemahan Bahasa Inggris (Tab English)
        │
        ▼
[ PENGATURAN MEDIA ]
  ├─ Unggah Foto Utama / Cover
  ├─ Tulis Deskripsi Alt Text Gambar (SEO Image)
  └─ Unggah Galeri Pendukung (Khusus Portfolio maks 4 foto)
        │
        ▼
[ OPTIMASI SEO & OPENGRAPH ]
  ├─ Tulis Meta Title (50–60 Karakter)
  ├─ Tulis Meta Description (150–160 Karakter)
  ├─ Atur Custom OG Image (1200x630 px)
  ├─ Pilih Schema Type (JSON-LD)
  └─ Pastikan Robots Index & Follow: ON
        │
        ▼
[ KONTROL STATUS & PUBLIKASI ]
  │
  ├───► Status = DRAFT ──────► Tersimpan aman di database (Belum tayang)
  │
  └───► Status = PUBLISHED
              │
              ▼
        [ SISTEM OTOMATIS BERJALAN ]
        ├─ Sanitasi teks dari script berbahaya (XSS Protection)
        ├─ Kompresi otomatis gambar ke WebP (Quality 85%)
        ├─ Buat tag Canonical URL & Schema JSON-LD
        ├─ Bersihkan Cache API (Cache Purge)
        └─ Update XML Sitemap
              │
              ▼
        [ TAYANG DI WEBSITE UTAMA & TERINDEKS GOOGLE ]
```

---

# 6. Aturan Bisnis Sistem (Business Rules)

Berikut adalah ringkasan aturan logika bisnis yang tertanam di dalam kode program dan wajib dipahami oleh seluruh tim:

### 1. Modul Blog
- Postingan berstatus `draft` atau `archived` **tidak akan pernah muncul** di API publik frontend.
- Postingan dengan tanggal `published_at` di masa depan (*scheduled post*) tidak akan tampil di website sampai waktu tanggal tersebut telah tiba.
- Postingan unggulan (`is_featured = true`) akan diprioritaskan tampil pada slider utama beranda blog.
- Menghapus akun admin yang menjadi penulis (*Author*) artikel blog akan **ditolak sistem** sebelum seluruh artikelnya dialihkan ke penulis lain.

### 2. Modul Portfolio
- Setiap item portofolio **wajib memiliki induk Service** yang masih aktif.
- Maksimal foto galeri detail adalah **4 foto** (di luar Cover Photo).
- Cover photo wajib diunggah sebelum admin diizinkan mengunggah foto galeri detail.
- Spesifikasi dan Fitur mendukung struktur dinamis dua bahasa (*bilingual list*).

### 3. Modul Promos
- Hanya boleh ada **1 promo** berstatus `is_highlighted = true` di seluruh sistem pada satu waktu.
- Promo yang statusnya nonaktif atau tanggal berlakunya sudah habis **dilarang dijadikan Highlight**.
- Tombol WhatsApp otomatis mengonversi angka awalan `0` menjadi format internasional `62`.

### 4. Modul Media Library
- Seluruh gambar JPG/PNG yang diunggah otomatis di-skala maksimal lebar 1920px dan dikompresi menjadi format **WebP**.
- Berkas gambar yang sedang dipakai pada konten aktif **dilarang keras dihapus** (*Safe Delete Protection*). Admin harus melepas tautan foto dari artikel/portofolio terkait terlebih dahulu.
- Dimensi gambar di atas 6.000 piksel atau total resolusi lebih dari 20 Megapiksel akan otomatis ditolak.

### 5. Modul Recycle Bin & Keamanan Data
- Operasi penghapusan di seluruh modul konten menggunakan mekanisme **Soft Delete** (data tidak langsung lenyap dari database, melainkan dipindahkan ke Recycle Bin).
- Hanya user dengan role **Super Admin** yang dapat membuka Recycle Bin, memulihkan data (*restore*), atau memusnahkan data permanen (*force delete*).

---

# 7. Matriks Hak Akses & Peran (Role-Based Access)

Sistem Brava CMS membagi wewenang pengguna ke dalam 3 tingkatan peran (*Role*):

| Menu / Fitur Sistem | Super Admin | Admin | Staff | Keterangan Wewenang |
| :--- | :---: | :---: | :---: | :--- |
| **Dashboard Stats & Ringkasan Konten** | ✅ Ya | ✅ Ya | ✅ Ya | Semua staf dapat melihat jumlah konten. |
| **Dashboard GA4 Analytics & Live Traffic**| ✅ Ya | ✅ Ya | ❌ Tidak | Data trafik sensitif hanya untuk level manajemen. |
| **Services, Blogs, Portfolio, Promos** | ✅ Penuh | ✅ Penuh | ✅ Penuh | Input, edit, publish, dan hapus ke Recycle Bin. |
| **Categories, Testimonials, FAQs** | ✅ Penuh | ✅ Penuh | ✅ Penuh | Pengelolaan kategori dan konten pendukung. |
| **Media Library & Upload** | ✅ Penuh | ✅ Penuh | ✅ Penuh | Unggah dan kelola aset foto/dokumen. |
| **Page-Level SEO Settings** | ✅ Ya | ✅ Ya | ❌ Tidak | Pengaturan SEO per-halaman. |
| **Global SEO Defaults** | ✅ Ya | ❌ Tidak | ❌ Tidak | Khusus penanggung jawab teknis domain. |
| **Manajemen Teams & Akun User** | ✅ Penuh | ⚠️ Terbatas | ❌ Tidak | Admin hanya bisa mengedit staf; Super Admin kelola semua. |
| **Reset Password User Lain** | ✅ Ya | ⚠️ Staf Saja | ❌ Tidak | Dilakukan melalui menu Teams. |
| **Settings (General, Contact, Social)** | ✅ Ya | ✅ Ya | ❌ Tidak | Perubahan nomor WA, alamat kantor, medsos. |
| **Settings (AdSense, GA4, System Keys)** | ✅ Ya | ❌ Tidak | ❌ Tidak | Kunci integrasi sensitif server. |
| **Recycle Bin (Restore & Force Delete)** | ✅ Penuh | ❌ Tidak | ❌ Tidak | Otoritas pemulihan dan pembersihan data permanen. |
| **Activity Logs (Audit Trail Jejak Staf)**| ✅ Penuh | ❌ Tidak | ❌ Tidak | Pemantauan aktivitas seluruh pengguna. |

---

# 8. Integrasi Sistem Pihak Ketiga & Background Tasks

Aplikasi Brava CMS terhubung secara otomatis dengan beberapa layanan teknologi:

### 1. Google Analytics 4 (GA4) Reporting API
- **Fungsi:** Mengambil data statistik pengunjung riil (Visitors, Pageviews, Bounce Rate, Realtime active users) dari server Google ke dashboard admin.
- **Kredensial:** Membutuhkan *Numeric Property ID* dan *Service Account Key JSON* dari Google Cloud Console yang dimasukkan di menu **Settings → Technical Settings**.
- **Performa:** Data disimpan di dalam cache memory selama 2–4 jam untuk memastikan dashboard admin tetap terbuka instan tanpa membebani kuota API Google.

### 2. Frontend REST API (Headless Architecture)
- Seluruh data yang disimpan oleh admin disajikan dalam format JSON terstandarisasi melalui endpoint `/api/v1/...` berkecepatan tinggi yang dilindungi mekanisme pembatasan akses (*Throttle Rate Limiting: 60 request per menit*).
- Setiap kali admin memperbarui data konten, sistem otomatis melakukan **Cache Purge** sehingga perubahan langsung tercermin seketika di website publik.

### 3. Intervention Image Engine (WebP Converter)
- Mengompresi seluruh file media yang diunggah ke server lokal (`storage/app/public/`) menjadi format generasi masa depan WebP yang sangat disukai oleh Google Core Web Vitals.

### 4. Scheduled Cron Tasks (Scheduler Otomatis)
Sistem memiliki 2 proses otomatis harian yang berjalan tanpa intervensi manusia:
1. `promos:clear-stale-highlights` (Berjalan setiap pergantian hari / harian): Menghapus highlight dari promo yang telah kedaluwarsa.
2. `media:cleanup-filenames --remove-orphans` (Berjalan setiap pukul 03:30 pagi): Membersihkan nama file media lama yang memiliki karakter aneh dan menghapus file sampah (*orphan files*) yang tidak lagi terpakai di website.

---

# 9. Panduan Troubleshooting (Penyelesaian Masalah)

Berikut adalah solusi cepat jika admin menemui kendala teknis operasional:

| Masalah yang Terjadi | Kemungkinan Penyebab | Solusi Langkah Demi Langkah |
| :--- | :---: | :--- |
| **Tidak bisa login (Pesan: *These credentials do not match our records*)** | 1. Salah ketik email/password.<br>2. Status akun dinonaktifkan (`is_active = false`).<br>3. Terkena proteksi rate limiting lockout (5x salah). | • Periksa huruf besar/kecil (Caps Lock).<br>• Tunggu 1–2 menit jika terkena lockout.<br>• Minta Super Admin memeriksa status keaktifan akun Anda di menu **Teams** dan lakukan reset password. |
| **Gambar yang baru diunggah tidak muncul di website** | 1. Tautan storage simbolik web belum terhubung.<br>2. Cache browser masih menyimpan versi lama. | • Lakukan *Hard Refresh* pada browser (Tekan `Ctrl + F5` atau `Cmd + Shift + R`).<br>• Jika di server lokal/baru dipindah hosting, hubungi tim IT untuk memastikan perintah `php artisan storage:link` sudah dijalankan. |
| **Gagal menghapus gambar di Media Library** | Gambar tersebut masih tertaut aktif pada artikel Blog, item Portofolio, Layanan, atau Testimoni. | • Sistem Brava melindungi website dari gambar rusak (*broken image*). Buka halaman konten yang disebutkan pada pesan error, ganti fotonya dengan gambar lain, lalu ulangi penghapusan di Media Library. |
| **Gagal menjadikan Promo sebagai Highlight** | Promo yang dipilih berstatus nonaktif atau tanggal kedaluwarsanya (`valid_until`) sudah lewat. | • Pastikan sakelar **Active** pada promo dalam kondisi menyala (hijau) dan perpanjang tanggal `valid_until` ke masa depan sebelum mencentang Highlight. |
| **Trafik Dashboard menampilkan label "Data Dummy"** | Kredensial GA4 Property ID atau Service Account JSON Key belum dimasukkan di Settings. | • Hubungi Super Admin untuk menempelkan (*paste*) JSON Service Account Google Cloud di menu **Settings → Technical Settings**. |
| **Artikel Blog tidak muncul di halaman depan website** | 1. Status artikel masih `Draft` atau `Archived`.<br>2. Tanggal `Published At` disetel ke tanggal masa depan. | • Buka menu **Blogs → Edit**, ubah status menjadi `Published`, dan pastikan tanggal publikasi adalah hari ini atau waktu yang telah lewat. |
| **Gagal membuat Blog / Portfolio dengan pesan "Slug has already been taken"** | Sudah ada postingan lama (atau postingan di Recycle Bin) yang memakai slug yang sama. | • Ubah sedikit teks slug (misal: tambah tahun atau kata pembeda: `panduan-bahan-drill-2026`).<br>• Atau minta Super Admin memeriksa dan mengosongkan data terkait di **Recycle Bin**. |

---

# 10. Checklist Sebelum Publish Konten

Gunakan daftar periksa (checklist) mandiri ini sebelum Anda menekan tombol simpan/publish artikel atau portofolio baru:

- [ ] **Judul (Title):** Jelas, menarik, mengandung kata kunci utama produk/layanan, dan tidak typo.
- [ ] **Struktur URL (Slug):** Bersih, menggunakan huruf kecil, kata dipisahkan strip (`-`), tanpa simbol aneh.
- [ ] **Kategori:** Sudah dicentang minimal 1 kategori yang paling relevan.
- [ ] **Kualitas Gambar Cover:** Foto tajam, pencahayaan baik, tidak pecah, dan proporsional.
- [ ] **Alt Text Gambar:** Sudah diisi deskripsi foto untuk pembaca tunanetra & Google Image.
- [ ] **Format Teks:** Menggunakan Heading terstruktur (H2, H3), paragraf tidak terlalu panjang, list poin rapi.
- [ ] **Spesifikasi & Fitur (Khusus Portofolio):** Rincian bahan kain, benang, sablon/bordir, dan keunggulan sudah terisi lengkap.
- [ ] **Meta Title SEO:** Panjang antara **50–60 karakter** (tidak melebihi 70 karakter).
- [ ] **Meta Description SEO:** Panjang antara **140–160 karakter**, merangkum isi, dan memuat kalimat ajakan.
- [ ] **OpenGraph Banner:** Thumbnail share sosial media berukuran proporsional (1200 x 630 px).
- [ ] **Pengaturan Index:** Sakelar *Allow Search Indexing (Robots Index)* dan *Robots Follow* dalam posisi **AKTIF**.
- [ ] **Status Publikasi:** Disetel ke `Published` dan tanggal publikasi sudah benar.

---

# 11. Ringkasan Praktik Terbaik (Do & Don't)

### ✅ YANG HARUS DILAKUKAN (DO)
1. **Lakukan Kompresi Awal Sebelum Upload:** Walaupun sistem sudah mengompresi gambar otomatis, usahakan mengunggah foto dengan ukuran master di bawah 2 MB agar proses upload cepat.
2. **Manfaatkan Fitur Dwi-Bahasa (ID & EN):** Isi tab English untuk memperluas jangkauan pembeli korporat multinasional dan institusi asing.
3. **Periksa Tampilan Share WhatsApp:** Sebelum menyebarkan tautan promo atau artikel baru ke grup klien, uji kirim tautan ke nomor pribadi untuk memastikan banner gambar dan teks ringkasan muncul sempurna.
4. **Lakukan Pembaruan Berkala Portofolio:** Publikasikan minimal 2–4 hasil produksi baru setiap bulan untuk membuktikan kepada calon klien bahwa konveksi Brava aktif dan terus dipercaya pelanggan.
5. **Gunakan Nomor WhatsApp Standar:** Selalu pastikan nomor WhatsApp kantor diisi lengkap dengan kode negara `628xxxxxxxx` tanpa spasi agar tombol pesanan tidak error.

---

### ❌ YANG TIDAK BOLEH DILAKUKAN (DON'T)
1. **DILARANG Menghapus Slug Konten Lama yang Sudah Populer:** Mengganti slug artikel lama akan membuat link yang tersimpan di Google atau bookmark klien menjadi rusak (*Error 404*).
2. **DILARANG Mematikan Sakelar Robots Index pada Konten Publik:** Mematikan opsi ini akan menghilangkan halaman dari Google secara permanen.
3. **JANGAN Menulis Judul dan Deskripsi yang Terlalu Panjang:** Teks yang melebihi batas karakter akan dipotong jelek oleh Google.
4. **JANGAN Membiarkan Alt Text Gambar Kosong:** Gambar tanpa alt text kehilangan 50% potensi trafik dari Google Image Search.
5. **JANGAN Menghapus Pengguna Tanpa Meninjau Artikelnya:** Re-assign atau tinjau terlebih dahulu artikel yang pernah ditulis staf sebelum menonaktifkan akun mereka.
6. **JANGAN Membagikan Password Akun CMS:** Setiap staf wajib memiliki akun pribadi dengan email masing-masing untuk menjaga akuntabilitas jejak audit di *Activity Logs*.

---

*Panduan ini disusun secara resmi untuk operasional tim Admin Brava CMS. Jika membutuhkan bantuan teknis lanjutan, silakan hubungi tim Administrator Sistem / Developer.*
