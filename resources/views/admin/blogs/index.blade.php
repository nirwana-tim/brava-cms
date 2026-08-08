<x-admin.layouts.app>
    <x-slot name="title">{{ __('Blog Posts') }}</x-slot>

    <div class="card">
        <div class="card-header flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold" style="color: var(--heading-text)">Blog Post Management</h2>
                <p class="text-xs" style="color: var(--muted-text)">Manage bilingual articles (Indonesian & English),
                    publish status, and SEO settings.</p>
            </div>
            <a href="{{ route('admin.blogs.create') }}">
                <x-primary-button>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('New Blog Post') }}
                </x-primary-button>
            </a>
        </div>

        <div class="border-t" style="border-color: var(--card-border);">
            <div class="px-6 py-3">
                <form method="GET" action="{{ route('admin.blogs.index') }}"
                    class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-2">
                        <select name="category" onchange="this.form.submit()"
                            class="form-select text-xs py-1.5 px-3 rounded-md border"
                            style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ (string) request('category') === (string) $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <select name="status" onchange="this.form.submit()"
                            class="form-select text-xs py-1.5 px-3 rounded-md border"
                            style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>
                                Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari artikel..." class="form-input text-xs py-1.5 px-3 rounded-md border w-56"
                            style="border-color: var(--card-border); background: var(--input-bg); color: var(--input-text);" />
                        <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Cari</button>
                        @if (request('search') || request('category') || request('status'))
                            <a href="{{ route('admin.blogs.index') }}" class="btn-secondary text-xs py-1.5 px-2.5"
                                title="Reset Filter">Reset</a>
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
                                    <span
                                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if ($blog->status->value === 'published') badge-active
                                        @elseif ($blog->status->value === 'draft') badge-draft
                                        @else badge-default @endif">
                                        {{ ucfirst($blog->status->value) }}
                                    </span>
                                </td>
                                <td>
                                    @foreach ($blog->categories as $cat)
                                        <span
                                            class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-default mr-1">{{ $cat->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <x-admin.action-show :href="route('admin.blogs.show', $blog)" />
                                        <x-admin.action-edit :href="route('admin.blogs.edit', $blog)" />
                                        <x-admin.action-delete :action="route('admin.blogs.destroy', $blog)" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
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
