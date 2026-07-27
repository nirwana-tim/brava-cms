<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What services do you offer?',
                'answer' => 'We offer web development, mobile app development, UI/UX design, and digital consulting services.',
                'category' => 'General',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'How long does a typical project take?',
                'answer' => 'Project timelines vary depending on complexity. A standard corporate website typically takes 4-8 weeks, while larger e-commerce platforms may take 8-16 weeks.',
                'category' => 'General',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'What is your pricing model?',
                'answer' => 'We offer both fixed-price and hourly billing options. Each project is quoted based on specific requirements and scope.',
                'category' => 'Pricing',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Do you provide post-launch support?',
                'answer' => 'Yes, we offer maintenance and support packages to ensure your website or application continues running smoothly after launch.',
                'category' => 'Support',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $data) {
            Faq::create($data);
        }
    }
}
