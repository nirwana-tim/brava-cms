<x-admin.layouts.app>
    <x-slot name="title">{{ __('FAQs') }}</x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">FAQs</h2>
            <a href="{{ route('admin.faqs.create') }}">
                <x-primary-button>{{ __('New FAQ') }}</x-primary-button>
            </a>
        </div>

        <div class="p-6">
            <div class="admin-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Category</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faqs as $faq)
                            <tr>
                                <td class="font-medium" style="color: var(--table-text)">{{ Str::limit($faq->question, 60) }}</td>
                                <td style="color: var(--table-text-muted)">{{ $faq->category ?? '-' }}</td>
                                <td>
                                    @if ($faq->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors text-xs font-medium">Edit</a>
                                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p>No FAQs found.</p>
                                    <a href="{{ route('admin.faqs.create') }}">Create your first FAQ</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($faqs->hasPages())
                <div class="mt-4">
                    {{ $faqs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
