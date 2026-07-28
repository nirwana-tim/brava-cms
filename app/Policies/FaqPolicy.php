<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Faq;
use App\Models\User;

class FaqPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Faq $faq): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Faq $faq): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Faq $faq): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function restore(User $user, Faq $faq): bool
    {
        return false;
    }

    public function forceDelete(User $user, Faq $faq): bool
    {
        return false;
    }
}
