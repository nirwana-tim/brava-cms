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
                    <p class="section-title">Slug (ID)</p>
                    <p style="color: var(--table-text)">{{ $portfolio->getTranslation('slug', 'id', false) }}</p>
                </div>
                <div>
                    <p class="section-title">Slug (EN)</p>
                    <p style="color: var(--table-text)">{{ $portfolio->getTranslation('slug', 'en', false) ?: '-' }}</p>
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
                @if ($portfolio->completed_at)
                <div>
                    <p class="section-title">Completed At</p>
                    <p style="color: var(--table-text)">{{ $portfolio->completed_at->format('M d, Y') }}</p>
                </div>
                @endif
            </div>

            <x-admin.language-tabs>
                <!-- ID Tab -->
                <div x-show="langTab === 'id'" class="space-y-6">
                    @if ($portfolio->getTranslation('description', 'id', false))
                        <div>
                            <p class="section-title">Description</p>
                            <p class="mt-2" style="color: var(--table-text)">{{ $portfolio->getTranslation('description', 'id', false) }}</p>
                        </div>
                    @endif
                    @if ($portfolio->getTranslation('client', 'id', false))
                        <div>
                            <p class="section-title">Client</p>
                            <p style="color: var(--table-text)">{{ $portfolio->getTranslation('client', 'id', false) }}</p>
                        </div>
                    @endif
                    @if ($portfolio->meta_title || $portfolio->meta_description || $portfolio->og_image)
                        <div>
                            <p class="section-title mb-2">SEO</p>
                            <div class="grid grid-cols-2 gap-4 text-sm" style="color: var(--table-text)">
                                @if ($portfolio->getTranslation('meta_title', 'id', false))
                                    <div><span class="font-medium">Meta Title:</span> {{ $portfolio->getTranslation('meta_title', 'id', false) }}</div>
                                @endif
                                @if ($portfolio->getTranslation('meta_description', 'id', false))
                                    <div><span class="font-medium">Meta Description:</span> {{ $portfolio->getTranslation('meta_description', 'id', false) }}</div>
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
                                @if ($portfolio->robots_follow !== null)
                                    <div>
                                        <span class="font-medium">Follow Links:</span>
                                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $portfolio->robots_follow ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $portfolio->robots_follow ? 'Follow' : 'Nofollow' }}
                                        </span>
                                    </div>
                                @endif
                                @if ($portfolio->schema_type)
                                    <div>
                                        <span class="font-medium">Schema Type:</span>
                                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full" style="background: var(--card-header-bg); color: var(--table-text)">{{ $portfolio->schema_type }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <x-admin.portfolio-lists
                        :specifications="$portfolio->specificationsFor('id')"
                        :features="$portfolio->featuresFor('id')"
                    />
                </div>

                <!-- EN Tab -->
                <div x-show="langTab === 'en'" class="space-y-6">
                    @if ($portfolio->getTranslation('description', 'en', false))
                        <div>
                            <p class="section-title">Description (EN)</p>
                            <p class="mt-2" style="color: var(--table-text)">{{ $portfolio->getTranslation('description', 'en', false) }}</p>
                        </div>
                    @endif
                    @if ($portfolio->getTranslation('client', 'en', false))
                        <div>
                            <p class="section-title">Client (EN)</p>
                            <p style="color: var(--table-text)">{{ $portfolio->getTranslation('client', 'en', false) }}</p>
                        </div>
                    @endif
                    @if ($portfolio->getTranslation('meta_title', 'en', false) || $portfolio->getTranslation('meta_description', 'en', false))
                        <div>
                            <p class="section-title mb-2">SEO (EN)</p>
                            <div class="grid grid-cols-2 gap-4 text-sm" style="color: var(--table-text)">
                                @if ($portfolio->getTranslation('meta_title', 'en', false))
                                    <div><span class="font-medium">Meta Title:</span> {{ $portfolio->getTranslation('meta_title', 'en', false) }}</div>
                                @endif
                                @if ($portfolio->getTranslation('meta_description', 'en', false))
                                    <div><span class="font-medium">Meta Description:</span> {{ $portfolio->getTranslation('meta_description', 'en', false) }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                    @unless ($portfolio->getTranslation('description', 'en', false) || $portfolio->getTranslation('client', 'en', false) || $portfolio->getTranslation('meta_title', 'en', false) || $portfolio->specificationsFor('en') !== [] || $portfolio->featuresFor('en') !== [])
                        <p style="color: var(--muted-text)">No English translation available.</p>
                    @endunless

                    <x-admin.portfolio-lists
                        :specifications="$portfolio->specificationsFor('en')"
                        :features="$portfolio->featuresFor('en')"
                    />
                </div>
            </x-admin.language-tabs>

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
