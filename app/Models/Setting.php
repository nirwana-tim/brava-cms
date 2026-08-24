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

    /**
     * Group that a setting key belongs to. Used when a key is submitted via
     * the admin form but does not exist yet, so it is created in the right
     * group instead of defaulting to `general`.
     */
    public static function defaultGroupFor(string $key): string
    {
        return match ($key) {
            'whatsapp_number', 'address', 'email', 'phone' => 'contact',
            'facebook_url', 'instagram_url', 'youtube_url', 'tiktok_url', 'x_url', 'linkedin_url' => 'social',
            'default_meta_title', 'default_meta_description', 'default_og_image',
            'google_verification', 'bing_verification', 'custom_webmaster_tags',
            'organization_schema', 'google_analytics_id' => 'seo',
            'adsense_enabled', 'adsense_client_id', 'adsense_slot_1', 'adsense_slot_2' => 'adsense',
            'ga4_property_id', 'ga4_service_account_key' => 'system',
            default => 'general',
        };
    }

    /**
     * Setting keys that are allowed to be created via the admin settings form.
     * Anything outside this list is rejected to prevent arbitrary key creation.
     *
     * @return list<string>
     */
    public static function allowedKeys(): array
    {
        return array_values(array_unique([
            ...self::TRANSLATABLE_KEYS,
            ...array_keys(self::LABELS),
            'address',
            'email',
            'default_og_image',
            'google_verification',
            'bing_verification',
            'custom_webmaster_tags',
            'organization_schema',
        ]));
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

                if (is_array($decoded) && array_key_exists('id', $decoded) && array_diff(array_keys($decoded), ['id', 'en']) === []) {
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
        'google_verification' => 'Google Search Console Verification',
        'bing_verification' => 'Bing Webmaster Tools Verification',
        'custom_webmaster_tags' => 'Custom Webmaster Tags',
        'organization_schema' => 'Organization Schema (JSON-LD)',
        'adsense_enabled' => 'AdSense Enabled',
        'adsense_client_id' => 'AdSense Publisher ID',
        'adsense_slot_1' => 'AdSlot 1 ID',
        'adsense_slot_2' => 'AdSlot 2 ID',
        'whatsapp_number' => 'WhatsApp Number',
        'phone' => 'Phone',
        'facebook_url' => 'Facebook URL',
        'instagram_url' => 'Instagram URL',
        'youtube_url' => 'YouTube URL',
        'tiktok_url' => 'TikTok URL',
        'x_url' => 'X (Twitter) URL',
        'linkedin_url' => 'LinkedIn URL',
        'ga4_property_id' => 'GA4 Property ID (dashboard)',
        'ga4_service_account_key' => 'GA4 Service Account Key',
    ];

    private const HINTS = [
        'google_analytics_id' => 'ID properti Google Analytics 4 (format: G-XXXXXXXXXX). Mengubah nilai ini mengganti tujuan pelacakan pengunjung di frontend.',
        'default_meta_title' => 'Judul standar (root title) untuk Google dan OpenGraph saat halaman tidak memiliki meta title khusus.',
        'default_meta_description' => 'Deskripsi standar (root description) untuk hasil pencarian Google. Usahakan 150–160 karakter.',
        'google_verification' => 'Kode verifikasi Google Search Console (isi token content dari tag meta: <meta name="google-site-verification" content="..." />).',
        'bing_verification' => 'Kode verifikasi Bing Webmaster Tools (isi token content dari tag meta: <meta name="msvalidate.01" content="..." />).',
        'custom_webmaster_tags' => 'Tag meta verifikasi kustom lainnya (misal: Pinterest, Baidu, Yandex, Norton). Masukkan tag <meta ... /> lengkap.',
        'organization_schema' => 'Kustom JSON-LD Schema.org untuk profil perusahaan/bisnis (opsional). Kosongkan jika ingin memakai schema otomatis bawaan sistem.',
        'site_name' => 'Nama utama yang tampil di title bar browser dan header API frontend.',
        'site_description' => 'Deskripsi utama brand untuk Schema.org JSON-LD dan metadata deskripsi global.',
        'adsense_enabled' => 'Aktifkan untuk menampilkan iklan AdSense di frontend. Iklan hanya tampil jika Publisher ID terisi.',
        'adsense_client_id' => 'Publisher ID AdSense (format: ca-pub-XXXXXXXXXXXXXXXX). Dipakai loader script di frontend.',
        'adsense_slot_1' => 'Slot ID ad unit pertama (mis. atas blog). Tiap ad unit wajib punya slot ID berbeda.',
        'adsense_slot_2' => 'Slot ID ad unit kedua (mis. sidebar blog). Harus berbeda dari slot pertama.',
        'phone' => 'Nomor telepon yang ditampilkan di website. Format: +62 812 3456 7890.',
        'whatsapp_number' => 'Nomor WhatsApp untuk tombol chat. Format: 6281234567890 (tanpa + dan spasi).',
        'facebook_url' => 'URL profil/halaman Facebook. Kosongkan untuk menyembunyikan tombolnya.',
        'instagram_url' => 'URL profil Instagram. Kosongkan untuk menyembunyikan tombolnya.',
        'youtube_url' => 'URL channel YouTube. Kosongkan untuk menyembunyikan tombolnya.',
        'tiktok_url' => 'URL profil TikTok. Kosongkan untuk menyembunyikan tombolnya.',
        'x_url' => 'URL profil X (Twitter). Kosongkan untuk menyembunyikan tombolnya.',
        'linkedin_url' => 'URL profil LinkedIn. Kosongkan untuk menyembunyikan tombolnya.',
        'ga4_property_id' => 'ID numerik properti GA4 (angka, bukan G-XXXXXX) untuk laporan dashboard. Ambil dari Admin → Property Settings.',
        'ga4_service_account_key' => 'Tempel isi file JSON service account (dari Google Cloud). Hanya superadmin yang melihat; tidak pernah di-expose ke API publik.',
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
