<x-admin.layouts.app>
    <x-slot name="title">{{ __('FAQs') }}</x-slot>

    <div class="card">
        <div class="card-header flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold" style="color: var(--heading-text)">FAQ Management</h2>
                <p class="text-xs" style="color: var(--muted-text)">Manage the list of frequently asked questions (FAQ) to help customers find answers quickly.</p>
            </div>
            <a href="{{ route('admin.faqs.create') }}">
                <x-primary-button>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('New FAQ') }}
                </x-primary-button>
            </a>
        </div>

        <div class="border-t" style="border-color: var(--card-border);">
            <div class="px-6 py-3">
                <form method="GET" action="{{ route('admin.faqs.index') }}" class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-2">
                        <select name="status" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border" style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari FAQ..." class="form-input text-xs py-1.5 px-3 rounded-md border w-56" style="border-color: var(--card-border); background: var(--input-bg); color: var(--input-text);" />
                        <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Cari</button>
                        @if (request('search') || request('status'))
                            <a href="{{ route('admin.faqs.index') }}" class="btn-secondary text-xs py-1.5 px-2.5" title="Reset Filter">Reset</a>
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
                            <th>Question</th>
                            <th>Sort</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faqs as $faq)
                            <tr>
                                <td class="font-medium" style="color: var(--table-text)">{{ Str::limit($faq->question, 60) }}</td>
                                <td style="color: var(--table-text-muted)">{{ $faq->sort_order ?? '0' }}</td>
                                <td>
                                    @if ($faq->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <x-admin.action-show :href="route('admin.faqs.show', $faq)" />
                                        <x-admin.action-edit :href="route('admin.faqs.edit', $faq)" />
                                        <x-admin.action-delete :action="route('admin.faqs.destroy', $faq)" />
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
