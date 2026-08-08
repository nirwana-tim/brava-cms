<x-admin.layouts.app>
    <x-slot name="title">Recycle Bin</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--heading-text)">Recycle Bin (Trash)</h1>
                <p class="text-sm mt-1" style="color: var(--muted-text)">
                    Manage soft-deleted items. Only Super Admin can restore or permanently clear data.
                </p>
            </div>

            @if ($items->total() > 0)
                <x-admin.confirm-dialog :action="route('admin.trash.empty', ['type' => $currentType])"
                    title="Empty {{ $modules[$currentType]['label'] }} Trash"
                    :message="'WARNING: This will permanently delete ALL trashed ' . $modules[$currentType]['label'] . ' from the database. This action cannot be undone. Are you sure?'"
                    confirm-label="Empty Trash"
                    icon="trash"
                    confirm-icon="alert"
                    button-class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-red-600 hover:bg-red-700 text-white shadow-sm transition cursor-pointer" />
            @endif
        </div>

        {{-- Module Tabs --}}
        <div class="flex flex-wrap gap-2 border-b pb-3" style="border-color: var(--card-header-border)">
            @foreach ($modules as $key => $config)
                @php
                    $isActive = $currentType === $key;
                    $count = $counts[$key] ?? 0;
                @endphp
                <a href="{{ route('admin.trash.index', ['type' => $key]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition {{ $isActive ? 'bg-blue-600 text-white shadow-sm' : 'hover:opacity-80' }}"
                    style="{{ ! $isActive ? 'background-color: var(--card-bg); color: var(--label-text); border: 1px solid var(--table-border)' : '' }}">
                    <span>{{ $config['label'] }}</span>
                    @if ($count > 0)
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $isActive ? 'bg-blue-500 text-white' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">
                            {{ $count }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Trashed Items Table --}}
        <div class="card overflow-hidden">
            @if ($items->isEmpty())
                <div class="admin-table-empty">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <p class="text-base font-medium" style="color: var(--heading-text)">No trashed {{ strtolower($modules[$currentType]['label']) }}</p>
                    <p class="text-sm mt-1" style="color: var(--muted-text)">When items are deleted by administrators, they will appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b text-xs uppercase" style="border-color: var(--table-border); color: var(--muted-text); background-color: var(--table-header-bg)">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Title / Name</th>
                                <th class="py-3 px-4 font-semibold">Deleted At</th>
                                <th class="py-3 px-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="divide-color: var(--table-border)">
                            @foreach ($items as $item)
                                @php
                                    $titleField = $modules[$currentType]['title_field'];
                                    $title = $item->{$titleField} ?? 'Untitled';
                                @endphp
                                <tr class="hover:opacity-90 transition">
                                    <td class="py-3 px-4 text-sm font-mono" style="color: var(--muted-text)">#{{ $item->id }}</td>
                                    <td class="py-3 px-4 text-sm font-medium" style="color: var(--heading-text)">
                                        {{ $title }}
                                    </td>
                                    <td class="py-3 px-4 text-sm" style="color: var(--muted-text)">
                                        {{ $item->deleted_at ? $item->deleted_at->format('d M Y, H:i') : '-' }}
                                        @if ($item->deleted_at)
                                            <span class="text-xs opacity-75">({{ $item->deleted_at->diffForHumans() }})</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right space-x-2">
                                        {{-- Restore Button --}}
                                        <form action="{{ route('admin.trash.restore', ['type' => $currentType, 'id' => $item->id]) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                Restore
                                            </button>
                                        </form>

                                        {{-- Permanent Delete Button --}}
                                        <x-admin.confirm-dialog :action="route('admin.trash.force-delete', ['type' => $currentType, 'id' => $item->id])"
                                            title="Delete Permanently"
                                            message="PERMANENT DELETE: Are you sure you want to permanently destroy this item?"
                                            confirm-label="Delete Permanently"
                                            confirm-icon="alert"
                                            button-class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-semibold bg-red-600 hover:bg-red-700 text-white transition cursor-pointer" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($items->hasPages())
                    <div class="p-4 border-t" style="border-color: var(--table-border)">
                        {{ $items->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-admin.layouts.app>
