<x-admin.layouts.app>
    <x-slot name="title">{{ __('Blog Posts') }}</x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Blog Posts</h2>
            <a href="{{ route('admin.blogs.create') }}">
                <x-primary-button>{{ __('New Blog Post') }}</x-primary-button>
            </a>
        </div>

        <div class="p-6">
            <div class="admin-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $blog)
                            <tr>
                                <td class="font-medium" style="color: var(--table-text)">{{ $blog->title }}</td>
                                <td style="color: var(--table-text-muted)">{{ $blog->author?->name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if ($blog->status->value === 'published') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                        @elseif ($blog->status->value === 'draft') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                                        {{ ucfirst($blog->status->value) }}
                                    </span>
                                </td>
                                <td>
                                    @foreach ($blog->categories as $cat)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 mr-1">{{ $cat->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors text-xs font-medium">Edit</a>
                                        <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 dark:bg-red-900/50 text-red-700 dark:text-red-300 rounded-md hover:bg-red-100 dark:hover:bg-red-900 transition-colors text-xs font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    <p>No blog posts found.</p>
                                    <a href="{{ route('admin.blogs.create') }}">Create your first blog post</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($blogs->hasPages())
                <div class="mt-4">
                    {{ $blogs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
