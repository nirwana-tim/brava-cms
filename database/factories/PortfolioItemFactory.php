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
            'specifications' => [
                'id' => [
                    ['key' => 'Material', 'value' => fake()->randomElement(['Lacoste CVC', 'Drill', 'Taslan', 'Balotelli'])],
                    ['key' => 'Teknik Logo', 'value' => fake()->randomElement(['Bordir', 'Sablon', 'Polyflex'])],
                    ['key' => 'Warna', 'value' => fake()->colorName()],
                ],
            ],
            'features' => [
                'id' => [
                    'Nyaman digunakan seharian',
                    'Warna tahan lama',
                    'Ukuran presisi sesuai request',
                ],
            ],
            'client' => fake()->company(),
            'completed_at' => fake()->date(),
            'is_active' => true,
            'meta_title' => fake()->words(5, true),
            'meta_description' => fake()->sentence(),
            'robots_index' => true,
            'robots_follow' => true,
            'schema_type' => 'CreativeWork',
        ];
    }
}
