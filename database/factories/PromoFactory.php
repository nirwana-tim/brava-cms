<?php

namespace Database\Factories;

use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Promo>
 */
class PromoFactory extends Factory
{
    protected $model = Promo::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'badge_text' => $this->faker->randomElement(['PROMO TERBATAS', 'SPECIAL OFFER', 'BEST DEAL', null]),
            'discount_info' => $this->faker->randomElement(['40%', '20%', 'Rp 500.000', '15%']),
            'description' => $this->faker->paragraph(3),
            'image' => null,
            'image_alt' => $title,
            'valid_from' => now()->subDays(5),
            'valid_until' => now()->addDays(30),
            'wa_template' => "Halo Brava, saya ingin klaim promo: {$title}",
            'is_highlighted' => false,
            'is_active' => true,
        ];
    }

    public function highlighted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_highlighted' => true,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_until' => now()->subDays(1),
            'is_highlighted' => false,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'is_highlighted' => false,
        ]);
    }

    public function comingSoon(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_from' => now()->addDays(7),
            'valid_until' => now()->addDays(37),
            'is_highlighted' => false,
        ]);
    }
}
