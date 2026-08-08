<x-admin.layouts.app>
    <x-slot name="title">{{ $blog->title }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                @if ($blog->status->value === 'published') badge-active
                @elseif ($blog->status->value === 'draft') badge-draft
                @else badge-default @endif">
                {{ ucfirst($blog->status->value) }}
            </span>
            @if ($blog->is_featured)
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" style="background-color: var(--flash-success-bg); color: var(--flash-success-text)">
                    Featured
                </span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.blogs.edit', $blog) }}">
                <x-secondary-button type="button" class="gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Edit') }}
                </x-secondary-button>
            </a>
            <a href="{{ route('admin.blogs.index') }}">
                <x-primary-button type="button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back') }}
                </x-primary-button>
            </a>
        </div>
    </div>

    @if ($blog->featured_image)
        <div class="card mb-6 overflow-hidden">
            <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}"
                class="w-full object-cover"
                style="max-height: 400px;">
        </div>
    @endif

    <article class="card">
        <div class="card-body max-w-4xl mx-auto">
            <x-admin.language-tabs>
                <!-- ID Tab -->
                <div x-show="langTab === 'id'" class="space-y-4">
                    <h1 class="text-3xl font-bold" style="color: var(--heading-text)">{{ $blog->getTranslation('title', 'id', false) }}</h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm pb-6 mb-8 border-b" style="color: var(--muted-text); border-color: var(--card-header-border)">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold" style="background-color: var(--btn-primary-bg); color: var(--btn-primary-text)">
                                {{ substr($blog->author?->name ?? 'U', 0, 1) }}
                            </div>
                            <span style="color: var(--table-text)">{{ $blog->author?->name ?? 'Unknown' }}</span>
                        </div>
                        @if ($blog->published_at)
                            <span>&middot;</span>
                            <span>{{ $blog->published_at->format('M d, Y') }}</span>
                        @endif
                        @if ($blog->categories->isNotEmpty())
                            <span>&middot;</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($blog->categories as $cat)
                                    <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full badge-default">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($blog->getTranslation('excerpt', 'id', false))
                        <p class="text-lg leading-relaxed mb-6" style="color: var(--table-text-muted)">{{ $blog->getTranslation('excerpt', 'id', false) }}</p>
                    @endif

                    <div class="prose max-w-none leading-relaxed overflow-x-auto" style="color: var(--table-text); line-height: 1.8">
                        {!! $blog->getTranslation('content', 'id', false) !!}
                    </div>
                </div>

                <!-- EN Tab -->
                <div x-show="langTab === 'en'" class="space-y-4">
                    <h1 class="text-3xl font-bold" style="color: var(--heading-text)">{{ $blog->getTranslation('title', 'en', false) ?: 'No English translation' }}</h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm pb-6 mb-8 border-b" style="color: var(--muted-text); border-color: var(--card-header-border)">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold" style="background-color: var(--btn-primary-bg); color: var(--btn-primary-text)">
                                {{ substr($blog->author?->name ?? 'U', 0, 1) }}
                            </div>
                            <span style="color: var(--table-text)">{{ $blog->author?->name ?? 'Unknown' }}</span>
                        </div>
                        @if ($blog->published_at)
                            <span>&middot;</span>
                            <span>{{ $blog->published_at->format('M d, Y') }}</span>
                        @endif
                        @if ($blog->categories->isNotEmpty())
                            <span>&middot;</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($blog->categories as $cat)
                                    <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full badge-default">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($blog->getTranslation('excerpt', 'en', false))
                        <p class="text-lg leading-relaxed mb-6" style="color: var(--table-text-muted)">{{ $blog->getTranslation('excerpt', 'en', false) }}</p>
                    @else
                        <p class="text-lg leading-relaxed mb-6" style="color: var(--muted-text)">No English excerpt available.</p>
                    @endif

                    <div class="prose max-w-none leading-relaxed overflow-x-auto" style="color: var(--table-text); line-height: 1.8">
                        @if ($blog->getTranslation('content', 'en', false))
                            {!! $blog->getTranslation('content', 'en', false) !!}
                        @else
                            <p style="color: var(--muted-text)">No English content available.</p>
                        @endif
                    </div>
                </div>
            </x-admin.language-tabs>
        </div>
    </article>

    <div class="card mt-6">
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: var(--muted-text)">Slug (ID)</p>
                    <p style="color: var(--table-text)">{{ $blog->getTranslation('slug', 'id', false) }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: var(--muted-text)">Slug (EN)</p>
                    <p style="color: var(--table-text)">{{ $blog->getTranslation('slug', 'en', false) ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: var(--muted-text)">Status</p>
                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if ($blog->status->value === 'published') badge-active
                        @elseif ($blog->status->value === 'draft') badge-draft
                        @else badge-default @endif">
                        {{ ucfirst($blog->status->value) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: var(--muted-text)">Published</p>
                    <p style="color: var(--table-text)">{{ $blog->published_at?->format('M d, Y') ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: var(--muted-text)">Featured</p>
                    <p style="color: var(--table-text)">{{ $blog->is_featured ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
