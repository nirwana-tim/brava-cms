<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Category $category): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Category $category): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function restore(User $user, Category $category): bool
    {
        return false;
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }
}
