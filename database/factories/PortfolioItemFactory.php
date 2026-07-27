<?php

namespace Database\Factories;

use App\Models\PortfolioItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PortfolioItem>
 */
class PortfolioItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(4, true),
            'client' => fake()->company(),
            'project_url' => fake()->url(),
            'completed_at' => fake()->date(),
            'sort_order' => fake()->numberBetween(0, 100),
            'is_active' => true,
            'meta_title' => fake()->sentence(2),
            'meta_description' => fake()->sentence(),
            'meta_keywords' => implode(', ', fake()->words(5)),
            'og_title' => fake()->sentence(2),
            'og_description' => fake()->sentence(),
            'og_image' => 'uploads/'.fake()->uuid().'.jpg',
            'canonical_url' => fake()->url(),
            'robots_index' => true,
            'robots_follow' => true,
            'schema_type' => 'CreativeWork',
        ];
    }
}
