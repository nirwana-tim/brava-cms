<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => 'Alexis Rivera',
                'position' => 'CEO & Founder',
                'email' => 'alexis@brava.id',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Jordan Kim',
                'position' => 'Lead Developer',
                'email' => 'jordan@brava.id',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Samantha Lee',
                'position' => 'UI/UX Designer',
                'email' => 'samantha@brava.id',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($members as $data) {
            $team = TeamMember::create($data);

            if ($team->email) {
                $user = User::create([
                    'name' => $team->name,
                    'email' => $team->email,
                    'password' => bcrypt('password'),
                    'role' => UserRole::Admin,
                    'position' => $team->position,
                ]);

                $team->user()->associate($user)->save();
            }
        }
    }
}
