<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Service $service): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Service $service): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function restore(User $user, Service $service): bool
    {
        return false;
    }

    public function forceDelete(User $user, Service $service): bool
    {
        return false;
    }
}
