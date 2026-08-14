<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ContentAccessPolicy;

class MediaPolicy
{
    use ContentAccessPolicy;

    public function deleteQuickUpload(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }
}
