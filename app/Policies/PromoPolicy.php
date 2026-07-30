<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Promo;
use App\Models\User;

class PromoPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Promo $promo): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Promo $promo): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Promo $promo): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function restore(User $user, Promo $promo): bool
    {
        return false;
    }

    public function forceDelete(User $user, Promo $promo): bool
    {
        return false;
    }
}
