<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PortfolioItem;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('type', 'portfolio')->pluck('id');

        $items = [
            [
                'title' => 'TechCorp Corporate Website',
                'slug' => 'techcorp-corporate-website',
                'description' => 'A modern corporate website with integrated CMS.',
                'content' => '<p>Designed and developed a comprehensive corporate website for TechCorp, featuring a custom CMS, blog integration, and multilingual support.</p>',
                'client' => 'TechCorp Indonesia',
                'project_url' => 'https://techcorp.example.com',
                'completed_at' => '2026-05-15',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'ShopNow E-commerce Platform',
                'slug' => 'shopnow-ecommerce-platform',
                'description' => 'Full-featured e-commerce platform with payment integration.',
                'content' => '<p>Built a scalable e-commerce platform for ShopNow, handling 10,000+ daily transactions with seamless payment gateway integration.</p>',
                'client' => 'ShopNow Retail',
                'project_url' => 'https://shopnow.example.com',
                'completed_at' => '2026-03-20',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'FitTrack Mobile App',
                'slug' => 'fittrack-mobile-app',
                'description' => 'Cross-platform fitness tracking application.',
                'content' => '<p>Developed a cross-platform mobile application for fitness tracking with real-time workout logging, progress charts, and social features.</p>',
                'client' => 'FitTrack Inc.',
                'project_url' => null,
                'completed_at' => '2026-01-10',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($items as $data) {
            $item = PortfolioItem::create($data);
            $item->categories()->attach($categories->random(min(2, $categories->count())));
        }
    }
}
