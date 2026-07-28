<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Media $media): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Media $media): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function restore(User $user, Media $media): bool
    {
        return false;
    }

    public function forceDelete(User $user, Media $media): bool
    {
        return false;
    }
}
