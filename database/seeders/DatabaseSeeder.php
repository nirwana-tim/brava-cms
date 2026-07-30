<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@brava.id',
            'role' => UserRole::SuperAdmin,
            'position' => 'Super Administrator',
        ]);

        TeamMember::create([
            'user_id' => $superAdmin->id,
            'name' => $superAdmin->name,
            'position' => 'Super Administrator',
            'email' => $superAdmin->email,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@brava.id',
            'role' => UserRole::Admin,
            'position' => 'Administrator',
        ]);

        TeamMember::create([
            'user_id' => $admin->id,
            'name' => $admin->name,
            'position' => 'Administrator',
            'email' => $admin->email,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->call([
            SettingSeeder::class,
            ContactSettingSeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            BlogSeeder::class,
            PortfolioSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            TeamSeeder::class,
            PromoSeeder::class,
        ]);
    }
}
