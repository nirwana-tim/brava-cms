# Rencana Fitur Bilingual Indonesia–Inggris (ID–EN)

> [!IMPORTANT]
> **Status: IMPLEMENTED / DONE (Selesai & Terverifikasi).**
> Fitur i18n bilingual ID-EN dengan fallback ke `id` dan dual-slug per locale telah **selesai dieksekusi & teruji 100%** di backend Laravel CMS. Untuk petunjuk konsumsi sisi frontend Next.js, lihat `C:\laragon\www\brava-compro\docs\I18N_BILINGUAL_PLAN.md`.

---

## 1. Ringkasan

Frontend company profile (Next.js) akan memiliki **switch bahasa Indonesia–Inggris**:

- Backend Laravel headless ini menjadi **single source of truth** untuk konten bilingual.
- Konten diinput admin **manual per tab ID / EN** di CMS — **TANPA** bantuan auto-translate (DeepL/Google).
- Jika field bahasa Inggris kosong, frontend menampilkan **fallback ke bahasa Indonesia** (konten tidak pernah kosong).
- Tiap bahasa punya **URL terpisah** untuk SEO (`/id/...` dan `/en/...`).

## 2. Keputusan Arsitektur (Sudah Disepakati)

| # | Keputusan |
|---|---|
| 1 | Bahasa: `id` (default/fallback) dan `en` |
| 2 | URL terpisah per bahasa (`[locale]` segment) + hreflang |
| 3 | Pakai `spatie/laravel-translatable` → kolom konten menjadi JSON `{"id": "...", "en": "..."}` |
| 4 | API terima **query param** `?lang=en` (bukan header `Accept-Language`) agar aman di-cache CDN/ISR |
| 5 | Fallback otomatis ke `id` saat field `en` kosong |
| 6 | Admin mengisi konten **manual per tab ID/EN** (tanpa DeepL/Google) |
| 7 | Slug **per-bahasa**, auto-generate dari title per tab, tetap **editable**; validasi unik manual per-locale |
| 8 | Semua tipe konten ikut: services, blogs, categories, portfolio_items, testimonials, faqs, team_members, promos, settings + field SEO |

## 3. Struktur URL (Hasil Akhir)

```
brava.id/id/blogs/beberapa-tips-konveksi     → bahasa Indonesia
brava.id/en/blogs/convection-tips            → bahasa Inggris
brava.id/                                    → redirect ke /id (via middleware Next.js)
```

Contoh input admin per tab:

| Tab ID | Tab EN |
|---|---|
| Title: `Beberapa Tips Konveksi` | Title: `Convection Tips` |
| Slug: `beberapa-tips-konveksi` (auto, editable) | Slug: `convection-tips` (auto, editable) |

## 4. Perubahan Backend (Laravel)

### 4.1 Fondasi
- Sudah terpasang `spatie/laravel-translatable`. **Tidak memakai `spatie/laravel-sluggable`** — slug translatable dihandle manual via atribut `slug` berisi JSON `{"id","en"}`.
- Supported locales `['id', 'en']`, default `id`.
- Middleware/controller API membaca `?lang=en` → `App::setLocale()`. Locale tidak valid → default `id`.

### 4.2 Migrasi Database
- Ubah kolom teks + SEO menjadi JSON di tabel: `services`, `blogs`, `categories`, `portfolio_items`, `testimonials`, `faqs`, `team_members`, `promos`.
- Field translatable per tipe (contoh umum): `title`, `slug`, `excerpt`, `content`, `description`, `question`, `answer`, `client`, `position`, `badge_text`, `discount_info`, `wa_template`, `client_name`, `name` (kategori/team), `meta_title`, `meta_description`, `meta_keywords`, `photo_alt`/`image_alt`. **Khusus `portfolio_items`: kolom `content` sudah dihapus — yang ditranslasi adalah `specifications` (array {key, value}) dan `features` (array string).**
- Kolom `slug`: ubah ke JSON + **drop unique constraint** (validasi unik dipindah ke app layer via `Rule::unique(..., 'slug->id')` / `slug->en`).
- **Backfill**: data lama dibungkus jadi `{"id": "<value>", "en": null}` → konten Indonesia yang sudah ada tetap tampil via fallback.
- **WAJIB backup database sebelum migrasi** (jangan di production tanpa testing).

