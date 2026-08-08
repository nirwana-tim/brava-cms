<x-admin.layouts.app>
    <x-slot name="title">{{ __('Portfolio') }}</x-slot>

    <div class="card">
        <div class="card-header flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">Portfolio</h2>
            <a href="{{ route('admin.portfolio.create') }}">
                <x-primary-button>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('New Portfolio Item') }}
                </x-primary-button>
            </a>
        </div>

        <div class="border-t" style="border-color: var(--card-border);">
            <div class="px-6 py-3">
                <form method="GET" action="{{ route('admin.portfolio.index') }}" class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-2">
                        <select name="category" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border" style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string) request('category') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <select name="service" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border" style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Layanan</option>
                            @foreach ($services as $srv)
                                <option value="{{ $srv->id }}" {{ (string) request('service') === (string) $srv->id ? 'selected' : '' }}>{{ $srv->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari portfolio..." class="form-input text-xs py-1.5 px-3 rounded-md border w-56" style="border-color: var(--card-border); background: var(--input-bg); color: var(--input-text);" />
                        <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Cari</button>
                        @if (request('search') || request('category') || request('service'))
                            <a href="{{ route('admin.portfolio.index') }}" class="btn-secondary text-xs py-1.5 px-2.5" title="Reset Filter">Reset</a>
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
                            <th>Categories</th>
                            <th>Service</th>
                            <th>Client</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td class="font-medium" style="color: var(--table-text)">{{ $item->title }}</td>
                                <td>
                                    @if ($item->categories->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($item->categories as $category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium badge-default">{{ $category->name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color: var(--table-text-muted)">-</span>
                                    @endif
                                </td>
                                <td style="color: var(--table-text-muted)">{{ $item->service?->title ?? '-' }}</td>
                                <td style="color: var(--table-text-muted)">{{ $item->client ?? '-' }}</td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <x-admin.action-show :href="route('admin.portfolio.show', $item)" />
                                        <x-admin.action-edit :href="route('admin.portfolio.edit', $item)" />
                                        <x-admin.action-delete :action="route('admin.portfolio.destroy', $item)" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <p>No portfolio items found.</p>
                                    <a href="{{ route('admin.portfolio.create') }}">Create your first portfolio item</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($items->hasPages())
                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
