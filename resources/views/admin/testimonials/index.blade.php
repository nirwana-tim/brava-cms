<x-admin.layouts.app>
    <x-slot name="title">{{ __('Testimonials') }}</x-slot>

    <div class="card">
        <div class="card-header flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">Testimonials</h2>
            <a href="{{ route('admin.testimonials.create') }}">
                <x-primary-button>{{ __('New Testimonial') }}</x-primary-button>
            </a>
        </div>

        <div class="border-t" style="border-color: var(--card-border);">
            <div class="px-6 py-3">
                <form method="GET" action="{{ route('admin.testimonials.index') }}" class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-2">
                        <select name="rating" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border" style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Rating</option>
                            <option value="5" {{ (string) request('rating') === '5' ? 'selected' : '' }}>5 Bintang</option>
                            <option value="4" {{ (string) request('rating') === '4' ? 'selected' : '' }}>4 Bintang</option>
                            <option value="3" {{ (string) request('rating') === '3' ? 'selected' : '' }}>3 Bintang</option>
                            <option value="2" {{ (string) request('rating') === '2' ? 'selected' : '' }}>2 Bintang</option>
                            <option value="1" {{ (string) request('rating') === '1' ? 'selected' : '' }}>1 Bintang</option>
                        </select>
                        <select name="status" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border" style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari testimoni..." class="form-input text-xs py-1.5 px-3 rounded-md border w-56" style="border-color: var(--card-border); background: var(--input-bg); color: var(--input-text);" />
                        <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Cari</button>
                        @if (request('search') || request('rating') || request('status'))
                            <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary text-xs py-1.5 px-2.5" title="Reset Filter">Reset</a>
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
                            <th>Company / Organization</th>
                            <th>Rating</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testimonials as $testimonial)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if ($testimonial->avatar)
                                            <img src="{{ $testimonial->avatar }}" alt="" class="rounded-full" style="width: 32px; height: 32px; object-fit: cover;">
                                        @endif
                                        <span class="font-medium" style="color: var(--table-text)">{{ $testimonial->client_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    @if ($testimonial->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="inline-flex items-center px-3 py-1.5 btn-show rounded-md text-xs font-medium">Show</a>
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
                                <td colspan="4" class="admin-table-empty">
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
