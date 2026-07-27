<x-admin.layouts.app>
    <x-slot name="title">{{ __('Testimonials') }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">Testimonials</h2>
            <a href="{{ route('admin.testimonials.create') }}">
                <x-primary-button>{{ __('New Testimonial') }}</x-primary-button>
            </a>
        </div>

        <div class="card-body">
            <div class="admin-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Company</th>
                            <th>Rating</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testimonials as $testimonial)
                            <tr>
                                <td class="font-medium" style="color: var(--table-text)">{{ $testimonial->client_name }}</td>
                                <td style="color: var(--table-text-muted)">{{ $testimonial->company ?? '-' }}</td>
                                <td style="color: var(--table-text-muted)">{{ $testimonial->rating ? str_repeat('★', $testimonial->rating) : '-' }}</td>
                                <td>
                                    @if ($testimonial->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="inline-flex items-center px-3 py-1.5 btn-edit rounded-md text-xs font-medium">Edit</a>
                                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 btn-delete rounded-md text-xs font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <p>No testimonials found.</p>
                                    <a href="{{ route('admin.testimonials.create') }}">Add your first testimonial</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($testimonials->hasPages())
                <div class="mt-4">
                    {{ $testimonials->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
