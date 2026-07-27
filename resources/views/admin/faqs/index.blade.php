<x-admin.layouts.app>
    <x-slot name="title">{{ __('FAQs') }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">FAQs</h2>
            <a href="{{ route('admin.faqs.create') }}">
                <x-primary-button>{{ __('New FAQ') }}</x-primary-button>
            </a>
        </div>

        <div class="card-body">
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
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.faqs.show', $faq) }}" class="inline-flex items-center px-3 py-1.5 btn-show rounded-md text-xs font-medium">Show</a>
                                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="inline-flex items-center px-3 py-1.5 btn-edit rounded-md text-xs font-medium">Edit</a>
                                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
