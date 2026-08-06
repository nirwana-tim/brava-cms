<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamRequest;
use App\Http\Requests\Admin\UpdateTeamRequest;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct(private readonly MediaService $mediaService)
    {
        $this->authorizeResource(TeamMember::class, 'team');
    }

    public function index(Request $request): View
    {
        $query = TeamMember::with('user')->orderBy('sort_order')->latest();

        if (! auth()->user()->isSuperAdmin()) {
            $query->whereDoesntHave('user', fn ($q) => $q->where('role', UserRole::SuperAdmin));
        } elseif ($role = $request->input('role')) {
            $query->whereHas('user', fn ($q) => $q->where('role', $role));
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name->id', 'like', "%{$search}%")
                    ->orWhere('name->en', 'like', "%{$search}%")
                    ->orWhere('position->id', 'like', "%{$search}%")
                    ->orWhere('position->en', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('email', 'like', "%{$search}%"));
            });
        }

        $members = $query->paginate(15)->withQueryString();

        return view('admin.team.index', compact('members'));
    }

    public function create(): View
    {
        return view('admin.team.create');
    }

    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (($validated['sort_order'] ?? null) === null) {
            $validated['sort_order'] = (int) TeamMember::max('sort_order') + 1;
        }

        $team = TeamMember::create($validated);

        if (($validated['create_user_account'] ?? false) && $team->email) {
            $user = User::create([
                'name' => $validated['name']['id'] ?? $team->email,
                'email' => $team->email,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? (auth()->user()->isSuperAdmin() ? UserRole::Admin : UserRole::Staff),
                'position' => $validated['position']['id'] ?? null,
                'avatar' => $team->avatar,
                'email_verified_at' => now(),
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

    public function update(UpdateTeamRequest $request, TeamMember $team): RedirectResponse
    {
        $validated = $request->validated();

        $previousAvatar = $team->avatar;

        $wantedActive = array_key_exists('is_active', $validated)
            ? (bool) $validated['is_active']
            : (bool) $team->is_active;

        if ($team->user_id === auth()->id() && ! $wantedActive) {
            return redirect()->route('admin.team.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $team->update($validated);

        if ($previousAvatar !== $team->avatar) {
            $this->mediaService->deleteStoredUpload($previousAvatar);
        }

        if ($team->user) {
            $role = $team->user_id === auth()->id()
                ? $team->user->role
                : ($validated['role'] ?? $team->user->role);

            $team->user->update([
                'name' => $validated['name']['id'] ?? $team->user->name,
                'email' => $validated['email'] ?? $team->user->email,
                'position' => $validated['position']['id'] ?? null,
                'avatar' => $team->avatar,
                'role' => $role,
                'is_active' => $wantedActive,
            ]);
        }

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member updated successfully.');
    }

    public function resetPassword(TeamMember $team): View
    {
        $this->authorize('update', $team);

        return view('admin.team.reset-password', compact('team'));
    }

    public function updatePassword(Request $request, TeamMember $team): RedirectResponse
    {
        $this->authorize('update', $team);

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
        if ($team->user_id && $team->user_id === auth()->id()) {
            return redirect()->route('admin.team.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if ($team->user) {
            $team->user->update(['is_active' => false]);
        }

        $team->delete();

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member deleted successfully.');
    }
}
