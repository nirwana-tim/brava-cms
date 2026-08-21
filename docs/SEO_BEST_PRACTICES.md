# Panduan & Praktik Terbaik SEO (SEO Best Practices) - Brava CMS

Dokumen ini berisi panduan resmi bagi pengelola konten (Admin/Staff) dalam mengoptimalkan Search Engine Optimization (SEO) untuk setiap modul di Brava CMS (Blog, Portfolio, dan Promo). 

Brava CMS dirancang dengan arsitektur **Universal SEO**, yang tidak hanya mengoptimalkan peringkat di Google Search, tetapi juga mesin pencari lain (Bing, Yandex, Yahoo), media sosial (WhatsApp, Facebook, X, LinkedIn, Telegram), hingga **AI Search & LLM Crawlers** (Perplexity, ChatGPT Search, Bing Chat/Copilot).

---

## 1. Panduan Pengisian Field SEO di Admin Panel

Setiap form pembuatan atau perbaikan konten (Blog, Portfolio, dan Promo) dilengkapi dengan bagian **SEO Settings**. Berikut adalah panduan dan rekomendasi pengisian setiap kolom:

### A. Meta Title
- **Fungsi Utama**: Menentukan judul halaman di tab browser, hasil pencarian mesin pencari (SERP), dan judul utama saat tautan dibagikan ke media sosial / pesan instan (OG Title).
- **Rekomendasi Panjang**: **50–60 karakter** (atau sekitar 580 piksel di layar desktop Google).
- **Tips Optimalisasi**:
  - Tempatkan kata kunci utama (target keyword) di bagian depan judul.
  - Tambahkan *brand name* di akhir jika memungkinkan, misal: `Konveksi Seragam Kerja Premium | Brava`.
  - Buat judul yang memicu rasa ingin tahu atau memberikan benefit jelas untuk meningkatkan klik (CTR - *Click-Through Rate*).
- **Sistem Otomatis (Fallback)**: Jika dikosongkan, sistem Brava CMS akan secara otomatis menggunakan **Judul Utama Konten (`title`)**.

---

### B. Meta Description
- **Fungsi Utama**: Memberikan ringkasan isi halaman yang tampil di bawah judul pada hasil pencarian dan *preview* media sosial (OG Description).
- **Rekomendasi Panjang**: **150–160 karakter** (termasuk spasi).
- **Tips Optimalisasi**:
  - Tulis kalimat ringkas yang merangkum isi konten secara akurat.
  - Gunakan *Call-to-Action (CTA)* yang natural, misal: *"Temukan koleksi seragam kerja custom terbaik dengan bahan awet. Konsultasi & penawaran harga sekarang!"*.
  - Hindari menjiplak deskripsi yang persis sama antar halaman agar tidak dianggap duplikat oleh mesin pencari.
- **Sistem Otomatis (Fallback)**:
  - Pada **Blog**: Jika dikosongkan, otomatis mengambil dari kolom `excerpt` (atau cuplikan awal `content`).
  - Pada **Portfolio & Promo**: Jika dikosongkan, otomatis mengambil dari `description` atau 160 karakter pertama konten bersih tanpa tag HTML.

---

### C. OG Image & OG Image Alt (Open Graph)
- **Fungsi Utama**: Menentukan gambar ilustrasi dan alt text saat halaman dibagikan ke WhatsApp, Facebook, LinkedIn, X, atau platform chat lainnya.
- **Rekomendasi Ukuran**: **1200 x 630 piksel** (aspek rasio **1.91:1**).
- **Tips Optimalisasi Gambar & Alt Text**:
  - Gunakan gambar beresolusi tinggi dengan kontras yang baik.
  - Jika terdapat teks pada gambar, posisikan di area tengah (*safe zone*) agar tidak terpotong pada tampilan ponsel.
  - Isi kolom **OG Image Alt** dengan deskripsi gambar yang singkat dan jelas (misal: `Foto tim kerja mengenakan seragam Brava di lapangan`). Alt text sangat penting untuk **aksesibilitas (screen reader)** serta pencarian gambar di Google Images & Pinterest.
