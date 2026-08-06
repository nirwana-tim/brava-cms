<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;
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
            'avatar' => 'avatars/'.fake()->uuid().'.jpg',
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'sort_order' => fake()->unique()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (TeamMember $member) {
            if ($member->email && ! $member->user_id) {
                $user = User::factory()->create([
                    'name' => $member->name,
                    'email' => $member->email,
                    'role' => UserRole::Admin,
                    'position' => $member->position,
                ]);

                $member->user()->associate($user)->save();
            }
        });
    }
}
