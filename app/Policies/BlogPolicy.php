<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\User;

class BlogPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Blog $blog): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Blog $blog): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Blog $blog): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function restore(User $user, Blog $blog): bool
    {
        return false;
    }

    public function forceDelete(User $user, Blog $blog): bool
    {
        return false;
    }
}
