<x-admin.layouts.app>
    <x-slot name="title">{{ $portfolio->title }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $portfolio->title }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.portfolio.edit', $portfolio) }}">
                    <x-secondary-button type="button">{{ __('Edit') }}</x-secondary-button>
                </a>
                <a href="{{ route('admin.portfolio.index') }}">
                    <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($portfolio->photo)
                <div class="mb-6">
                    <p class="section-title">Cover Photo</p>
                    <img src="{{ $portfolio->photo }}" alt="{{ $portfolio->title }}" class="mt-2 rounded-lg" style="max-width: 100%; max-height: 400px;">
                </div>
            @endif

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="section-title">Slug</p>
                    <p style="color: var(--table-text)">{{ $portfolio->slug }}</p>
                </div>
                <div>
                    <p class="section-title">Active</p>
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $portfolio->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $portfolio->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @if ($portfolio->categories->isNotEmpty())
                <div class="col-span-2">
                    <p class="section-title">Categories</p>
                    <div class="mt-1 flex flex-wrap gap-1.5">
                        @foreach ($portfolio->categories as $category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium badge-default">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if ($portfolio->service)
                <div>
                    <p class="section-title">Service</p>
                    <p style="color: var(--table-text)">{{ $portfolio->service->title }}</p>
                </div>
                @endif
                @if ($portfolio->client)
                <div>
                    <p class="section-title">Client</p>
                    <p style="color: var(--table-text)">{{ $portfolio->client }}</p>
                </div>
                @endif
                @if ($portfolio->project_url)
                <div>
                    <p class="section-title">Project URL</p>
                    <a href="{{ $portfolio->project_url }}" target="_blank" rel="noopener noreferrer" style="color: var(--btn-edit-text)">{{ $portfolio->project_url }}</a>
                </div>
                @endif
                @if ($portfolio->completed_at)
                <div>
                    <p class="section-title">Completed At</p>
                    <p style="color: var(--table-text)">{{ $portfolio->completed_at->format('M d, Y') }}</p>
                </div>
                @endif
                <div>
                    <p class="section-title">Sort Order</p>
                    <p style="color: var(--table-text)">{{ $portfolio->sort_order ?? '0' }}</p>
                </div>
            </div>

            @if ($portfolio->meta_title || $portfolio->meta_description || $portfolio->og_image)
                <div class="mb-6">
                    <p class="section-title mb-2">SEO</p>
                    <div class="grid grid-cols-2 gap-4 text-sm" style="color: var(--table-text)">
                        @if ($portfolio->meta_title)
                            <div><span class="font-medium">Meta Title:</span> {{ $portfolio->meta_title }}</div>
                        @endif
                        @if ($portfolio->meta_description)
                            <div><span class="font-medium">Meta Description:</span> {{ $portfolio->meta_description }}</div>
                        @endif
                        @if ($portfolio->og_image)
                            <div class="col-span-2">
                                <span class="font-medium">OG Image:</span>
                                <img src="{{ $portfolio->og_image }}" alt="OG Image" class="mt-1 rounded max-h-32">
                            </div>
                        @endif
                        <div>
                            <span class="font-medium">Indexing:</span>
                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $portfolio->robots_index ? 'badge-active' : 'badge-inactive' }}">
                                {{ $portfolio->robots_index ? 'Allowed' : 'Noindex' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            @if ($portfolio->description)
                <div class="mb-6">
                    <p class="section-title">Description</p>
                    <p class="mt-2" style="color: var(--table-text)">{{ $portfolio->description }}</p>
                </div>
            @endif

            <div>
                <p class="section-title">Content</p>
                <div class="mt-2 prose prose-sm max-w-none" style="color: var(--table-text); line-height: 1.8">
                    {!! $portfolio->content !!}
                </div>
            </div>

            @if ($portfolio->media->isNotEmpty())
                <div class="mt-8">
                    <p class="section-title mb-3">Gallery Photos</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach ($portfolio->media as $media)
                            <div class="rounded-lg border overflow-hidden" style="border-color: var(--table-border)">
                                <div class="aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $media->url }}" alt="{{ $media->alt_text }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
