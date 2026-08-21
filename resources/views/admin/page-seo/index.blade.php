<x-admin.layouts.app>
    <x-slot name="title">{{ __('Page SEO') }}</x-slot>

    <div class="card">
        <div class="card-header flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold" style="color: var(--heading-text)">SEO</h2>
                <p class="text-xs" style="color: var(--muted-text)">Kelola SEO per-halaman di bawah. Kosongkan field untuk memakai fallback bawaan.</p>
            </div>
        </div>

        <div class="card-body">
            <div class="card mb-6" style="background: transparent; border: 1px solid var(--card-border)">
                <div class="card-body">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="section-title">Global Defaults (Fallback Seluruh Situs)</h3>
                            <p class="text-xs" style="color: var(--muted-text)">Fallback untuk halaman yang tidak punya meta sendiri (root layout &amp; halaman dinamis). Bukan SEO per-halaman — setiap halaman diatur di tabel bawah.</p>
                        </div>
                        @can('update', $defaults['default_meta_title'])
                            <a href="{{ route('admin.page-seo.defaults') }}" class="shrink-0">
                                <x-primary-button>{{ __('Edit Defaults') }}</x-primary-button>
                            </a>
                        @else
                            <div class="shrink-0 inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-md" style="background-color: var(--table-header-bg); border: 1px solid var(--card-border); color: var(--muted-text)">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="color: var(--brand-primary-500)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Tidak memiliki izin untuk mengubah pengaturan ini.</span>
                            </div>
                        @endcan
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Default Meta Title</p>
                            <p class="text-sm mt-1" style="color: var(--table-text)">{{ $defaults['default_meta_title']?->getTranslation('value', 'id', false) ?: '— (kosong)' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Default Meta Description</p>
                            <p class="text-sm mt-1 line-clamp-2" style="color: var(--table-text)">{{ $defaults['default_meta_description']?->getTranslation('value', 'id', false) ?: '— (kosong)' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">Default OG Image</p>
                            <p class="text-sm mt-1 truncate" style="color: var(--table-text)">{{ $defaults['default_og_image']?->value ?: '— (kosong)' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Page</th>
                            <th>Meta Title (ID)</th>
                            <th>Meta Description (ID)</th>
                            <th>Indexing</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pageSeos as $pageSeo)
                            @php
                                $path = $pageSeo->page_key === 'home' ? '/' : '/'.$pageSeo->page_key;
                                $metaTitle = $pageSeo->getTranslation('meta_title', 'id', false);
                                $metaDescription = $pageSeo->getTranslation('meta_description', 'id', false);
                            @endphp
                            <tr>
                                <td>
                                    <span class="font-medium capitalize" style="color: var(--table-text)">{{ $pageSeo->page_key }}</span>
                                    <span class="block text-xs" style="color: var(--muted-text)">{{ $path }}</span>
                                </td>
                                <td class="text-sm" style="color: var(--table-text)">{{ $metaTitle ?: '— (fallback)' }}</td>
                                <td class="text-sm max-w-xs cell-wrap" style="color: var(--muted-text)" title="{{ $metaDescription }}">{{ $metaDescription ? \Illuminate\Support\Str::limit($metaDescription, 80) : '— (fallback)' }}</td>
                                <td>
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
                                </td>
                                <td class="text-sm whitespace-nowrap" style="color: var(--muted-text)">{{ $pageSeo->updated_at?->diffForHumans() }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <x-admin.action-show :href="route('admin.page-seo.show', $pageSeo->page_key)" />
                                        <x-admin.action-edit :href="route('admin.page-seo.edit', $pageSeo->page_key)" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                                    <p>No page SEO records found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin.layouts.app>