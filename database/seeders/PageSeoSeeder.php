<?php

namespace Database\Seeders;

use App\Models\PageSeo;
use Illuminate\Database\Seeder;

class PageSeoSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_key' => 'home',
                'meta_title' => [
                    'id' => 'Konveksi & Vendor Apparel Profesional',
                    'en' => 'Professional Custom Garment & Apparel Manufacturer',
                ],
                'meta_description' => [
                    'id' => 'Vendor konveksi apparel terpercaya untuk seragam kerja, PDH/PDL, jersey, dan kustom apparel perusahaan dengan standar mutu tinggi.',
                    'en' => 'Trusted custom garment manufacturer for corporate uniforms, work shirts, team jerseys, and custom apparel built to premium standards.',
                ],
                'robots_index' => true,
                'robots_follow' => true,
            ],
            [
                'page_key' => 'about',
                'meta_title' => [
                    'id' => 'Tentang Kami',
                    'en' => 'About Us',
                ],
                'meta_description' => [
                    'id' => 'Kenali BRAVA lebih dekat, dedikasi kami dalam menghadirkan standar apparel dan seragam berkualitas dengan ketelitian jahitan dan kenyamanan layanan.',
                    'en' => 'Learn more about BRAVA, our dedication to delivering high-quality apparel and uniforms with precision craftsmanship and reliable service.',
                ],
                'robots_index' => true,
                'robots_follow' => true,
            ],
            [
                'page_key' => 'services',
                'meta_title' => [
                    'id' => 'Layanan Konveksi & Seragam',
                    'en' => 'Custom Apparel & Uniform Services',
                ],
                'meta_description' => [
                    'id' => 'Solusi pembuatan seragam kustom dan apparel dari BRAVA: Vest PDH, Jersey Tim, Kemeja Kantor, hingga custom apparel dengan standar mutu terbaik.',
                    'en' => 'Custom uniform and garment solutions from BRAVA: PDH Vests, Team Jerseys, Corporate Shirts, and full custom apparel built to premium standards.',
                ],
                'robots_index' => true,
                'robots_follow' => true,
            ],
            [
                'page_key' => 'portfolio',
                'meta_title' => [
                    'id' => 'Portofolio & Hasil Produksi',
                    'en' => 'Portfolio & Product Showcase',
                ],
                'meta_description' => [
                    'id' => 'Temukan bukti nyata kualitas apparel dan seragam karya BRAVA yang telah dipercaya oleh berbagai perusahaan, kampus, dan organisasi.',
                    'en' => 'Explore BRAVA\'s finest uniform and custom apparel portfolio, trusted by corporations, universities, and organizations.',
                ],
                'robots_index' => true,
                'robots_follow' => true,
            ],
            [
                'page_key' => 'contact',
                'meta_title' => [
                    'id' => 'Hubungi Kami',
                    'en' => 'Contact Us',
                ],
                'meta_description' => [
                    'id' => 'Hubungi tim BRAVA untuk konsultasi kebutuhan seragam, penawaran harga, dan diskusi spesifikasi apparel Anda via WhatsApp atau Email.',
                    'en' => 'Contact the BRAVA team to discuss your uniform needs, request quotations, and explore apparel specifications via WhatsApp or Email.',
                ],
                'robots_index' => true,
                'robots_follow' => true,
            ],
            [
                'page_key' => 'blogs',
                'meta_title' => [
                    'id' => 'Blog & Wawasan Apparel',
                    'en' => 'Blog & Apparel Insights',
                ],
                'meta_description' => [
                    'id' => 'Baca artikel, tips pemilihan bahan seragam, tren industri garmen, dan wawasan apparel terbaru dari tim ahli BRAVA.',
                    'en' => 'Read articles, fabric selection guides, garment industry trends, and latest apparel insights from the BRAVA expert team.',
                ],
                'robots_index' => true,
                'robots_follow' => true,
            ],
            [
                'page_key' => 'promos',
                'meta_title' => [
                    'id' => 'Promo & Penawaran Khusus',
                    'en' => 'Promotions & Special Offers',
                ],
                'meta_description' => [
                    'id' => 'Dapatkan diskon dan penawaran spesial untuk pemesanan seragam kerja, rompi, dan apparel kustom di BRAVA.',
                    'en' => 'Discover exclusive discounts and special offers for custom uniforms, vests, and company apparel from BRAVA.',
                ],
                'robots_index' => true,
                'robots_follow' => true,
            ],
        ];

        foreach ($pages as $page) {
            PageSeo::updateOrCreate(
                ['page_key' => $page['page_key']],
                $page
            );
        }
    }
}
