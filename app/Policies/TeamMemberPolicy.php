<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;

class TeamMemberPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, TeamMember $teamMember): bool
    {
        if ($teamMember->user?->isSuperAdmin()) {
            return $user->isSuperAdmin();
        }

        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, TeamMember $teamMember): bool
    {
        if ($user->role !== UserRole::Admin) {
            return false;
        }

        return $teamMember->user === null
            || $teamMember->user_id === $user->id
            || $teamMember->user->role === UserRole::Staff;
    }

    public function delete(User $user, TeamMember $teamMember): bool
    {
        if ($user->role !== UserRole::Admin) {
            return false;
        }

        return $teamMember->user === null
            || $teamMember->user_id === $user->id
            || $teamMember->user->role === UserRole::Staff;
    }

    public function restore(User $user, TeamMember $teamMember): bool
    {
        return false;
    }

    public function forceDelete(User $user, TeamMember $teamMember): bool
    {
        return false;
    }
}
