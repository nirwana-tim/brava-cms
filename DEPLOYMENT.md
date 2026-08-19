# Deploy Brava CMS — cPanel Shared Hosting

Runbook lengkap untuk men-deploy **brava-cms** (Laravel 13) ke **cPanel shared hosting**.
Backend produksi: `https://cms.brava.id` · Frontend publik: `https://brava.id` (Next.js, di Vercel).

---

## Arsitektur singkat

```
brava-compro (Next.js 16, Vercel)  --HTTP-->  brava-cms (Laravel 13, cPanel)
    SSR/ISR reads (server-side)                     GET  /api/v1/*
```

- API publik seluruhnya **GET tanpa auth**.
- Admin panel = Blade, di **domain sama** dengan API (`cms.brava.id/admin`) → tidak kena CORS.
- Tidak ada queue job aktif dan tidak ada email aktif → worker queue & SMTP **belum wajib**.

---

## 0. Pre-flight checklist

- [ ] PHP **≥ 8.3** tersedia di host (Laravel 13 butuh `^8.3`).
- [ ] Ekstensi PHP: `gd` (**dengan dukungan WebP**), `mbstring`, `intl`, `curl`, `openssl`, `pdo_mysql`, `dom`/`xml`, `fileinfo`, `bcmath`, `sodium`.
- [ ] Composer bisa dijalankan (cPanel "Setup PHP Application" atau Terminal).
- [ ] Bun/Node tersedia **lokal** untuk build Vite (`public/build`). (npm dan bun sama-sama bisa — project punya `package-lock.json` + `bun.lock`.)
- [ ] Domain `brava.id` dan `cms.brava.id` sudah mengarah ke hosting (A record).
- [ ] SSL (AutoSSL/gratis) tersedia untuk `cms.brava.id`.

---

## 1. Setup cPanel

### 1.1 Subdomain backend
1. cPanel → **Subdomains** → buat `cms` pada domain `brava.id`.
2. **Document root** arahkan ke: `/home/USER/brava-cms/public`
   > Jangan arahkan ke `public_html`. Letakkan project di `/home/USER/brava-cms` (di luar `public_html`) supaya `.env` dan source tidak pernah ter-serve publik.
   > Jika host memaksa docroot di dalam `public_html`, alternatif: upload project ke `/home/USER/brava-cms`, lalu di `public_html` buat folder `cms` yang berisi symlink/isi dari `public/`. (Kurang ideal; usahakan opsi pertama.)

### 1.2 Database MySQL
1. cPanel → **MySQL Databases** → buat DB (mis. `brava_cms`) + user (mis. `brava_cms`).
2. Klik **Add User To Database** → beri **ALL PRIVILEGES**.
3. Catat `DB_HOST` (biasanya `127.0.0.1` atau `localhost`), nama DB, user, password.

### 1.3 Versi & ekstensi PHP
1. cPanel → **MultiPHP Manager** → pilih **PHP 8.3+** untuk `cms.brava.id`.
2. Di **Select PHP Version** pastikan ekstensi di atas aktif.
3. Pastikan **GD mendukung WebP**: jalankan `php -r "var_dump(function_exists('imagewebp'));"` → harus `true`. (Tanpa ini upload gambar gagal.)

### 1.4 Batas upload & memori
Buat file `.user.ini` di **docroot project** (`/home/USER/brava-cms/public/.user.ini`):

```ini
upload_max_filesize = 20M
post_max_size = 20M
memory_limit = 256M
max_execution_time = 120
```

(Aplikasi membatasi upload 10MB per file, 20M memberi ruang encoding.)

---

## 2. Build asset (lokal, sebelum upload)

Blade admin memakai Vite. Jalankan di repo lokal `brava-cms`:

```bash
npm install
npm run build
```

> Pakai bun di lokal? `bun install` + `bun run build` juga tetap bisa — project ini punya `bun.lock` dan `package-lock.json` sekaligus.

Pastikan `public/build/` dan `public/manifest.json` ter-update. (Bersihkan dulu `public/build/*` lama bila perlu.)

---

## 2b. Incremental deploy (git-based)

Kalau server sudah menjalankan git (`git clone`/`git pull` di `/home/USER/brava-cms`), deploy perubahan berikut cukup dengan `git pull`. **`public/build` di-gitignore** → hasil build Vite tidak pernah ikut via git.

### Aturan: kapan perlu `bun run build`?

