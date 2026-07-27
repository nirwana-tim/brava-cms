<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('type', 'product')->pluck('id');

        $products = [
            [
                'title' => 'Corporate Website Package',
                'slug' => 'corporate-website-package',
                'description' => 'Complete corporate website with CMS, SEO optimization, and responsive design.',
                'content' => '<p>Our Corporate Website Package includes everything you need to establish a strong online presence. From responsive design to SEO optimization, we cover all aspects of professional web development.</p><h3>Features</h3><ul><li>Responsive design</li><li>Content Management System</li><li>SEO optimization</li><li>Contact forms</li><li>Google Analytics integration</li></ul>',
                'price' => 15000000,
                'is_featured' => true,
                'is_active' => true,
                'published_at' => now(),
                'meta_title' => 'Corporate Website Package - Professional Web Development',
                'meta_description' => 'Complete corporate website package with CMS, SEO, and responsive design. Starting from Rp 15.000.000.',
                'schema_type' => 'Product',
            ],
            [
                'title' => 'E-commerce Solution',
                'slug' => 'ecommerce-solution',
                'description' => 'Full-featured e-commerce platform with payment gateway integration.',
                'content' => '<p>Launch your online store with our comprehensive e-commerce solution. Built for scalability and performance.</p><h3>Features</h3><ul><li>Shopping cart</li><li>Payment gateway integration</li><li>Product management</li><li>Order tracking</li><li>Inventory management</li></ul>',
                'price' => 25000000,
                'is_featured' => true,
                'is_active' => true,
                'published_at' => now(),
                'meta_title' => 'E-commerce Solution - Online Store Platform',
                'meta_description' => 'Full-featured e-commerce platform with payment integration and product management.',
                'schema_type' => 'Product',
            ],
            [
                'title' => 'Mobile App Development',
                'slug' => 'mobile-app-development',
                'description' => 'Cross-platform mobile application development for iOS and Android.',
                'content' => '<p>We build high-performance mobile applications using modern frameworks that work seamlessly on both iOS and Android platforms.</p>',
                'price' => 35000000,
                'is_featured' => false,
                'is_active' => true,
                'published_at' => now(),
                'meta_title' => 'Mobile App Development - iOS & Android',
                'meta_description' => 'Cross-platform mobile app development services for iOS and Android.',
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create($data);
            $product->categories()->attach($categories->random(min(2, $categories->count())));
        }
    }
}
