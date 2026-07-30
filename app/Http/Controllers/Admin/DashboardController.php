<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Service;
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
            'users' => User::where('role', '!=', UserRole::SuperAdmin)->count(),
        ];

        $data = $analytics->getOverview(30);
        $isDummy = ! $analytics->isReady();

        return view('admin.dashboard', compact('stats', 'data', 'isDummy'));
    }
}
