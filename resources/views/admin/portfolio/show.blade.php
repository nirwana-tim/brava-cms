<x-admin.layouts.app>
    <x-slot name="title">{{ $portfolio->title }}</x-slot>

    <div x-data="{ langTab: 'id' }">
        <div class="flex items-center gap-2 border-b pb-2 mb-6" style="border-color: var(--table-border)">
            <button type="button"
                    @click="langTab = 'id'"
                    :style="langTab === 'id' ? 'background-color: var(--btn-primary-bg); color: var(--btn-primary-text); font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.2);' : 'background-color: var(--btn-secondary-bg); color: var(--btn-secondary-text); border: 1px solid var(--btn-secondary-border);'"
                    class="px-4 py-2 text-sm rounded-lg transition flex items-center gap-2 cursor-pointer">
                Bahasa Indonesia (Default)
            </button>
            <button type="button"
                    @click="langTab = 'en'"
                    :style="langTab === 'en' ? 'background-color: var(--btn-primary-bg); color: var(--btn-primary-text); font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.2);' : 'background-color: var(--btn-secondary-bg); color: var(--btn-secondary-text); border: 1px solid var(--btn-secondary-border);'"
                    class="px-4 py-2 text-sm rounded-lg transition flex items-center gap-2 cursor-pointer">
                English (Inggris)
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $portfolio->title }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.portfolio.edit', $portfolio) }}">
                        <x-secondary-button type="button" class="gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </x-secondary-button>
                    </a>
                    <a href="{{ route('admin.portfolio.index') }}">
                        <x-primary-button type="button">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back') }}
                        </x-primary-button>
                    </a>
                </div>
            </div>

            <div class="card-body">
                @foreach (['id', 'en'] as $locale)
                    <div x-show="langTab === '{{ $locale }}'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <div class="{{ $portfolio->media->isEmpty() ? 'md:col-span-2' : '' }}">
                                <p class="section-title">Cover Photo</p>
                                @if ($portfolio->photo)
                                    <div class="mt-2 overflow-hidden rounded-lg shadow-sm" style="border: 1px solid var(--card-border);">
                                        <img src="{{ $portfolio->photo }}" alt="{{ $portfolio->title }}" class="w-full object-cover" style="max-height: 440px;">
                                    </div>
                                @else
                                    <div class="mt-2 overflow-hidden rounded-lg shadow-sm" style="width: 100%; max-width: 560px; height: 280px; background-color: #E1E1E1; border: 1px solid var(--card-border);">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-4xl font-bold" style="color: #9CA3AF;">{{ mb_strtoupper(mb_substr($portfolio->getTranslation('title', $locale, false) ?: $portfolio->getTranslation('title', 'id', false), 0, 1)) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($portfolio->media->isNotEmpty())
                                <div>
                                    <p class="section-title mb-3">Gallery Photos</p>
                                    <div class="grid grid-cols-2 gap-3">
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

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="section-title">Active</p>
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $portfolio->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $portfolio->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
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
                            @if ($portfolio->completed_at)
                                <div>
                                    <p class="section-title">Completed At</p>
                                    <p style="color: var(--table-text)">{{ $portfolio->completed_at->format('M d, Y') }}</p>
                                </div>
                            @endif
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
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="section-title">Slug (ID)</p>
                                <p class="font-mono break-all" style="color: var(--table-text)">{{ $portfolio->getTranslation('slug', 'id', false) ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="section-title">Slug (EN)</p>
                                <p class="font-mono break-all" style="color: var(--table-text)">{{ $portfolio->getTranslation('slug', 'en', false) ?: '-' }}</p>
                            </div>
                        </div>

                        @if ($portfolio->getTranslation('description', $locale, false))
                            <div>
                                <p class="section-title">{{ $locale === 'en' ? 'Description (EN)' : 'Description' }}</p>
                                <p class="mt-2" style="color: var(--table-text); line-height: 1.8;">{{ $portfolio->getTranslation('description', $locale, false) }}</p>
                            </div>
                        @endif

                        <x-admin.portfolio-lists
                            :specifications="$portfolio->specificationsFor($locale)"
                            :features="$portfolio->featuresFor($locale)"
                        />

                        @if ($portfolio->robots_index !== null || $portfolio->robots_follow !== null || $portfolio->schema_type)
                            <div>
                                <p class="section-title mb-2">Indexing</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium">Robots:</span>
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

                        @if ($portfolio->getTranslation('meta_title', $locale, false) || $portfolio->getTranslation('meta_description', $locale, false) || $portfolio->og_image)
                            <div>
                                <p class="section-title mb-2">{{ $locale === 'en' ? 'SEO (EN)' : 'SEO' }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm" style="color: var(--table-text)">
                                    @if ($portfolio->getTranslation('meta_title', $locale, false))
                                        <div><span class="font-medium">Meta Title:</span> {{ $portfolio->getTranslation('meta_title', $locale, false) }}</div>
                                    @endif
                                    @if ($portfolio->getTranslation('meta_description', $locale, false))
                                        <div><span class="font-medium">Meta Description:</span> {{ $portfolio->getTranslation('meta_description', $locale, false) }}</div>
                                    @endif
                                    @if ($portfolio->og_image)
                                        <div class="col-span-1 md:col-span-2">
                                            <span class="font-medium">OG Image:</span>
                                            <img src="{{ $portfolio->og_image }}" alt="OG Image" class="mt-1 rounded max-h-32">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($locale === 'en')
                            @unless ($portfolio->getTranslation('description', 'en', false) || $portfolio->getTranslation('meta_title', 'en', false) || $portfolio->getTranslation('meta_description', 'en', false) || $portfolio->specificationsFor('en') !== [] || $portfolio->featuresFor('en') !== [])
                                <p style="color: var(--muted-text)">No English translation available.</p>
                            @endunless
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin.layouts.app>