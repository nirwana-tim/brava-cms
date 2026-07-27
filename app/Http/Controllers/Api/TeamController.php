<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TeamMemberResource;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;

class TeamController extends ApiController
{
    public function __construct(
        private readonly TeamService $service
    ) {}

    public function index(): JsonResponse
    {
        $members = $this->service->all();

        return $this->success(TeamMemberResource::collection($members));
    }
}
