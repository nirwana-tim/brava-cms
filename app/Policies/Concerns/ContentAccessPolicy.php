<?php

namespace App\Policies\Concerns;

use App\Enums\UserRole;
use App\Models\User;

trait ContentAccessPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isStaffOrAdmin();
    }

    public function view(User $user, mixed $model): bool
    {
        return $user->isStaffOrAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isStaffOrAdmin();
    }

    public function update(User $user, mixed $model): bool
    {
        return $user->isStaffOrAdmin();
    }

    public function delete(User $user, mixed $model): bool
    {
        return $user->isStaffOrAdmin();
    }

    public function restore(User $user, mixed $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, mixed $model): bool
    {
        return false;
    }
}
