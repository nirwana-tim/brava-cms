<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $serviceIds = Service::pluck('id');

        $items = [
            [
                'title' => 'Seragam PDH Korpri Instansi Pemerintah',
                'slug' => 'seragam-pdh-korpri-instansi-pemerintah',
                'description' => 'Produksi seragam PDH untuk instansi pemerintah dengan bordir logo Korpri.',
                'specifications' => [
                    ['key' => 'Bahan', 'value' => 'Lacoste CVC'],
                    ['key' => 'Teknik Logo', 'value' => 'Bordir'],
                    ['key' => 'Warna', 'value' => 'Abu-abu / Hitam'],
                    ['key' => 'Size Range', 'value' => 'S – 5XL'],
                ],
                'features' => [
                    'Bahan adem dan nyaman dipakai seharian',
                    'Bordir logo rapi dan presisi',
                    'Jahitan kuat dan rapi',
                    'Harga grosir untuk instansi',
                ],
                'client' => 'KORPRI – Instansi Pemerintah',
                'completed_at' => '2026-05-15',
                'is_active' => true,
            ],
            [
                'title' => 'Jersey Futsal Komunitas',
                'slug' => 'jersey-futsal-komunitas',
                'description' => 'Cetak jersey futsal untuk komunitas dengan design custom dan nomor punggung.',
                'specifications' => [
                    ['key' => 'Bahan', 'value' => 'Dryfit Jade / Spandex'],
                    ['key' => 'Teknik Sablon', 'value' => 'Rubber Plastisol'],
                    ['key' => 'Pesanan', 'value' => 'Start dari 12 pcs'],
                    ['key' => 'Durasi Produksi', 'value' => '3 – 5 hari kerja'],
                ],
                'features' => [
                    'Warna tidak mudah pudar',
                    'Bahan cepat kering dan adem',
                    'Design bebas request',
                    'Include numbering & nama',
                ],
                'client' => 'Komunitas Futsal Jakarta',
                'completed_at' => '2026-03-20',
                'is_active' => true,
            ],
            [
                'title' => 'Jaket Bomber Komunitas & Merchandise',
                'slug' => 'jaket-bomber-komunitas-merchandise',
                'description' => 'Produksi jaket bomber custom untuk komunitas dan merchandise brand.',
                'specifications' => [
                    ['key' => 'Bahan', 'value' => 'Korean Drill / Taslan'],
                    ['key' => 'Teknik Logo', 'value' => 'Bordir / Polyflex'],
                    ['key' => 'Size Range', 'value' => 'M – 3XL'],
                    ['key' => 'Durasi Produksi', 'value' => '7 – 14 hari kerja'],
                ],
                'features' => [
                    'Bahan tebal dan hangat',
                    'Zipper premium tidak mudah macet',
                    'Bordir logo rapi dan awet',
                    'Bisa mixed size dalam satu order',
                ],
                'client' => 'Komunitas & Brand Merch',
                'completed_at' => '2026-01-10',
                'is_active' => true,
            ],
        ];

        foreach ($items as $data) {
            $item = PortfolioItem::create($data);
            if ($serviceIds->isNotEmpty()) {
                $item->service()->associate($serviceIds->random());
                $item->save();
            }
        }
    }
}
