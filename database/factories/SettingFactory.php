<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->word(),
            'value' => fake()->sentence(),
            'group' => fake()->randomElement(['general', 'seo', 'social', 'analytics', 'mail']),
            'type' => fake()->randomElement(['text', 'textarea', 'boolean', 'image']),
        ];
    }
}
