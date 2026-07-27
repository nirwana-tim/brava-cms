<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => 'Alexis Rivera',
                'position' => 'CEO & Founder',
                'bio' => 'Visionary leader with over 15 years of experience in the tech industry. Passionate about building solutions that make a difference.',
                'email' => 'alexis@brava.id',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Jordan Kim',
                'position' => 'Lead Developer',
                'bio' => 'Full-stack developer specializing in Laravel and modern JavaScript frameworks. Open source contributor and tech blogger.',
                'email' => 'jordan@brava.id',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Samantha Lee',
                'position' => 'UI/UX Designer',
                'bio' => 'Creative designer with a keen eye for detail. Specializes in creating intuitive and beautiful user experiences.',
                'email' => 'samantha@brava.id',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($members as $data) {
            TeamMember::create($data);
        }
    }
}
