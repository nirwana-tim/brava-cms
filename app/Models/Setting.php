<?php

namespace App\Models;

use App\Traits\ClearsApiCache;
use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use ClearsApiCache, HasFactory;

    protected $fillable = ['key', 'value', 'group', 'type'];

    private const LABELS = [
        'site_name' => 'Site Name',
        'site_description' => 'Site Description',
        'google_analytics_id' => 'Google Analytics ID',
        'default_meta_title' => 'Default Meta Title',
        'default_meta_description' => 'Default Meta Description',
        'whatsapp_number' => 'WhatsApp Number',
        'phone' => 'Phone',
    ];

    private const HINTS = [
        'google_analytics_id' => '⚠️ Kritis (SuperAdmin): Mengubah ID ini akan mengganti tujuan pelacakan data pengunjung di Google Analytics 4. Pastikan format diawali huruf G- (contoh: G-XXXXXXXXXX) agar tracking script di frontend Next.js tetap aktif.',
        'default_meta_title' => 'ℹ️ SEO Impact (SuperAdmin): Digunakan sebagai judul standar (root title) di mesin pencari Google dan OpenGraph sosial media saat halaman tidak memiliki meta title khusus.',
        'default_meta_description' => 'ℹ️ SEO Impact (SuperAdmin): Digunakan sebagai deskripsi standar (root description) pada hasil pencarian Google. Usahakan 150–160 karakter agar tidak terpotong.',
        'site_name' => '⚠️ System Branding: Nama utama sistem yang tampil di title bar browser dan header API frontend Next.js.',
        'site_description' => 'ℹ️ Brand Bio & SEO (SuperAdmin): Deskripsi utama brand yang digunakan pada struktur Schema.org JSON-LD dan metadata deskripsi default global.',
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
