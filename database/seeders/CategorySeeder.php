<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology', 'type' => 'blog', 'description' => 'Tech industry insights'],
            ['name' => 'Business', 'slug' => 'business', 'type' => 'blog', 'description' => 'Business and entrepreneurship'],
            ['name' => 'Design', 'slug' => 'design', 'type' => 'blog', 'description' => 'Design tips and inspiration'],
            ['name' => 'Web', 'slug' => 'web', 'type' => 'portfolio', 'description' => 'Web development projects'],
            ['name' => 'Mobile', 'slug' => 'mobile', 'type' => 'portfolio', 'description' => 'Mobile app projects'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