### 4.3 Model
- Tambah trait `HasTranslations` + array `$translatable` di 8 model.
- Adaptasi accessor khusus (contoh: `Blog::getContentAttribute` + HtmlSanitizer) agar tetap jalan untuk JSON.
- Slug: validasi unik per-bahasa di FormRequest (`Store`/`Update`), dengan `->ignore($this->route('...'))` saat update.

### 4.4 API / Service Layer
- API Resources output konten sesuai locale aktif + fallback ke `id`.
- **Cache key wajib ditambah locale** (mis. `blog.list.en.<hash>`) — `ClearsApiCache` sudah ada.
- Resolusi `getBySlug`: cari slug **di dalam JSON** sesuai locale aktif (query `slug->en = ...`).
- Search/filter yang memakai `where('title','like')` diadaptasi ke `JSON_EXTRACT`/scope spatie.
- Sitemap + canonical URL (trait `BuildsCanonicalUrl`) jadi **per-locale** → frontend dapat data hreflang.

### 4.5 Settings
- Setting teks (`site_name`, `site_description`, `default_meta_title`, `default_meta_description`, dan teks hero bila ada) ikut translatable — `value` jadi JSON.

## 5. Perubahan Admin (Blade)

- Form create/edit tiap resource: tambah **tab ID / EN** untuk tiap field translatable (9 resource + settings).
- Field slug menjadi 2 (ID/EN) dengan auto-generate dari title tab tersebut + tombol generate ulang (opsional).
- `FormRequest` validasi per-locale (`title.id` required, `title.en` nullable).
- Validasi unik slug **manual** per-locale di app layer (cek `"en": "..."` sudah dipakai belum).
- Index/list menampilkan locale default; search menyesuaikan JSON.

## 6. Perubahan Frontend (Next.js — `brava-compro`)

Lihat dokumen terpisah: `C:\laragon\www\brava-compro\docs\I18N_BILINGUAL_PLAN.md`

- `next-intl` + App Router `[locale]` segment.
- `generateStaticParams` → build statis per bahasa; middleware redirect `/` → `/id`.
- Fetch API pakai `?lang=${locale}`, cache per-locale (ISR / `unstable_cache`).
- hreflang + localized sitemap + `generateMetadata` per bahasa.
- Language switcher ID–EN.

## 7. Testing (Pest)

- API per-locale: `/api/blogs?lang=en` → konten EN; tanpa `lang` → ID; field EN kosong → fallback ID.
- Resolusi slug per-locale (`/api/blogs/convection-tips?lang=en`).
- Validasi admin per-locale + validasi unik slug.

## 8. Estimasi Effort / Biaya

Estimasi tambahan dari total proyek awal (± Rp 1.700.000):

| Opsi | Estimasi Tambahan |
|---|---|
| Satu slug global (paling aman) | ± Rp 510.000 – 600.000 (≈ 30–35%) |
| **Slug per-bahasa (dipilih)** | ± **Rp 680.000 – 765.000** (≈ 40–45%) |

Breakdown area:

| Area | Effort |
|---|---|
| Backend (migrasi, model, resources, service+cache, sitemap) | ~25% |
| Admin Blade forms (9 resource + settings, tab ID/EN, validasi) | ~30% |
| Frontend Next.js (routing, i18n, fetch, SEO) | ~25% |
| Testing + QA | ~15% |

> Catatan: estimasi **tidak termasuk** biaya/jam untuk menerjemahkan isi konten ke bahasa Inggris (itu kerjaan admin klien, bukan coding).

## 9. Catatan Penting

- **Tidak ada auto-translate.** Admin wajib mengisi versi Inggris di tab EN. Bila kosong → fallback ke Indonesia.
- Migrasi DB butuh backup + testing data dulu.
- Cache API per-locale; pastikan `ClearsApiCache` ikut membersihkan semua varian locale.
- Kerumitan terbesar ada di backend lookup slug JSON + validasi unik manual, bukan di sisi fetch frontend.

## 10. Open Items / Follow-up

- [ ] Finalisasi scope & harga dengan User.
- [ ] Konfirmasi penamaan label route di frontend (`/blogs` vs `/blog`, dsb).
- [ ] Konfirmasi daftar field yang wajib required di `en` (opsional).
- [ ] Persetujuan untuk mulai eksekusi (Fase 0–6).
