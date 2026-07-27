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
                'title' => 'Corporate Website Package',
                'slug' => 'corporate-website-package',
                'description' => 'Complete corporate website with CMS, SEO optimization, and responsive design.',
                'content' => '<p>Our Corporate Website Package includes everything you need to establish a strong online presence. From responsive design to SEO optimization, we cover all aspects of professional web development.</p><h3>Features</h3><ul><li>Responsive design</li><li>Content Management System</li><li>SEO optimization</li><li>Contact forms</li><li>Google Analytics integration</li></ul>',
                'is_active' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'E-commerce Solution',
                'slug' => 'ecommerce-solution',
                'description' => 'Full-featured e-commerce platform with payment gateway integration.',
                'content' => '<p>Launch your online store with our comprehensive e-commerce solution. Built for scalability and performance.</p>',
                'is_active' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Mobile App Development',
                'slug' => 'mobile-app-development',
                'description' => 'Cross-platform mobile application development for iOS and Android.',
                'content' => '<p>We build high-performance mobile applications using modern frameworks that work seamlessly on both iOS and Android platforms.</p>',
                'is_active' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($services as $data) {
            Service::create($data);
        }
    }
}
