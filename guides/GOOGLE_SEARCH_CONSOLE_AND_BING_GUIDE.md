# Panduan Verifikasi Google Search Console & Bing Webmaster Tools

Dokumen ini berisi panduan langkah-demi-langkah (*step-by-step*) untuk mendaftarkan website Brava ke **Google Search Console** dan **Bing Webmaster Tools**, memverifikasi kepemilikan melalui Brava CMS, mengirimkan peta situs XML (*Sitemap*), dan memastikan artikel cepat terindeks.

---

## Daftar Isi
1. [Google Search Console (GSC)](#1-google-search-console-gsc)
   - [Langkah 1: Menambahkan Properti di GSC](#langkah-1-menambahkan-properti-di-gsc)
   - [Langkah 2: Mengambil Kode HTML Tag](#langkah-2-mengambil-kode-html-tag)
   - [Langkah 3: Memasukkan Kode ke Brava CMS](#langkah-3-memasukkan-kode-ke-brava-cms)
   - [Langkah 4: Konfirmasi Verifikasi](#langkah-4-konfirmasi-verifikasi)
   - [Langkah 5: Submit Sitemap XML](#langkah-5-submit-sitemap-xml)
2. [Bing Webmaster Tools & AI Search](#2-bing-webmaster-tools--ai-search)
   - [Langkah 1: Login & Tambah Situs di Bing Webmaster](#langkah-1-login--tambah-situs-di-bing-webmaster)
   - [Langkah 2: Ambil Kode Verifikasi Bing](#langkah-2-ambil-kode-verifikasi-bing)
   - [Langkah 3: Masukkan ke Brava CMS & Verifikasi](#langkah-3-masukkan-ke-brava-cms--verifikasi)
3. [Menggunakan Custom Webmaster Tags (Pinterest, Yandex, Baidu, FB)](#3-menggunakan-custom-webmaster-tags)
4. [Tanya Jawab & Troubleshooting Indexing](#4-tanya-jawab--troubleshooting-indexing)

---

## 1. Google Search Console (GSC)

Google Search Console adalah alat resmi gratis dari Google untuk memantau performa kata kunci pencarian, status indeks halaman, dan masalah perayapan (*crawling*).

### Langkah 1: Menambahkan Properti di GSC
1. Buka [Google Search Console](https://search.google.com/search-console).
2. Login menggunakan akun Google / Gmail Anda.
3. Pada dialog *Select property type*, pilih opsi kanan: **URL prefix** (Awalan URL).
4. Masukkan URL lengkap website Anda beserta `https://` (contoh: `https://brava.id`).
5. Klik **Continue**.

---

### Langkah 2: Mengambil Kode HTML Tag
1. Pada jendela *Verify ownership*, gulir ke bawah ke bagian **Other verification methods** (Metode verifikasi lainnya).
2. Klik opsi **HTML tag**.
3. Google akan menampilkan kode meta seperti berikut:
   ```html
   <meta name="google-site-verification" content="abcdef12345_XYZ9876543210" />
   ```
4. **Salin hanya isi nilai di dalam tanda kutip `content="..."`** (contoh: `abcdef12345_XYZ9876543210`).

---

### Langkah 3: Memasukkan Kode ke Brava CMS
1. Buka Dasbor Admin **Brava CMS** (`https://domain-anda.com/admin`).
2. Masuk ke menu **Settings** pada sidebar.
3. Gulir ke grup **SEO Settings**.
4. Cari field **Google Search Console Verification** (`google_verification`).
5. Tempelkan kode token yang disalin tadi ke kolom tersebut.
6. Klik **Save Settings**.

---

### Langkah 4: Konfirmasi Verifikasi
1. Kembali ke tab halaman Google Search Console.
2. Klik tombol hijau **Verify** pada metode HTML tag.
3. Google akan memeriksa `<head>` website Anda. Jika sukses, muncul pop-up *"Ownership verified"*.
4. Klik **Go to Property**.

---

### Langkah 5: Submit Sitemap XML
1. Di menu sidebar kiri Google Search Console, klik **Sitemaps** (Peta Situs).
2. Pada form *Add a new sitemap*, masukkan:
   ```
   sitemap.xml
   ```
3. Klik **Submit**.
4. Google akan membaca seluruh URL halaman statis, layanan, portofolio, artikel blog, dan promo dari website Brava secara otomatis.

---

## 2. Bing Webmaster Tools & AI Search

> [!NOTE]
> Indeks dari Bing digunakan oleh mesin pencari **Microsoft Bing, Yahoo Search, DuckDuckGo, Ecosia, Microsoft Copilot, dan fitur ChatGPT Search**.

### Langkah 1: Login & Tambah Situs di Bing Webmaster
1. Buka [Bing Webmaster Tools](https://www.bing.com/webmasters).
2. Login menggunakan akun Microsoft atau akun Google Anda.
3. Anda memiliki 2 opsi penambahan situs:
   - **Opsi Cepat (Import from GSC):** Klik *Import* dari Google Search Console (otomatis terverifikasi).
   - **Opsi Manual:** Masukkan URL website Anda di kolom *Add your site manually* → Klik **Add**.

---

### Langkah 2: Ambil Kode Verifikasi Bing
1. Jika memilih metode manual, pilih metode **HTML Meta Tag**.
2. Bing akan menampilkan kode meta tag seperti berikut:
   ```html
   <meta name="msvalidate.01" content="A1B2C3D4E5F60718293A4B5C6D7E8F90" />
   ```
3. Salin kode token di dalam `content="..."` (contoh: `A1B2C3D4E5F60718293A4B5C6D7E8F90`).

---

### Langkah 3: Masukkan ke Brava CMS & Verifikasi
1. Buka Dasbor Admin **Brava CMS** → Menu **Settings** → Grup **SEO Settings**.
2. Masukkan kode ke field **Bing Webmaster Tools Verification** (`bing_verification`).
3. Klik **Save Settings**.
4. Kembali ke halaman Bing Webmaster Tools → Klik tombol **Verify**.
5. Setelah terverifikasi, masuk ke menu **Sitemaps** di Bing Webmaster → Submit URL sitemap: `https://domain-anda.com/sitemap.xml`.

---

## 3. Menggunakan Custom Webmaster Tags

Jika Anda ingin memverifikasi kepemilikan website di platform lain:

1. Buka **Brava CMS** → Menu **Settings** → Grup **SEO Settings**.
2. Gunakan field **Custom Webmaster Tags** (`custom_webmaster_tags`).
3. Tempelkan tag `<meta>` lengkap dari platform yang bersangkutan.

### Contoh Format yang Didukung:
```html
<!-- Pinterest Business Verification -->
<meta name="p:domain_verify" content="9876543210abcdef" />

<!-- Facebook / Meta Domain Verification -->
<meta name="facebook-domain-verification" content="f82938472910abcdef" />

<!-- Yandex Webmaster Verification -->
<meta name="yandex-verification" content="123456abcdef7890" />

<!-- Baidu Webmaster Verification -->
<meta name="baidu-site-verification" content="code-xyz987" />
```
Klik **Save Settings**. Frontend Brava Compro akan otomatis mencetak meta tag tersebut di bagian `<head>` halaman.

---

## 4. Tanya Jawab & Troubleshooting Indexing

### 1. Berapa lama Google mengindeks artikel blog atau portofolio baru?
* Biasanya membutuhkan waktu antara **1 hari hingga 1 minggu**.
* **Cara Mempercepat (Inspeksi URL):** Buka Google Search Console → Tempelkan URL artikel baru pada kolom pencarian atas *Inspect any URL* → Tekan Enter → Klik tombol **Request Indexing**.

### 2. Mengapa verifikasi HTML tag gagal?
* Pastikan Anda hanya memasukkan **kode token string** ke field `google_verification` atau `bing_verification` (jangan memasukkan `<meta name=...>` lengkap di field input text tersebut).
* Jika baru saja menekan Save Settings di CMS, jalankan `php artisan cache:clear` di server jika cache API belum diperbarui.
* Pastikan website Anda dapat diakses secara publik dan tidak dilindungi *HTTP Basic Auth* / *Maintenance Mode*.
