<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\TeamMember;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TeamService
{
    public function __construct(private readonly TeamMember $model) {}

    public function all(): Collection
    {
        return Cache::store('api')->flexible('team.all', [3600, 7200], function () {
            return $this->model
                ->active()
                ->whereDoesntHave('user', fn ($q) => $q->where('role', UserRole::SuperAdmin))
                ->orderBy('sort_order')
                ->get();
        });
    }
}
