<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@brava.id',
            'role' => UserRole::SuperAdmin,
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@brava.id',
            'role' => UserRole::Admin,
        ]);

        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            BlogSeeder::class,
            PortfolioSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            TeamSeeder::class,
        ]);
    }
}
