#!/usr/bin/env bash
# =============================================================================
# Brava CMS - Cron setup helper for cPanel / shared hosting
# -----------------------------------------------------------------------------
# Cara pakai (di cPanel > Terminal, atau SSH dari folder project Laravel):
#   bash scripts/cron-setup.sh
#
# Script ini akan:
#   1. Mendeteksi lokasi PHP yang dipakai hosting.
#   2. Memverifikasi artisan bisa jalan.
#   3. Menampilkan daftar jadwal scheduler (schedule:list).
#   4. Membuat baris cron siap-tempel untuk cPanel.
#   5. (Opsional, hanya VPS) memasang crontab otomatis.
# =============================================================================
set -euo pipefail

PROJECT_DIR="$(pwd)"
if [ ! -f "$PROJECT_DIR/artisan" ]; then
    echo "ERROR: Jalankan script ini dari folder root Laravel (tempat file 'artisan')."
    echo "Sekarang kamu di: $PROJECT_DIR"
    exit 1
fi

echo "=== [1/4] Mendeteksi lokasi PHP ==="
PHP_BIN=""
for candidate in \
    /usr/local/bin/php \
    /usr/local/bin/ea-php83 \
    /usr/local/bin/ea-php82 \
    /usr/local/bin/ea-php81 \
    /usr/bin/php \
    /opt/alt/php82/usr/bin/php \
    /opt/alt/php81/usr/bin/php; do
    if [ -x "$candidate" ]; then
        PHP_BIN="$candidate"
        break
    fi
done

if [ -z "$PHP_BIN" ]; then
    PHP_BIN="$(command -v php || true)"
fi

if [ -z "$PHP_BIN" ]; then
    echo "PHP tidak ditemukan otomatis. Edit script ini dan set PHP_BIN secara manual."
    exit 1
fi
echo "Lokasi PHP  : $PHP_BIN"
echo "Folder proyek: $PROJECT_DIR"

echo
echo "=== [2/4] Verifikasi artisan bisa jalan ==="
if ! "$PHP_BIN" artisan --version; then
    echo "ERROR: artisan gagal dijalankan. Cek .env dan izin folder."
    exit 1
fi

echo
echo "=== [3/4] Daftar jadwal scheduler ==="
"$PHP_BIN" artisan schedule:list || true

echo
echo "=== [4/4] Baris cron untuk cPanel ==="
CRON_LINE="* * * * * cd $PROJECT_DIR && $PHP_BIN artisan schedule:run >> /dev/null 2>&1"
echo "Salin baris di bawah ini ke cPanel > Cron Jobs > 'Command':"
echo "------------------------------------------------------------------"
echo "$CRON_LINE"
echo "------------------------------------------------------------------"
echo

# Opsional: pasang crontab otomatis (hanya berlaku di VPS/dedicated yang punya
# akses 'crontab'. Di shared hosting cPanel, abaikan dan pakai menu Cron Jobs).
if command -v crontab >/dev/null 2>&1; then
    read -r -p "Pasang otomatis ke crontab pengguna? [y/N]: " -n 1 ans
    echo
    case "${ans:-N}" in
        y | Y)
            # Hapus baris lama dengan signature yang sama, lalu tambahkan yang baru.
            ( crontab -l 2>/dev/null | grep -v "brava-cms/schedule-cron" | \
              cat; echo "$CRON_LINE # brava-cms/schedule-cron" ) | crontab -
            echo "Crontab terpasang. Verifikasi: crontab -l"
            ;;
        *) echo "Skipped. Tempel manual di cPanel > Cron Jobs." ;;
    esac
else
    echo "Perintah 'crontab' tidak tersedia (normal di shared hosting)."
    echo "Tempel baris cron di atas lewat menu cPanel > Cron Jobs."
fi
