<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    public function definition(): array
    {
        $ext = fake()->randomElement(['jpg', 'png', 'webp', 'svg']);

        return [
            'name' => fake()->word().'.'.$ext,
            'file_name' => fake()->word().'.'.$ext,
            'mime_type' => fake()->randomElement(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']),
            'size' => fake()->numberBetween(1024, 5242880),
            'disk' => 'public',
            'path' => 'uploads/'.fake()->uuid().'.'.$ext,
            'alt_text' => fake()->sentence(3),
            'sort_order' => fake()->numberBetween(0, 100),
            'collection' => fake()->randomElement(['default', 'gallery', 'featured']),
        ];
    }
}
