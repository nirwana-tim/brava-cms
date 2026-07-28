<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_name' => fake()->company(),
            'content' => fake()->paragraphs(2, true),
            'rating' => fake()->numberBetween(1, 5),
            'avatar' => 'avatars/'.fake()->uuid().'.jpg',
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
