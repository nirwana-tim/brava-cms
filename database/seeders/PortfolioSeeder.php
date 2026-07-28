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
                'title' => 'TechCorp Corporate Website',
                'slug' => 'techcorp-corporate-website',
                'description' => 'A modern corporate website with integrated CMS.',
                'content' => '<p>Designed and developed a comprehensive corporate website for TechCorp, featuring a custom CMS, blog integration, and multilingual support.</p>',
                'client' => 'TechCorp Indonesia',
                'completed_at' => '2026-05-15',
                'is_active' => true,
            ],
            [
                'title' => 'ShopNow E-commerce Platform',
                'slug' => 'shopnow-ecommerce-platform',
                'description' => 'Full-featured e-commerce platform with payment integration.',
                'content' => '<p>Built a scalable e-commerce platform for ShopNow, handling 10,000+ daily transactions with seamless payment gateway integration.</p>',
                'client' => 'ShopNow Retail',
                'completed_at' => '2026-03-20',
                'is_active' => true,
            ],
            [
                'title' => 'FitTrack Mobile App',
                'slug' => 'fittrack-mobile-app',
                'description' => 'Cross-platform fitness tracking application.',
                'content' => '<p>Developed a cross-platform mobile application for fitness tracking with real-time workout logging, progress charts, and social features.</p>',
                'client' => 'FitTrack Inc.',
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
