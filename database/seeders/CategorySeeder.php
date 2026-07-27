<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Tech industry insights', 'sort_order' => 1],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Business and entrepreneurship', 'sort_order' => 2],
            ['name' => 'Design', 'slug' => 'design', 'description' => 'Design tips and inspiration', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
