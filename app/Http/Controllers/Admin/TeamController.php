<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $members = TeamMember::latest()->paginate(15);

        return view('admin.team.index', compact('members'));
    }

    public function create(): View
    {
        return view('admin.team.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $team = TeamMember::create($validated);

        if ($team->email) {
            $user = User::create([
                'name' => $team->name,
                'email' => $team->email,
                'password' => Hash::make($validated['password']),
                'role' => UserRole::Admin,
                'position' => $team->position,
            ]);

            $team->user()->associate($user)->save();
        }

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member created successfully.');
    }

    public function show(TeamMember $team): View
    {
        return view('admin.team.show', compact('team'));
    }

    public function edit(TeamMember $team): View
    {
        return view('admin.team.edit', compact('team'));
    }

    public function update(Request $request, TeamMember $team): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $team->update($validated);

        if ($team->user) {
            $team->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? $team->user->email,
                'position' => $validated['position'],
            ]);
        }

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member updated successfully.');
    }

    public function resetPassword(TeamMember $team): View
    {
        return view('admin.team.reset-password', compact('team'));
    }

    public function updatePassword(Request $request, TeamMember $team): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($team->user) {
            $team->user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('admin.team.index')
            ->with('success', 'Password reset successfully.');
    }

    public function destroy(TeamMember $team): RedirectResponse
    {
        $team->delete();

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member deleted successfully.');
    }
}
