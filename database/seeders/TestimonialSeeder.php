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
                'client_name' => 'John Doe',
                'client_position' => 'CEO',
                'company' => 'TechCorp',
                'content' => 'Working with this team was an absolute pleasure. They delivered our corporate website on time and exceeded our expectations.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'client_name' => 'Sarah Johnson',
                'client_position' => 'Marketing Director',
                'company' => 'ShopNow Retail',
                'content' => 'Our e-commerce platform has never performed better. The team understood our requirements perfectly and delivered an exceptional solution.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'client_name' => 'Michael Chen',
                'client_position' => 'CTO',
                'company' => 'FitTrack Inc.',
                'content' => 'The mobile app they built for us is outstanding. Clean code, great UI, and excellent performance across both iOS and Android.',
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
