<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'price' => fake()->randomFloat(2, 9.99, 9999.99),
            'is_featured' => fake()->boolean(20),
            'is_active' => true,
            'published_at' => now(),
            'meta_title' => fake()->sentence(2),
            'meta_description' => fake()->sentence(),
            'meta_keywords' => implode(', ', fake()->words(5)),
            'og_title' => fake()->sentence(2),
            'og_description' => fake()->sentence(),
            'og_image' => 'uploads/'.fake()->uuid().'.jpg',
            'canonical_url' => fake()->url(),
            'robots_index' => true,
            'robots_follow' => true,
            'schema_type' => 'Product',
        ];
    }
}
