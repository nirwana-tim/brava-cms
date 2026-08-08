<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminEmail = env('SUPERADMIN_EMAIL', 'superadmin@brava.id');
        $superAdminPassword = env('SUPERADMIN_PASSWORD') ?: Str::random(24);
        $adminEmail = env('ADMIN_EMAIL', 'admin@brava.id');
        $adminPassword = env('ADMIN_PASSWORD') ?: Str::random(24);

        $this->command->warn("Super Admin ({$superAdminEmail}) created with password: {$superAdminPassword}");
        $this->command->warn("Admin ({$adminEmail}) created with password: {$adminPassword}");

        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => $superAdminEmail,
            'role' => UserRole::SuperAdmin,
            'position' => 'Super Administrator',
            'password' => $superAdminPassword,
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
            'email' => $adminEmail,
            'role' => UserRole::Admin,
            'position' => 'Administrator',
            'password' => $adminPassword,
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
        ]);
    }
}
