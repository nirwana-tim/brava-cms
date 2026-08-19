<x-admin.layouts.app>
    <x-slot name="title">{{ ucfirst($pageSeo->page_key) }} SEO</x-slot>

    @php
        $path = $pageSeo->page_key === 'home' ? '/' : '/'.$pageSeo->page_key;
    @endphp

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
                <h2 class="text-lg font-semibold capitalize" style="color: var(--heading-text)">{{ $pageSeo->page_key }} <span class="text-sm font-normal" style="color: var(--muted-text)">({{ $path }})</span></h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.page-seo.edit', $pageSeo->page_key) }}">
                        <x-secondary-button type="button" class="gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </x-secondary-button>
                    </a>
                    <a href="{{ route('admin.page-seo.index') }}">
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
                <div x-show="langTab === 'id'" class="space-y-6">
                    <div>
                        <p class="section-title">Meta Title (ID)</p>
                        <p style="color: var(--table-text)">{{ $pageSeo->getTranslation('meta_title', 'id', false) ?: '— (fallback)' }}</p>
                    </div>
                    <div>
                        <p class="section-title">Meta Description (ID)</p>
                        <p class="mt-2" style="color: var(--table-text); line-height: 1.8;">{{ $pageSeo->getTranslation('meta_description', 'id', false) ?: '— (fallback)' }}</p>
                    </div>
                    <div>
                        <p class="section-title">OG Image Alt (ID)</p>
                        <p style="color: var(--table-text)">{{ $pageSeo->getTranslation('og_image_alt', 'id', false) ?: '—' }}</p>
                    </div>
                </div>

                <div x-show="langTab === 'en'" class="space-y-6">
                    <div>
                        <p class="section-title">Meta Title (EN)</p>
                        <p style="color: var(--table-text)">{{ $pageSeo->getTranslation('meta_title', 'en', false) ?: '— (fallback)' }}</p>
                    </div>
                    <div>
                        <p class="section-title">Meta Description (EN)</p>
                        <p class="mt-2" style="color: var(--table-text); line-height: 1.8;">{{ $pageSeo->getTranslation('meta_description', 'en', false) ?: '— (fallback)' }}</p>
                    </div>
                    <div>
                        <p class="section-title">OG Image Alt (EN)</p>
                        <p style="color: var(--table-text)">{{ $pageSeo->getTranslation('og_image_alt', 'en', false) ?: '—' }}</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t space-y-6" style="border-color: var(--table-border)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="space-y-6">
                            <div>
                                <p class="section-title">Canonical URL</p>
                                <p style="color: var(--table-text)">{{ $pageSeo->canonical_url ?: 'Otomatis ('.$path.')' }}</p>
                            </div>
                            <div>
                                <p class="section-title">Schema Type</p>
                                <p style="color: var(--table-text)">{{ $pageSeo->schema_type ?: 'WebPage' }}</p>
                            </div>
                            <div>
                                <p class="section-title">Robots Indexing</p>
                                <div class="flex items-center gap-1">
                                    @if ($pageSeo->robots_index)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Index</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Noindex</span>
                                    @endif
                                    @if ($pageSeo->robots_follow)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Follow</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Nofollow</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="section-title">Last Updated</p>
                                <p style="color: var(--table-text)">{{ $pageSeo->updated_at?->format('d M Y H:i') ?: '—' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="section-title">OpenGraph Image</p>
                            @if ($pageSeo->og_image)
                                <div class="mt-2 overflow-hidden rounded-lg shadow-sm" style="max-width: 320px; border: 1px solid var(--card-border);">
                                    <img src="{{ $pageSeo->og_image }}" alt="{{ $pageSeo->getTranslation('og_image_alt', 'id', false) ?: $pageSeo->page_key }}" class="w-full object-cover" style="max-height: 200px;">
                                </div>
                            @else
                                <div class="mt-2 overflow-hidden rounded-lg shadow-sm" style="width: 100%; max-width: 320px; height: 160px; background-color: #E1E1E1; border: 1px solid var(--card-border);">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #9CA3AF;">No image (fallback)</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>