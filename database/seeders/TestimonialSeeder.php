<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'KORPRI Kota Malang',
                'content' => 'Pemesanan PDH untuk anggota KORPRI sangat puas dengan hasil jahitan yang rapi dan bahan yang nyaman dipakai seharian.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'client_name' => 'SMA Negeri 1 Surabaya',
                'content' => 'Jersey untuk acara class meeting keren banget! Cetakannya awet dan bahannya adem. Siswa-siswa pada senang.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'client_name' => 'Komunitas Vespa Jogja',
                'content' => 'Pesan vest untuk anggota komunitas, hasilnya memuaskan. Ukuran presisi dan bordir logo rapi. Recommended!',
                'rating' => 4,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::create($data);
        }
    }
}
