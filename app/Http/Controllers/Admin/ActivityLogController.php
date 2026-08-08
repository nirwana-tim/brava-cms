<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isSuperAdmin(), 403, 'Only Super Admin can view activity logs.');

        $logs = ActivityLog::with(['user:id', 'loggable'])
            ->when($request->filled('event'), fn ($query) => $query->where('event', $request->query('event')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->query('q');

                return $query->where('description', 'like', "%{$search}%");
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $events = [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted (Trash)',
            'restored' => 'Restored',
            'force_deleted' => 'Force Deleted',
        ];

        return view('admin.activity-logs.index', compact('logs', 'events'));
    }
}
