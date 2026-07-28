<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Spesialis Vest & PDH',
                'slug' => 'spesialis-vest-pdh',
                'description' => 'Produksi andalan untuk kemeja PDH, PDL, dan Vest organisasi. Dirancang agar nyaman digunakan beraktivitas, dengan detail jahitan dan bordir yang rapi.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Custom Jersey Tim',
                'slug' => 'custom-jersey-tim',
                'description' => 'Pembuatan jersey olahraga maupun seragam acara santai. Kami menggunakan bahan yang menyerap keringat dengan teknik cetak yang awet dan tajam.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Kustomisasi Konveksi',
                'slug' => 'kustomisasi-konveksi',
                'description' => 'Punya ide seragam lain? Dari jaket komunitas hingga pakaian kerja spesifik, tim kami siap membantu Anda mencari bahan dan solusi produksi yang paling tepat.',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($services as $data) {
            Service::create($data);
        }
    }
}
