<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(AnalyticsService $analytics): View
    {
        $stats = [
            'services' => Service::count(),
            'blogs' => Blog::count(),
            'categories' => Category::count(),
            'users' => User::query()
                ->where('role', '!=', UserRole::SuperAdmin)
                ->whereIn('id', TeamMember::query()->whereNotNull('user_id')->pluck('user_id'))
                ->count(),
        ];

        $days = (int) request()->query('days', 30);
        if (! in_array($days, [7, 30, 90, 365], true)) {
            $days = 30;
        }

        $data = $analytics->getOverview($days);
        $isDummy = ! $analytics->isReady();

        return view('admin.dashboard', compact('stats', 'data', 'isDummy', 'days'));
    }
}