- **Sistem Otomatis (Fallback)**: Jika dikosongkan, sistem otomatis mengambil gambar utama konten (`featured_image`, `photo`, atau `image`) dan nama judul sebagai alternatif teksnya.

---

### D. Robots Index & Robots Follow
- **Robots Index (`true/false`)**:
  - **Aktif (`true` / Default)**: Mengizinkan mesin pencari (Google, Bing, dll.) untuk mengindeks halaman ini agar tampil di hasil pencarian.
  - **Non-aktif (`false` / `noindex`)**: Gunakan hanya untuk halaman yang sifatnya sementara, draft, duplikat, atau halaman promosi internal yang tidak ingin ditemukan publik melalui mesin pencari.
- **Robots Follow (`true/false`)**:
  - **Aktif (`true` / Default)**: Mengizinkan bot mesin pencari menelusuri (*crawl*) seluruh tautan yang ada di halaman tersebut.
  - **Non-aktif (`false` / `nofollow`)**: Memerintahkan bot untuk tidak mengikuti tautan keluar dari halaman tersebut.

---

### E. Schema Type (Structured Data / JSON-LD)
- **Fungsi Utama**: Menentukan standar tipe data terstruktur schema.org agar mesin pencari memahami konteks halaman dan dapat menampilkan **Rich Snippets** (tampilan interaktif/terstruktur di SERP).
- **Rekomendasi per Modul**:
  - **Blog**: Gunakan `Article` atau `BlogPosting`.
  - **Portfolio**: Gunakan `CreativeWork`.
  - **Promo**: Sistem secara otomatis mengalokasikan tipe `SpecialAnnouncement`.

---

## 2. Arsitektur SEO Otomatis Brava CMS

Brava CMS dilengkapi fitur otomatis untuk meminimalkan beban kerja Admin sekaligus menjamin kesehatan teknis SEO:

1. **Intelligent Fallbacks**: Admin tidak diwajibkan mengisi seluruh kolom SEO satu per satu. Jika dikosongkan, sistem memanfaatkan data utama konten secara pintar tanpa mengurangi kualitas meta tag.
2. **Canonical URLs**: Sistem membuat tag `<link rel="canonical" href="..." />` secara dinamis pada setiap Resource API untuk mencegah penalti konten duplikat (*duplicate content penalty*) akibat parameter URL (seperti `?utm_source=` atau `?page=`).
3. **Sitemap Otomatis (`/sitemap.xml`)**: Sistem mempublikasikan daftar seluruh URL aktif dari modul Blog, Portfolio, Service, FAQ, dan Halaman Utama yang diubah secara *real-time* ke format XML yang memenuhi standar protokol sitemap internasional.
4. **Kompatibilitas Robot Crawler (`/robots.txt`)**: Konfigurasi standar untuk mengizinkan agen peramban publik dan mengarahkan crawler langsung ke `sitemap.xml`.

---

## 3. Strategi Universal SEO (Beyond Google)

Mengapa SEO di Brava CMS efektif untuk berbagai peramban dan bot modern?

- **Mesin Pencari Non-Google (Bing, Yandex, Yahoo, DuckDuckGo)**: Sangat bergantung pada struktur HTML yang bersih, konsistensi `title`/`meta description`, serta keberadaan sitemap XML yang valid.
- **Social Media Crawlers (WhatsApp, Facebook, LinkedIn, X Bot)**: Mengutamakan tag standar **Open Graph (`og:title`, `og:description`, `og:image`)** serta **Twitter Card (`twitter:card`, `twitter:image`)** yang dihasilkan dari data SEO di panel admin.
- **AI Crawlers / LLM Search Engine (Perplexity AI, ChatGPT Search, Bing Copilot)**: Bot AI mencari jawaban berbasis informasi terstruktur. Kombinasi **Schema.org JSON-LD**, heading yang rapi (`<h1>`, `<h2>`), dan deskripsi meta yang padat fakta membantu AI mengenali merek Brava sebagai sumber otoritatif.
