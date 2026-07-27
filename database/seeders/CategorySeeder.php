<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'slug' => 'web-development', 'description' => 'Web development projects and services', 'type' => 'product', 'sort_order' => 1],
            ['name' => 'Mobile Apps', 'slug' => 'mobile-apps', 'description' => 'Mobile application development', 'type' => 'product', 'sort_order' => 2],
            ['name' => 'UI/UX Design', 'slug' => 'ui-ux-design', 'description' => 'User interface and experience design', 'type' => 'product', 'sort_order' => 3],
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Tech industry insights', 'type' => 'blog', 'sort_order' => 1],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Business and entrepreneurship', 'type' => 'blog', 'sort_order' => 2],
            ['name' => 'Design', 'slug' => 'design', 'description' => 'Design tips and inspiration', 'type' => 'blog', 'sort_order' => 3],
            ['name' => 'Website', 'slug' => 'website', 'description' => 'Company website projects', 'type' => 'portfolio', 'sort_order' => 1],
            ['name' => 'Mobile App', 'slug' => 'mobile-app', 'description' => 'Mobile app portfolio', 'type' => 'portfolio', 'sort_order' => 2],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
