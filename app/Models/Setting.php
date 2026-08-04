<?php

namespace App\Models;

use App\Traits\ClearsApiCache;
use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use ClearsApiCache, HasFactory, HasTranslations;

    public array $translatable = ['value'];

    /**
     * Settings whose value is stored as a translatable JSON payload
     * (e.g. {"id": "...", "en": "..."}). All other settings keep a plain value.
     */
    private const TRANSLATABLE_KEYS = [
        'site_name',
        'site_description',
        'default_meta_title',
        'default_meta_description',
        'hero_title',
        'hero_subtitle',
        'contact_address',
        'footer_copyright',
    ];

    protected $fillable = ['key', 'value', 'group', 'type'];

    public static function translatableKeys(): array
    {
        return self::TRANSLATABLE_KEYS;
    }

    public function getTranslatableAttributes(): array
    {
        return in_array($this->key, self::TRANSLATABLE_KEYS, true) ? ['value'] : [];
    }

    public function getAttributeValue($key): mixed
    {
        if ($key === 'value') {
            $raw = $this->getAttributeFromArray('value');

            if (in_array($this->key, self::TRANSLATABLE_KEYS, true)) {
                if (is_string($raw) && ! str_starts_with(ltrim($raw), '{')) {
                    return $raw;
                }

                return $this->getTranslation($key, $this->getLocale(), $this->useFallbackLocale());
            }

            if (is_string($raw) && str_starts_with(ltrim($raw), '{')) {
                $decoded = json_decode($raw, true);

                if (is_array($decoded) && array_key_exists('id', $decoded)) {
                    return $decoded['id'];
                }
            }

            return parent::getAttributeValue($key);
        }

        return parent::getAttributeValue($key);
    }

    public function setAttribute($key, $value)
    {
        if ($key === 'value' && in_array($this->key, self::TRANSLATABLE_KEYS, true)) {
            if (is_array($value)) {
                return $this->setTranslations($key, $value);
            }

            return $this->setTranslation($key, $this->getLocale(), $value);
        }

        return parent::setAttribute($key, $value);
    }

    private const LABELS = [
        'site_name' => 'Site Name',
        'site_description' => 'Site Description',
        'google_analytics_id' => 'Google Analytics ID',
        'default_meta_title' => 'Default Meta Title',
        'default_meta_description' => 'Default Meta Description',
        'adsense_enabled' => 'AdSense Enabled',
        'adsense_client_id' => 'AdSense Publisher ID',
        'adsense_slot_1' => 'AdSlot 1 ID',
        'adsense_slot_2' => 'AdSlot 2 ID',
        'whatsapp_number' => 'WhatsApp Number',
        'phone' => 'Phone',
    ];

    private const HINTS = [
        'google_analytics_id' => '⚠️ Kritis (SuperAdmin): Mengubah ID ini akan mengganti tujuan pelacakan data pengunjung di Google Analytics 4. Pastikan format diawali huruf G- (contoh: G-XXXXXXXXXX) agar tracking script di frontend Next.js tetap aktif.',
        'default_meta_title' => 'ℹ️ SEO Impact (SuperAdmin): Digunakan sebagai judul standar (root title) di mesin pencari Google dan OpenGraph sosial media saat halaman tidak memiliki meta title khusus.',
        'default_meta_description' => 'ℹ️ SEO Impact (SuperAdmin): Digunakan sebagai deskripsi standar (root description) pada hasil pencarian Google. Usahakan 150–160 karakter agar tidak terpotong.',
        'site_name' => '⚠️ System Branding: Nama utama sistem yang tampil di title bar browser dan header API frontend Next.js.',
        'site_description' => 'ℹ️ Brand Bio & SEO (SuperAdmin): Deskripsi utama brand yang digunakan pada struktur Schema.org JSON-LD dan metadata deskripsi default global.',
        'adsense_enabled' => '⚠️ Revenue (SuperAdmin): Aktifkan untuk menampilkan iklan AdSense di frontend. Iklan hanya tampil jika Publisher ID (ca-pub-...) juga terisi.',
        'adsense_client_id' => '⚠️ Revenue (SuperAdmin): Publisher ID dari dashboard AdSense, format diawali huruf ca-pub- (contoh: ca-pub-XXXXXXXXXXXXXXXX). Dipakai loader script di frontend Next.js.',
        'adsense_slot_1' => 'ℹ️ AdSlot pertama (posisi bebas, contoh: atas blog). Isi Slot ID dari halaman ad unit AdSense. Tiap ad unit wajib punya slot ID berbeda agar tidak dianggap duplikat.',
        'adsense_slot_2' => 'ℹ️ AdSlot kedua (posisi bebas, contoh: sidebar blog). Isi Slot ID dari ad unit kedua AdSense, harus berbeda dari AdSlot 1.',
        'phone' => 'Nomor telepon yang ditampilkan di website. Format: +62 812 3456 7890.',
        'whatsapp_number' => 'Nomor WhatsApp untuk tombol chat. Format: 6281234567890 (tanpa + dan spasi).',
    ];

    public function getLabelAttribute(): string
    {
        return self::LABELS[$this->key] ?? str_replace('_', ' ', ucfirst($this->key));
    }

    public function getHintAttribute(): ?string
    {
        return self::HINTS[$this->key] ?? null;
    }

    public function scopeInGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
