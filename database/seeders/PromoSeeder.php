<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        Promo::create([
            'title' => '40% Diskon Untuk Pemesanan Seragam Perusahaan',
            'slug' => '40-diskon-untuk-pemesanan-seragam-perusahaan',
            'badge_text' => 'PROMO TERBATAS',
            'discount_info' => '40%',
            'description' => 'Dapatkan potongan harga hingga 40% untuk pembuatan seragam perusahaan, kemeja PDH, jaket kantor, dan kaos event korporat. Konsultasikan desain dan bahan pilihan Anda bersama tim spesialis Brava.',
            'image' => null,
            'image_alt' => '40% Diskon Untuk Pemesanan Seragam Perusahaan',
            'valid_from' => now()->subDays(10),
            'valid_until' => now()->addMonths(2),
            'wa_template' => 'Halo Brava, saya tertarik untuk mengklaim Diskon 40% untuk Pemesanan Seragam Perusahaan. Mohon info lebih lanjut.',
            'is_highlighted' => true,
            'is_active' => true,
        ]);

        $regularPromos = [
            [
                'title' => 'Diskon 20% Pemesanan Kaos Komunitas & Event',
                'badge' => 'PROMO EVENT',
                'discount' => '20%',
            ],
            [
                'title' => 'Gratis Desain & Bordir Untuk Order Minimum 50 Pcs',
                'badge' => 'SPECIAL DEAL',
                'discount' => 'FREE BORDIR',
            ],
            [
                'title' => 'Potongan Rp 500.000 Pemesanan Jaket Bomber Eksklusif',
                'badge' => 'BEST SELLER',
                'discount' => 'Rp 500.000',
            ],
            [
                'title' => 'Diskon 15% Untuk Pengambilan Kedua & Repeat Order',
                'badge' => 'LOYALTY PROMO',
                'discount' => '15%',
            ],
        ];

        foreach ($regularPromos as $index => $item) {
            Promo::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'badge_text' => $item['badge'],
                'discount_info' => $item['discount'],
                'description' => "Nikmati penawaran spesial {$item['title']} dengan kualitas bahan terbaik dari Brava CMS.",
                'image' => null,
                'image_alt' => $item['title'],
                'valid_from' => now()->subDays(5),
                'valid_until' => now()->addMonths(1),
                'wa_template' => "Halo Brava, saya ingin klaim promo: {$item['title']}.",
                'is_highlighted' => false,
                'is_active' => true,
            ]);
        }
    }
}
