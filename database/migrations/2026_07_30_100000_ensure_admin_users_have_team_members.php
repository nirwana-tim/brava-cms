<?php

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $users = User::whereDoesntHave('teamMember')->get();

        foreach ($users as $user) {
            $position = $user->role === UserRole::SuperAdmin ? 'Super Administrator' : ($user->position ?: 'Administrator');

            TeamMember::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'position' => $position,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'is_active' => true,
            ]);
        }
    }

    public function down(): void
    {
        // No action required on rollback
    }
};
