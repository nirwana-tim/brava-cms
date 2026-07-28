<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Blog>
 */
class BlogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'title' => fake()->unique()->sentence(),
            'slug' => fake()->unique()->slug(),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'featured_image' => 'uploads/'.fake()->uuid().'.jpg',
            'published_at' => now(),
            'is_featured' => fake()->boolean(20),
            'status' => PostStatus::Published,
            'meta_title' => fake()->sentence(2),
            'meta_description' => fake()->sentence(),
            'meta_keywords' => implode(', ', fake()->words(5)),
            'og_image' => 'uploads/'.fake()->uuid().'.jpg',
            'robots_index' => true,
            'robots_follow' => true,
            'schema_type' => 'BlogPosting',
        ];
    }

    public function draft(): static
    {
        return $this->state([
            'status' => PostStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state([
            'status' => PostStatus::Published,
            'published_at' => now(),
        ]);
    }
}
