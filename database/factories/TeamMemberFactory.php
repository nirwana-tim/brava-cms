<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->jobTitle(),
            'bio' => fake()->paragraphs(2, true),
            'avatar' => 'avatars/'.fake()->uuid().'.jpg',
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'sort_order' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
