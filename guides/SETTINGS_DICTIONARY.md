# Kamus & Panduan Lengkap Settings Brava CMS

Dokumen ini berisi referensi lengkap untuk setiap field pengaturan di menu **Settings** (`admin/settings`) pada **Brava CMS**.

Setiap field dijelaskan secara mendalam mengenai:
- **Status Kewajiban (Wajib / Opsional)** beserta alasan penanganan otomatis oleh frontend (`brava-compro`).
- **Fungsi & Pengaruhnya** (ke mana data tersebut mengalir: Header, Footer, Meta Tag `<head>`, Hasil Pencarian Google/SERP, OpenGraph Medsos, Dashboard Analitik, dll).
- **Format & Contoh Isian** yang valid.

---

## Daftar Isi

1. [Grup: General (Identitas Situs)](#1-grup-general-identitas-situs)
2. [Grup: Contact (Informasi Kontak & CS)](#2-grup-contact-informasi-kontak--cs)
3. [Grup: Social Media (Tautan Akun Resmi)](#3-grup-social-media-tautan-akun-resmi)
4. [Grup: SEO & Webmaster Verification (Mesin Pencari & Metadata)](#4-grup-seo--webmaster-verification-mesin-pencari--metadata)
5. [Grup: AdSense (Monetisasi Iklan)](#5-grup-adsense-monetisasi-iklan)
6. [Grup: System & Analytics (Integrasi GA4 Dashboard)](#6-grup-system--analytics-integrasi-ga4-dashboard)
7. [Matriks Ringkasan Cepat](#7-matriks-ringkasan-cepat)

---

## 1. Grup: General (Identitas Situs)

Pengaturan dasar nama dan deskripsi brand bisnis Anda.

### 1.1. `site_name` (Site Name)
* **Tipe Data:** Teks Translatable (`id`, `en`)
* **Wajib Diisi?** **Sangat Disarankan (Wajib)**
  * *Alasan:* Meskipun frontend memiliki fallback default `"Brava"`, nama brand Anda harus konsisten di seluruh web.
* **Pengaruh ke Website:**
  1. Title bar browser dan root title template: `%s | [site_name]`.
  2. Teks header brand & footer copyright.
  3. Schema.org JSON-LD `Organization` (nama entitas bisnis).
  4. OpenGraph metadata `og:site_name`.
* **Contoh Isian:**
  * **Indonesia (id):** `BRAVA Apparel & Konveksi`
  * **English (en):** `BRAVA Custom Apparel & Manufacturing`

---

### 1.2. `site_description` (Site Description)
* **Tipe Data:** Textarea Translatable (`id`, `en`)
* **Wajib Diisi?** **Opsional**
  * *Alasan:* Jika dikosongkan, frontend akan fallback ke deskripsi standar `"BRAVA — Strong Silent Unshakeable."`.
* **Pengaruh ke Website:**
  1. Default root meta description untuk Google jika halaman tidak memiliki meta description khusus.
  2. Fallback deskripsi OpenGraph di media sosial.
  3. Deskripsi entitas bisnis pada Schema.org `Organization`.
* **Contoh Isian:**
  * **Indonesia (id):** `Pabrik konveksi dan produsen apparel seragam kerja, rompi, polo shirt, dan jersey custom berkualitas tinggi di Indonesia.`
  * **English (en):** `Premium custom uniform, vest, polo shirt, and sportswear garment manufacturer based in Indonesia.`

---

## 2. Grup: Contact (Informasi Kontak & CS)

Informasi kontak resmi yang menghubungkan calon pelanggan dengan tim sales / customer service.

### 2.1. `whatsapp_number` (WhatsApp Number)
* **Tipe Data:** Teks Angka
* **Wajib Diisi?** **Sangat Disarankan (Wajib)**
  * *Alasan:* Tombol floating chat WhatsApp dan tombol CTA klaim promo/order portofolio mengarah ke nomor ini. Jika kosong, fallback ke nomor demo sistem.
* **Pengaruh ke Website:**
  1. Link tombol floating WhatsApp di pojok layar frontend.
  2. Link tombol *"Konsultasi Kebutuhanmu"* di detail portofolio.
  3. Link tombol *"Klaim Promo via WhatsApp"* di detail promo.
* **Format & Aturan:**
  * Wajib diawali kode negara tanpa tanda `+` dan tanpa spasi/strip (contoh: `62` untuk Indonesia).
* **Contoh Isian:** `6281234567890`

---

### 2.2. `phone` (Phone)
* **Tipe Data:** Teks Bebas
* **Wajib Diisi?** **Opsional**
* **Pengaruh ke Website:**
  1. Ditampilkan di Footer website dan halaman Contact Us (`/contact`).
  2. Disematkan pada tag `telephone` di Schema.org `Organization`.
* **Contoh Isian:** `+62 812-3456-7890` atau `(021) 555-0199`

---

### 2.3. `email` (Email)
* **Tipe Data:** Teks Email
* **Wajib Diisi?** **Sangat Disarankan**
* **Pengaruh ke Website:**
  1. Ditampilkan di Footer dan halaman Contact Us.
  2. Link `mailto:` untuk klien yang ingin mengirim email resmi / RFP / penawaran harga vendor.
  3. Disematkan pada tag `email` Schema.org `Organization`.
* **Contoh Isian:** `hello@brava.id` atau `sales@brava.id`

---

### 2.4. `address` (Address / Contact Address)
* **Tipe Data:** Textarea
* **Wajib Diisi?** **Opsional**
* **Pengaruh ke Website:**
  1. Ditampilkan pada Footer website dan section peta di halaman `/contact`.
  2. Disematkan pada properti `address` Schema.org `Organization` untuk kredibilitas SEO lokal.
* **Contoh Isian:**
  `Jl. Raya Telagasari - Kosambi No. 45, Karawang Timur, Jawa Barat 41361, Indonesia`

---

## 3. Grup: Social Media (Tautan Akun Resmi)

Tautan profil media sosial resmi perusahaan.

| Field Key | Label | Wajib? | Pengaruh & Penanganan Frontend | Contoh Isian |
| :--- | :--- | :---: | :--- | :--- |
| `facebook_url` | Facebook URL | **Tidak** | Tampil di icon medsos footer. **Jika kosong**, icon Facebook otomatis disembunyikan. | `https://facebook.com/bravacms` |
| `instagram_url` | Instagram URL | **Tidak** | Tampil di icon medsos footer. **Jika kosong**, icon Instagram otomatis disembunyikan. | `https://instagram.com/bravacms` |
| `youtube_url` | YouTube URL | **Tidak** | Tampil di icon medsos footer. **Jika kosong**, icon YouTube otomatis disembunyikan. | `https://youtube.com/@bravacms` |
| `tiktok_url` | TikTok URL | **Tidak** | Tampil di icon medsos footer. **Jika kosong**, icon TikTok otomatis disembunyikan. | `https://tiktok.com/@bravacms` |
| `x_url` | X (Twitter) URL | **Tidak** | Tampil di icon medsos footer. **Jika kosong**, icon X otomatis disembunyikan. | `https://x.com/bravacms` |
| `linkedin_url` | LinkedIn URL | **Tidak** | Tampil di icon medsos footer. **Jika kosong**, icon LinkedIn otomatis disembunyikan. | `https://linkedin.com/company/brava` |

> [!TIP]
> Frontend `brava-compro` dirancang cerdas: Anda tidak perlu khawatir footer tampak berlubang jika hanya memiliki Instagram dan Facebook. Icon medsos yang URL-nya kosong tidak akan dirender.

---

## 4. Grup: SEO & Webmaster Verification (Mesin Pencari & Metadata)

Pengaturan verifikasi kepemilikan domain dan pelacakan mesin pencari.

### 4.1. `google_verification` (Google Search Console Verification)
* **Tipe Data:** Teks
* **Wajib Diisi?** **Sangat Disarankan (Untuk SEO Google)**
* **Pengaruh ke Website:**
  * Frontend akan menyisipkan tag berikut di `<head>`:
    ```html
    <meta name="google-site-verification" content="isi_token_anda" />
    ```
  * Digunakan saat memverifikasi kepemilikan domain di **Google Search Console (GSC)** dengan metode *HTML Tag*.
* **Contoh Isian:** `abc12345XYZ_GoogleVerificationToken99` (Hanya masukkan string kode tokennya, bukan seluruh tag `<meta>`).

---

### 4.2. `bing_verification` (Bing Webmaster Tools Verification)
* **Tipe Data:** Teks
* **Wajib Diisi?** **Opsional (Disarankan untuk Index Bing & AI Search)**
* **Pengaruh ke Website:**
  * Frontend akan menyisipkan tag berikut di `<head>`:
    ```html
    <meta name="msvalidate.01" content="isi_token_anda" />
    ```
  * Digunakan untuk verifikasi di **Bing Webmaster Tools**. Indeks Bing ini juga digunakan oleh **DuckDuckGo, Yahoo, Microsoft Copilot, dan ChatGPT Search**.
* **Contoh Isian:** `A1B2C3D4E5F60718293A4B5C6D7E8F90`

---

### 4.3. `custom_webmaster_tags` (Custom Webmaster Tags)
* **Tipe Data:** Textarea
* **Wajib Diisi?** **Tidak Wajib (Opsional)**
* **Pengaruh ke Website:**
  * Frontend secara otomatis mem-parsing dan mencetak tag `<meta>` kustom ke dalam `<head>` website.
  * Tempat menempel tag verifikasi dari platform lain yang belum ada field khususnya, seperti:
    - *Pinterest Domain Verify* (`<meta name="p:domain_verify" content="..." />`)
    - *Baidu Search Verification* (`<meta name="baidu-site-verification" content="..." />`)
    - *Yandex Webmaster* (`<meta name="yandex-verification" content="..." />`)
    - *Norton SafeWeb* (`<meta name="norton-safeweb-site-verification" content="..." />`)
    - *Facebook Domain Verification* (`<meta name="facebook-domain-verification" content="..." />`)
* **Contoh Isian:**
  ```html
  <meta name="p:domain_verify" content="9876543210abcdef" />
  <meta name="facebook-domain-verification" content="f82938472910" />
  ```

---

### 4.4. `organization_schema` (Organization Schema JSON-LD)
* **Tipe Data:** Textarea (JSON String)
* **Wajib Diisi?** **TIDAK WAJIB (Boleh Dikosongkan)**
* **Pengaruh ke Website:**
  * **Jika Dikosongkan (Default):** Frontend **sudah otomatis** membuat skema JSON-LD `Organization` standar dari data `site_name`, `logo`, `address`, `phone`, dan `email`.
  * **Jika Diisi:** Kode JSON kustom ini akan menimpa (*override*) skema otomatis, cocok bila Anda butuh tipe schema spesifik seperti `LocalBusiness`, `openingHours`, koordinat `geo`, atau daftar `sameAs` akun sosmed lengkap.
* **Contoh Isian (Jika ingin custom):**
  ```json
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "BRAVA Konveksi & Apparel",
    "url": "https://brava.id",
    "logo": "https://brava.id/brava-default.png",
    "telephone": "+6281234567890",
    "email": "hello@brava.id",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Jl. Raya Telagasari No. 123",
      "addressLocality": "Karawang",
      "addressRegion": "Jawa Barat",
      "postalCode": "41361",
      "addressCountry": "ID"
    },
    "sameAs": [
      "https://facebook.com/bravacms",
      "https://instagram.com/bravacms"
    ]
  }
  ```

---

### 4.5. `google_analytics_id` (Google Analytics ID - Measurement ID)
* **Tipe Data:** Teks
* **Wajib Diisi?** **Opsional (Wajib jika ingin tracking GA4 di frontend)**
* **Pengaruh ke Website:**
  1. Frontend otomatis memuat script pelacak Google Tag Manager (`gtag.js?id=G-XXXXXXXXXX`).
  2. Mengaktifkan sistem **Google Consent Mode v2** yang terintegrasi dengan Cookie Banner frontend (pelacakan hanya berjalan jika pengunjung menyetujui cookie analitik).
* **Format & Aturan:**
  * Format wajib diawali huruf `G-` diikuti huruf & angka (misal: `G-XXXXXXXXXX`).
* **Contoh Isian:** `G-ABCD1234EF`

---

## 5. Grup: AdSense (Monetisasi Iklan)

Pengaturan penayangan banner Google AdSense di frontend compro.

### 5.1. `adsense_enabled` (AdSense Enabled)
* **Tipe Data:** Toggle Switch (Boolean)
* **Wajib Diisi?** **Ya (Default: Off/False)**
* **Pengaruh ke Website:**
  * Mengaktifkan/menonaktifkan seluruh slot iklan AdSense di website secara global dengan satu sakelar.
  * Jika bernilai `false`, frontend tidak akan memuat script AdSense sama sekali sehingga loading halaman tetap maksimal.

---

### 5.2. `adsense_client_id` (AdSense Publisher ID)
* **Tipe Data:** Teks
* **Wajib Diisi?** **Wajib jika `adsense_enabled` bernilai True**
* **Pengaruh ke Website:**
  * Dimasukkan ke loader script `<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-..." ... />`.
* **Format & Aturan:**
  * Wajib diawali `ca-pub-` diikuti minimal 10 digit angka.
* **Contoh Isian:** `ca-pub-1234567890123456`

---

### 5.3. `adsense_slot_1` & `adsense_slot_2` (AdSlot ID)
* **Tipe Data:** Teks Numerik
* **Wajib Diisi?** **Wajib jika ingin unit iklan tertentu tayang**
* **Pengaruh ke Website:**
  * `adsense_slot_1`: Slot iklan di **bagian atas artikel blog** (di bawah judul & author).
  * `adsense_slot_2`: Slot iklan di **bagian bawah artikel blog** (setelah konten selesai).
* **Format & Aturan:**
  * Angka ID unit iklan dari dashboard Google AdSense. Slot 1 dan Slot 2 harus memiliki ID berbeda.
* **Contoh Isian:** `9876543210`

---

## 6. Grup: System & Analytics (Integrasi GA4 Dashboard)

> [!IMPORTANT]
> Pengaturan pada grup `system` hanya dapat dilihat dan diedit oleh pengguna dengan role **Super Admin**. Nilai pada grup ini **TIDAK PERNAH dikirim ke API publik frontend** demi keamanan kredensial Google Cloud server Anda.

### 6.1. `ga4_property_id` (GA4 Numeric Property ID)
* **Tipe Data:** Angka (Numeric)
* **Wajib Diisi?** **Wajib jika ingin grafik dashboard CMS membaca data riil Google Analytics**
  * *Catatan:* Jika dikosongkan, dashboard admin akan berjalan dalam **Mode Simulasi/Dummy Dinamis** yang aman untuk demo.
* **Pengaruh ke CMS:**
  * Digunakan oleh backend Laravel untuk memanggil Google Analytics Data API v1beta.
* **Format & Aturan:**
  * Merupakan angka 9 digit Property ID dari menu *GA4 Admin → Property Details* (bukan Measurement ID `G-XXX`).
* **Contoh Isian:** `482910482`

---

### 6.2. `ga4_service_account_key` (GA4 Service Account Key JSON)
* **Tipe Data:** Textarea (Full JSON Payload)
* **Wajib Diisi?** **Wajib jika `ga4_property_id` diisi**
* **Pengaruh ke CMS:**
  * Kunci otentikasi Service Account Google Cloud untuk mengambil laporan pengunjung, top pages, traffic source, dan live visitors secara otomatis.
* **Format & Aturan:**
  * Salin seluruh isi file `.json` service account yang diunduh dari Google Cloud IAM.
* **Contoh Isian:**
  ```json
  {
    "type": "service_account",
    "project_id": "brava-cms-analytics",
    "private_key_id": "c1a2b3...",
    "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgk...",
    "client_email": "ga4-reader@brava-cms-analytics.iam.gserviceaccount.com",
    "client_id": "102938475610293847561",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token"
  }
  ```

---

## 7. Matriks Ringkasan Cepat

| Key Pengaturan | Grup | Level Hak Akses | Status Wajib | Diatur Otomatis Frontend jika Kosong? |
| :--- | :---: | :---: | :---: | :---: |
| `site_name` | `general` | Admin & Super Admin | **Disarankan** | Fallback ke `"Brava"` |
| `site_description` | `general` | Admin & Super Admin | Opsional | Fallback ke tagline default |
| `whatsapp_number` | `contact` | Admin & Super Admin | **Disarankan** | Fallback ke no demo |
| `phone` | `contact` | Admin & Super Admin | Opsional | Disembunyikan jika kosong |
| `email` | `contact` | Admin & Super Admin | **Disarankan** | Disembunyikan jika kosong |
| `address` | `contact` | Admin & Super Admin | Opsional | Disembunyikan jika kosong |
| `facebook_url` s/d `linkedin_url` | `social` | Admin & Super Admin | Opsional | Icon otomatis disembunyikan |
| `google_verification` | `seo` | Admin & Super Admin | **Disarankan** | Tidak merender tag |
| `bing_verification` | `seo` | Admin & Super Admin | Opsional | Tidak merender tag |
| `custom_webmaster_tags` | `seo` | Admin & Super Admin | Opsional | Tidak merender tag |
| `organization_schema` | `seo` | Admin & Super Admin | **Tidak Wajib** | **Auto Generate Schema Bawaan** |
| `google_analytics_id` | `seo` | Admin & Super Admin | Opsional | Script tidak dimuat |
| `adsense_enabled` | `adsense` | Super Admin | Opsional | Default False |
| `adsense_client_id` | `adsense` | Super Admin | Opsional | Script tidak dimuat jika False |
| `adsense_slot_1` & `_2` | `adsense` | Super Admin | Opsional | Slot kosong jika tidak diisi |
| `ga4_property_id` | `system` | Super Admin Saja | Opsional | Dashboard masuk Mode Dummy |
| `ga4_service_account_key` | `system` | Super Admin Saja | Opsional | Dashboard masuk Mode Dummy |
