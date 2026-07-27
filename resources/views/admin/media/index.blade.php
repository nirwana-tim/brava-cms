<x-admin.layouts.app>
    <x-slot name="title">{{ __('Media') }}</x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Media</h2>
            <a href="{{ route('admin.media.create') }}">
                <x-primary-button>{{ __('Upload Media') }}</x-primary-button>
            </a>
        </div>

        <div class="p-6">
            <div class="admin-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($media as $item)
                            <tr>
                                <td class="font-medium" style="color: var(--table-text)">{{ $item->name }}</td>
                                <td style="color: var(--table-text-muted)">{{ $item->mime_type }}</td>
                                <td style="color: var(--table-text-muted)">{{ number_format($item->size / 1024, 1) }} KB</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.media.edit', $item) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors text-xs font-medium">Edit</a>
                                        <form action="{{ route('admin.media.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 dark:bg-red-900/50 text-red-700 dark:text-red-300 rounded-md hover:bg-red-100 dark:hover:bg-red-900 transition-colors text-xs font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p>No media found.</p>
                                    <a href="{{ route('admin.media.create') }}">Upload your first file</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($media->hasPages())
                <div class="mt-4">
                    {{ $media->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
