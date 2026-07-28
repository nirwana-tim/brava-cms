<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Testimonial;
use App\Models\User;

class TestimonialPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Testimonial $testimonial): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Testimonial $testimonial): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Testimonial $testimonial): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function restore(User $user, Testimonial $testimonial): bool
    {
        return false;
    }

    public function forceDelete(User $user, Testimonial $testimonial): bool
    {
        return false;
    }
}
