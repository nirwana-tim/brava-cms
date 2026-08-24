# Brava CMS & Compro - Comprehensive Guides Portal

Selamat datang di pusat dokumentasi dan panduan resmi untuk **Brava CMS** (Laravel Backend) dan **Brava Compro** (Next.js Frontend).

Seluruh modul dan konfigurasi sistem dirancang agar mudah dikelola oleh Administrator dan Developer.

---

## 📚 Daftar Panduan Lengkap

### 1. [Kamus & Panduan Lengkap Settings](SETTINGS_DICTIONARY.md)
> **Wajib dibaca bagi Admin / Webmaster.**
> Penjelasan mendalam untuk setiap field di menu **Settings** (`admin/settings`), status kewajiban (Wajib/Opsional), alasan penanganan otomatis oleh frontend, pengaruhnya ke website (Header, Footer, Meta Tag, OpenGraph, Schema.org), serta contoh isian yang benar.

### 2. [Panduan Google Search Console & Bing Webmaster Tools](GOOGLE_SEARCH_CONSOLE_AND_BING_GUIDE.md)
> Langkah-demi-langkah mendaftarkan website ke Google Search Console dan Bing Webmaster Tools (yang juga mengindeks ke DuckDuckGo, Yahoo, Copilot & ChatGPT Search), cara memasukkan token verifikasi, submit XML Sitemap (`/sitemap.xml`), dan mempercepat pengindeksan artikel.

### 3. [Panduan Monetisasi Google AdSense](GOOGLE_ADSENSE_SETUP_GUIDE.md)
> Panduan pendaftaran situs di AdSense, pembuatan file `ads.txt`, pembuatan Ad Unit responsif (Top & Bottom artikel blog), cara konfigurasi Client ID & Slot ID di Brava CMS, serta kepatuhan *Google Consent Mode v2*.

### 4. [Panduan Integrasi Google Analytics 4 (GA4)](GA4_SETUP_GUIDE.md)
> Panduan lengkap mengaktifkan grafik analitik live dan pengunjung harian di Dashboard Admin Brava CMS secara 100% gratis menggunakan Google Cloud Service Account dan Google Analytics Data API v1beta.

---

## 🛠️ Ringkasan Arsitektur Sistem

* **Backend CMS:** Laravel 12 (REST API, Caching, Media Management, Soft Deletes, Activity Logs)
* **Frontend Compro:** Next.js 15+ (App Router, Tailwind CSS v4, ISR, SEO Structured Data Schema.org, i18n Bilingual ID/EN)
* **API Endpoints:**
  * `GET /api/v1/settings` (Pengaturan umum, kontak, sosial, dan SEO publik)
  * `GET /api/v1/page-seo/{page}` (Metadata SEO per-halaman)
  * `GET /api/v1/blogs` & `GET /api/v1/blogs/{slug}` (Artikel blog & relasi kategori)
  * `GET /api/v1/portfolio` & `GET /api/v1/portfolio/{slug}` (Katalog karya & spesifikasi)
  * `GET /api/v1/promos` (Daftar promo & voucher diskon aktif)
  * `GET /api/v1/services` (Katalog layanan apparel)
  * `GET /api/v1/sitemap` (Daftar lengkap URL untuk sitemap XML)