| Jenis perubahan | Perlu build? | Langkah |
|---|---|---|
| PHP / Blade / migration / seeder / routes | ❌ Tidak | `git pull` + refresh cache |
| `resources/css/*`, `resources/js/*`, `vite.config.*`, `package.json`, atau **class Tailwind baru di Blade** yang belum ada di CSS hasil build | ✅ Ya | `bun run build` lokal, lalu **upload manual** `public/build/` + `public/manifest.json` (atau build di server jika terminal cPanel punya node/bun) |

> Cek cepat apakah ada perubahan asset: `git status --short` → ada file `resources/css`, `resources/js`, `package.json`, `vite.config.*`? Kalau tidak ada → aman tanpa build.

### Runbook backend-only (ganti `bun run build`/upload)

```bash
cd ~/brava-cms
git pull
composer install --no-dev --optimize-autoloader --no-interaction   # hanya jika composer.lock berubah
php artisan migrate --force                                        # jika ada migration baru
php artisan db:seed --class=PageSeoSeeder --force                  # seed idempotent (updateOrCreate)
php artisan optimize:clear
php artisan config:cache route:cache view:cache event:cache
```

- `optimize:clear` lalu re-cache wajib karena produksi memakai `config/route/view/event` cache dan Blade berubah.
- Migration baru wajib `migrate --force` sebelum halaman baru dipakai.
- Seed bisa dijalankan penuh (`db:seed --force`) — `DatabaseSeeder` idempotent (`firstOrCreate`/`updateOrCreate`).
- Tambahan route API baru tidak berdampak ke frontend; redeploy `brava-compro` (Vercel) hanya jika mau memakai endpoint baru.

### Runbook git pull + upload build via FTP (terminal tanpa npm/bun)

Setup yang dipakai di produksi: terminal cPanel bisa `git pull` dan `php artisan`, tapi **tidak bisa** menjalankan `npm install`/`bun run build` → build Vite dikerjakan lokal, hasilnya di-upload manual via FTP.

**Lokal (komputer dev):**
1. Commit & push: `git add -A && git commit -m "..." && git push`
2. Build asset: `bun run build` (atau `npm run build`) → regenerasi `public/build/` + `public/manifest.json`.

**Hosting (Terminal cPanel) — tarik source + urus DB:**
```bash
cd ~/brava-cms
git pull
php artisan migrate --force                          # jika ada migration baru
php artisan db:seed --class=PageSeoSeeder --force    # jika ada seeder baru
```

**Upload build via FTP/File Manager** — tujuan `/home/USER/brava-cms/public/`:
- Hapus isi folder `public/build` lama, lalu upload **seluruh isi folder `public/build`** hasil build lokal (`assets/`, `manifest.json`, `fonts-manifest.json`).
- `manifest.json` Vite ada **di dalam** `public/build/` — bukan di level `public/`. Tidak ada file `public/manifest.json` tersendiri.

> Jangan tertukar dengan `public/site.webmanifest` — itu PWA/web app manifest (file statis yang **di-track git**), otomatis ikut saat `git pull`, dan **bukan** hasil build Vite. Tidak perlu di-upload via FTP.

**Hosting (Terminal cPanel) — refresh cache:**
```bash
php artisan optimize:clear
php artisan config:cache route:cache view:cache event:cache
```

- Urutan `git pull` vs upload build bebas — git tidak menyentuh `public/build` karena di-gitignore.
- Cek konsistensi manifest: nama file hash di `public/build/manifest.json` harus ada di dalam folder `public/build`. Jika muncul `Unable to locate file in Vite manifest` → upload build belum selesai atau salah folder.
- Cek cepat apakah batch ini butuh build: `git status --short` lihat `resources/css`, `resources/js`, `package.json`, `vite.config.*` → tidak ada berarti hanya runbook backend-only di atas.

---

## 3. Upload file ke server

Yang **harus di-upload** ke `/home/USER/brava-cms/`:

```
app/            bootstrap/      config/
database/       public/         resources/
routes/         storage/        tests/ (opsional)
composer.json   composer.lock   .env.production.example
artisan
```

Yang **jangan di-upload**:
- `.env` (buat ulang di server), `node_modules/`, `.git/`, `vendor/` (install di server), `storage/app/public/**` (konten upload lokal), file log lokal.

Pakai File Manager / FTP dengan cara aman. Isi `storage/framework/{cache,views,sessions}` dibiarkan kosong — akan dibuat otomatis.

---

## 4. Install dependency & konfigurasi

> Sebaiknya via **Terminal** cPanel (SSH). Alternatif: cPanel → "Setup PHP Application" → **Composer**.

```bash
cd ~/brava-cms
composer install --no-dev --optimize-autoloader --no-interaction
```

Buat environment:

```bash
cp .env.production.example .env
```

Lalu edit `.env` dan isi sesuai tabel di bawah (**jangan** pakai nilai `.env` dari lokal).

