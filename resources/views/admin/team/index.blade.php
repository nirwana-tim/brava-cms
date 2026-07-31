<x-admin.layouts.app>
    <x-slot name="title">{{ __('Team') }}</x-slot>

    <div class="card">
        <div class="card-header flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">Team</h2>
            <a href="{{ route('admin.team.create') }}">
                <x-primary-button>{{ __('New Team Member') }}</x-primary-button>
            </a>
        </div>

        <div class="border-t" style="border-color: var(--card-border);">
            <div class="px-6 py-3">
                <form method="GET" action="{{ route('admin.team.index') }}" class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-2">
                        @if (auth()->user()->isSuperAdmin())
                            <select name="role" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border" style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tim..." class="form-input text-xs py-1.5 px-3 rounded-md border w-56" style="border-color: var(--card-border); background: var(--input-bg); color: var(--input-text);" />
                        <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Cari</button>
                        @if (request('search') || request('role'))
                            <a href="{{ route('admin.team.index') }}" class="btn-secondary text-xs py-1.5 px-2.5" title="Reset Filter">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
        <div class="admin-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Email</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($members as $member)
                            <tr>
                                <td class="font-medium" style="color: var(--table-text)">{{ $member->name }}</td>
                                <td style="color: var(--table-text-muted)">{{ $member->position ?? '-' }}</td>
                                <td style="color: var(--table-text-muted)">{{ $member->email ?? '-' }}</td>
                                <td>
                                    @if ($member->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.team.show', $member) }}" class="inline-flex items-center px-3 py-1.5 btn-show rounded-md text-xs font-medium">Show</a>
                                        @can('update', $member)
                                            <a href="{{ route('admin.team.edit', $member) }}" class="inline-flex items-center px-3 py-1.5 btn-edit rounded-md text-xs font-medium">Edit</a>
                                        @endcan
                                        @can('delete', $member)
                                            <form action="{{ route('admin.team.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 btn-delete rounded-md text-xs font-medium">Delete</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                                    <p>No team members found.</p>
                                    <a href="{{ route('admin.team.create') }}">Add your first team member</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($members->hasPages())
                <div class="mt-4">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
