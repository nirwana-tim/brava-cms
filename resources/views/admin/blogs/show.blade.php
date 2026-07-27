<x-admin.layouts.app>
    <x-slot name="title">{{ $blog->title }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $blog->title }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.blogs.edit', $blog) }}">
                    <x-secondary-button type="button">{{ __('Edit') }}</x-secondary-button>
                </a>
                <a href="{{ route('admin.blogs.index') }}">
                    <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="section-title">Status</p>
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if ($blog->status->value === 'published') badge-active
                        @elseif ($blog->status->value === 'draft') badge-draft
                        @else badge-default @endif">
                        {{ ucfirst($blog->status->value) }}
                    </span>
                </div>
                <div>
                    <p class="section-title">Author</p>
                    <p style="color: var(--table-text)">{{ $blog->author?->name ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="section-title">Published At</p>
                    <p style="color: var(--table-text)">{{ $blog->published_at?->format('M d, Y') ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="section-title">Slug</p>
                    <p style="color: var(--table-text)">{{ $blog->slug }}</p>
                </div>
                @if ($blog->categories->isNotEmpty())
                <div>
                    <p class="section-title">Categories</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                        @foreach ($blog->categories as $cat)
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-default">{{ $cat->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                <div>
                    <p class="section-title">Featured</p>
                    <p style="color: var(--table-text)">{{ $blog->is_featured ? 'Yes' : 'No' }}</p>
                </div>
            </div>

            @if ($blog->featured_image)
                <div class="mb-6">
                    <p class="section-title">Featured Image</p>
                    <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="mt-2 rounded-lg" style="max-width: 100%; max-height: 400px;">
                </div>
            @endif

            @if ($blog->excerpt)
                <div class="mb-6">
                    <p class="section-title">Excerpt</p>
                    <p class="mt-2" style="color: var(--table-text)">{{ $blog->excerpt }}</p>
                </div>
            @endif

            <div>
                <p class="section-title">Content</p>
                <div class="mt-2 prose prose-sm max-w-none" style="color: var(--table-text); line-height: 1.8">
                    {!! $blog->content !!}
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>