### Tabel env produksi

| Variabel | Nilai contoh | Keterangan |
|---|---|---|
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | Wajib di produksi |
| `APP_KEY` | *(generate)* | `php artisan key:generate` |
| `APP_URL` | `https://cms.brava.id` | Domain backend; dipakai URL gambar absolute |
| `FRONTEND_URL` | `https://brava.id` | Canonical URL & sitemap — **bukan** untuk CORS |
| `DB_CONNECTION` | `mysql` | Default `.env` masih `sqlite`! |
| `DB_HOST/DB_DATABASE/DB_USERNAME/DB_PASSWORD` | *(dari cPanel)* | |
| `SESSION_DRIVER` | `database` | Tabel `sessions` (auto-migrate) |
| `SESSION_SECURE_COOKIE` | `true` | Setelah SSL aktif |
| `CACHE_STORE` | `database` | Store `api` → tabel `api_cache` |
| `LOG_CHANNEL` | `daily` | Rotasi log harian |
| `LOG_LEVEL` | `warning` | |
| `CORS_ALLOWED_ORIGINS` | `https://brava.id,https://brava-compro-git-dev-nirwana-tims-projects.vercel.app` | Origin frontend (wajib hanya jika ada request browser) |
| `CORS_ALLOWED_ORIGINS_PATTERNS` | *(opsional)* `/^https:\/\/.*\.vercel\.app$/` | Wildcard preview Vercel |
| `SUPERADMIN_EMAIL/SUPERADMIN_PASSWORD` | *(isi)* | Dibuat saat `db:seed` |
| `ADMIN_EMAIL/ADMIN_PASSWORD` | *(isi)* | Dibuat saat `db:seed` |
| `GA4_PROPERTY_ID`, `GA4_SERVICE_ACCOUNT_KEY` | *(opsional)* | Dashboard admin GA4 |

---

## 5. Perintah setelah upload

```bash
cd ~/brava-cms

php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Apa yang di-seed
`DatabaseSeeder` di versi ini hanya membuat:
- 1 user **Super Admin** + 1 user **Admin** (dari env) dan `team_members`-nya,
- **base settings** (`SettingSeeder` + `ContactSettingSeeder`) agar halaman Settings CMS bisa dipakai.

Konten (blog/portfolio/promo/testimonial/faq/service/kategori/team) **tidak** di-seed — diisi lewat CMS.

### Permission (jika perlu)
```bash
chmod -R 775 storage bootstrap/cache
```

### Storage symlink
`php artisan storage:link` membuat `public/storage` → `storage/app/public` (file upload disajikan di `/storage/uploads`, `/storage/media`).

Jika host **melarang symlink** (jarang, tapi bisa), alternatif manual via Terminal:
```bash
ln -s ~/brava-cms/storage/app/public ~/brava-cms/public/storage
```
Atau jika betul-betul tidak bisa: ubah disk `public` di `config/filesystems.php` agar `root` dan `url` menunjuk ke folder publik nyata. (Usahakan symlink dulu.)

---

## 6. Cron jobs (WAJIB)

cPanel → **Cron Jobs**, tambah:

```
* * * * * php /home/USER/brava-cms/artisan schedule:run >> /dev/null 2>&1
```

- Ganti `USER` dengan username cPanel.
- Jika `php` bukan versi 8.3, gunakan path eksplisit, mis. `/usr/local/bin/php83` (cek dengan `which php` atau cek versi di MultiPHP).
- Ini menjalankan 2 tugas terjadwal (`routes/console.php`):
  - `promos:clear-stale-highlights` — harian.
  - `media:cleanup-filenames --remove-orphans` — pukul 03:30. **Perintah ini menghapus file yang tidak direferensikan.**

> **Sebelum cron aktif pertama kali**: jalankan manual sekali dengan `--dry-run` dan **backup `storage/app/public` + DB**:
> ```bash
> php artisan media:cleanup-filenames --dry-run
> php artisan media:cleanup-filenames   # tanpa --remove-orphans, lihat output rename
> ```

---

## 7. SSL & cookie

1. cPanel → **SSL/TLS Status** → aktifkan AutoSSL untuk `cms.brava.id` (atau Let's Encrypt via host).
2. Pastikan `https://cms.brava.id` sudah terkunci.
3. `SESSION_SECURE_COOKIE=true` di `.env` (sudah default template produksi) → jalankan ulang `php artisan config:cache`.
4. Login admin: `https://cms.brava.id/admin`.

---

## 8. Integrasi frontend (brava-compro / Vercel)

