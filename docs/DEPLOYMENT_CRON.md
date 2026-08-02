# Panduan Cron Job di cPanel — Brava CMS

> Panduan langkah demi langkah untuk menjalankan Laravel Scheduler di hosting
> **cPanel (shared hosting)**. Baca sampai habis sebelum mulai — sebagian besar
> kegagalan cron berasal dari *path yang salah*.

---

## 0. Kenapa butuh ini

Jadwal berikut didefinisikan di `routes/console.php`:

```php
Schedule::command('promos:clear-stale-highlights')
    ->daily()
    ->withoutOverlapping();
```

Server perlu "diingatkan" tiap menit untuk mengecek jadwal tersebut, dengan satu
baris cron:

```
* * * * * php artisan schedule:run
```

Baris di atas **belum cukup** — di cron, semua path harus **lengkap/absolut**
(`/home/...`, bukan `php` saja). Ikuti langkah di bawah.

---

## 1. Siapkan dulu (satu kali saja)

### A. Tahu folder project di server
1. Login cPanel.
2. Menu **File Manager** → buka folder project (misal `brava-cms` di dalam
   `public_html`, atau di luar `public_html` + subdomain).
3. Catat path lengkapnya, contoh:
   - `/home/budisetiawan/brava-cms`

### B. Tahu lokasi PHP
1. cPanel → menu **"MultiPHP Manager"** / **"Select PHP Version"**.
2. Path PHP tidak selalu tertulis di sana. Cara paling andal: menu **"Terminal"**
   (kalau tersedia) lalu ketik:
   ```bash
   which php
   ```
   Contoh hasil: `/usr/local/bin/php` atau `/usr/local/bin/ea-php83`.
3. Tidak punya Terminal? Pakai nilai umum berikut (pilih yang ada):
   - `/usr/local/bin/php`
   - `/usr/local/bin/ea-php83`
   - `/usr/bin/php`

> Alternatif otomatis: jalankan script bantuan berikut dari Terminal cPanel
> (dari folder project):
> ```bash
> bash scripts/cron-setup.sh
> ```
> Script ini mendeteksi PHP, memverifikasi `artisan`, menampilkan `schedule:list`,
> dan mencetak baris cron siap-tempel.

---

## 2. Pasang cron di cPanel

1. cPanel → menu **"Cron Jobs"** (grup *Advanced*).
2. Bagian **Add New Cron Job**:
   - **Common Settings**: pilih `Once Per Day`, atau biarkan `Custom`.
   - Kolom **Minute/Hour/Day/Month/Weekday** → isi **semua** dengan `*`
     (ini penting: biarkan Laravel yang memfilter jadwal hariannya).
     Persis seperti: `* * * * *`
   - Kolom **Command** → isi:
     ```
     cd /home/USERNAME/brava-cms && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
     ```
     Ganti `USERNAME` dan `brava-cms` dengan path asli; ganti path PHP dengan
     hasil dari langkah 1B.
3. Klik **Add New Cron Job**.

**Anatomi baris tersebut:**
| Bagian | Arti |
|---|---|
| `cd /home/USERNAME/brava-cms` | pindah ke folder project (wajib!) |
| `&&` | "lalu jalankan" |
| `/usr/local/bin/php` | path PHP absolut |
| `artisan schedule:run` | memeriksa & menjalankan jadwal yang waktunya tiba |
| `>> /dev/null 2>&1` | buang output agar tidak banjir email dari cron |

---

## 3. Verifikasi

1. Test command secara manual dulu (paling penting):
   ```bash
   cd /home/USERNAME/brava-cms && /usr/local/bin/php artisan schedule:list
   ```
   Harusnya menampilkan `promos:clear-stale-highlights`.
2. Jalankan sekali manual:
   ```bash
   cd /home/USERNAME/brava-cms && /usr/local/bin/php artisan promos:clear-stale-highlights
   ```
   Output: `Cleared stale highlights from N promo(s).`
3. Simulasikan yang dijadwalkan (tanpa menunggu tengah malam):
   ```bash
   cd /home/USERNAME/brava-cms && /usr/local/bin/php artisan schedule:run
   ```
   Jika belum waktunya, output kosong — itu normal.

> **Debugging**: jika mencurigai error, sementara hapus `>> /dev/null 2>&1`
> dari baris cron. Error akan dikirim ke email cPanel kamu (atau atur di bagian
> **"Send an email to"** pada menu Cron Jobs). Setelah beres, kembalikan lagi.

---

## 4. Common pitfalls

| Masalah | Penyebab | Solusi |
|---|---|---|
| Cron jalan tapi tidak ada efek | Path PHP salah / `cd` salah | Pakai path absolut + `cd` dulu |
| Artisan "command not found" | `php` tanpa path lengkap | Ganti dengan `/usr/local/bin/php` (dst) |
| Iklan promo tidak pernah bersih | Cache API ikut kebaca | Command ini sudah `Cache::store('api')->flush()`, pastikan versi kode terbaru sudah di-deploy |
| Takut nyoba | Takut merusak | `artisan schedule:run` tidak berbahaya; hanya menjalankan tugas yang waktunya tiba |

---

## 5. Alternatif: VPS / Laravel Cloud

- **VPS/dedicated**: jalankan `bash scripts/cron-setup.sh` (opsi instal otomatis
  ke `crontab -e` tersedia), atau tempel baris yang dicetak script.
- **Laravel Cloud**: scheduler dikelola otomatis platform — tidak perlu cron.
- **Lokal (development)**: `php artisan schedule:work` (berjalan di foreground).

---

## 6. Ringkasan satu baris

```
* * * * * cd /home/USERNAME/brava-cms && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

Ganti `USERNAME`, folder, dan path PHP sesuai server kamu.
