<x-admin.layouts.app>
    <x-slot name="title">{{ __('Services') }}</x-slot>

    <div class="card">
        <div class="card-header flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">Services</h2>
            <a href="{{ route('admin.services.create') }}">
                <x-primary-button>{{ __('New Service') }}</x-primary-button>
            </a>
        </div>

        <div class="border-t" style="border-color: var(--card-border);">
            <div class="px-6 py-3">
                <form method="GET" action="{{ route('admin.services.index') }}" class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-2">
                        <select name="status" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border" style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..." class="form-input text-xs py-1.5 px-3 rounded-md border w-56" style="border-color: var(--card-border); background: var(--input-bg); color: var(--input-text);" />
                        <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Cari</button>
                        @if (request('search') || request('status'))
                            <a href="{{ route('admin.services.index') }}" class="btn-secondary text-xs py-1.5 px-2.5" title="Reset Filter">Reset</a>
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
                                <th>Title</th>
                                <th>Sort</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service)
                            <tr>
                                <td class="font-medium" style="color: var(--table-text)">{{ $service->title }}</td>
                                <td class="text-sm" style="color: var(--muted-text)">{{ $service->sort_order ?? '0' }}</td>
                                <td>
                                    @if ($service->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.services.show', $service) }}" class="inline-flex items-center px-3 py-1.5 btn-show rounded-md text-xs font-medium">Show</a>
                                        <a href="{{ route('admin.services.edit', $service) }}" class="inline-flex items-center px-3 py-1.5 btn-edit rounded-md text-xs font-medium">Edit</a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 btn-delete rounded-md text-xs font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    <p>No services found.</p>
                                    <a href="{{ route('admin.services.create') }}">Create your first service</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($services->hasPages())
                <div class="mt-4">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