1. Di project brava-compro, ganti base URL API dari ngrok/lokal menjadi:
   ```
   NEXT_PUBLIC_API_URL=https://cms.brava.id/api/v1     # sesuai struktur fetch di lib
   ```
   (Sesuaikan nama variabel dengan `lib/` — lihat `docs/API_INTEGRATION.md`.)
2. `CORS_ALLOWED_ORIGINS` di backend sudah berisi `https://brava.id` dan preview `https://brava-compro-git-dev-nirwana-tims-projects.vercel.app`.
3. Deploy/redeploy di Vercel. Data reads server-side → tidak kena CORS; form kontak (browser) kena → sudah di daftar.
4. Sanity check:
   - `curl https://cms.brava.id/api/v1/settings` → JSON.
   - `curl https://cms.brava.id/up` → `OK`.
   - `curl -I -H "Origin: https://brava.id" https://cms.brava.id/api/v1/settings` → header `Access-Control-Allow-Origin` ada.
   - Buka preview Vercel → data tampil, submit form kontak → 200.

---

## 9. Backup & rollback

- **Rutin**: backup DB via phpMyAdmin (Export SQL) + zip `storage/app/public` (upload) + `.env`.
- **Rollback deploy**: simpan folder sebelumnya (atau gunakan git tag di repo). Untuk data: `php artisan migrate:rollback` bukan untuk produksi — buat restore dari SQL backup.
- Sebelum menjalankan perintah destruktif (`media:cleanup-filenames --remove-orphans`, `migrate:fresh`) selalu backup.

---

## 10. Troubleshooting

| Gejala | Kemungkinan | Solusi |
|---|---|---|
| Halaman 500 / blank | APP_KEY kosong / permission | `php artisan key:generate`; `chmod -R 775 storage bootstrap/cache` |
| 404 untuk semua route kecuali `/` | Docroot salah / rewrite mati | Docroot harus `.../brava-cms/public`; pastikan `public/.htaccess` ada |
| Gambar upload 404 | Symlink belum ada | `php artisan storage:link` |
| Upload gambar gagal | GD tanpa WebP / limit | Cek `imagewebp`; naikkan `.user.ini` |
| Login tidak tersimpan (cookie hilang) | SESSION_SECURE_COOKIE=true tanpa SSL | Aktifkan SSL dulu, atau set false saat masih http |
| Data API lama setelah edit | Cache API (database) | `php artisan cache:clear` (store default) atau hapus tabel `api_cache` |
| Error migrasi `Unique constraint` | Data duplikat | Periksa key duplikat; jangan `migrate:fresh` di produksi tanpa backup |
| `Unable to locate file in Vite manifest` | `public/build` lama | `npm run build` (atau `bun run build`) lokal lalu upload `public/build` |
| Log penuh | `LOG_CHANNEL=single` | Pakai `LOG_CHANNEL=daily` |
| Cron tidak jalan | Path `php` salah | Ganti dengan `/usr/local/bin/php83` (sesuai host) |

---

## 11. Security checklist

- [ ] `APP_DEBUG=false`, `APP_ENV=production`.
- [ ] `APP_KEY` terisi, `.env` tidak ikut ter-upload & di luar docroot.
- [ ] Docroot tepat di `.../brava-cms/public` (folder lain tidak terserve).
- [ ] `SESSION_SECURE_COOKIE=true` + HTTPS-only (`SESSION_COOKIE` default).
- [ ] DB user tidak memakai password lemah; user DB hanya punya akses ke DB-nya.
- [ ] `CORS_ALLOWED_ORIGINS` daftar ketat (jangan `*`).
- [ ] Rate limit API aktif (throttle 60/menit per IP).
- [ ] Log `daily`; review `storage/logs/laravel-*.log`.
- [ ] Update patch: `composer update --no-dev` terjadwal; jalankan `php artisan migrate --force` setelahnya.

---

## 12. Checklist deploy akhir

```
[ ] PHP 8.3 + ekstensi (gd-webp)   [ ] Subdomain cms + docroot /public
[ ] DB MySQL dibuat                 [ ] .env terisi (tabel §4)
[ ] composer install --no-dev       [ ] key:generate
[ ] migrate --force                 [ ] db:seed --force
[ ] storage:link                    [ ] config/route/view/event cache
[ ] Cron schedule:run tiap menit    [ ] SSL aktif + secure cookie
[ ] CORS berisi frontend + preview  [ ] /up & /api/v1/settings 200
[ ] Frontend base URL -> cms.brava.id [ ] Backup DB + storage terjadwal
```

---

*Dokumen ini spesifik untuk `brava-cms`. Untuk frontend: `brava-compro/docs/AI_CONTEXT.md`, `API_INTEGRATION.md`, `CACHING.md`.*
