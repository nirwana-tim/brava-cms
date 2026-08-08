<x-admin.layouts.app>
    <x-slot name="title">{{ __('Media') }}</x-slot>

    <div class="card">
        <div class="card-header flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">Media</h2>
            <a href="{{ route('admin.media.create') }}">
                <x-primary-button>{{ __('Upload Media') }}</x-primary-button>
            </a>
        </div>

        <div class="card-body">
            @if ($collections->isNotEmpty())
                <div class="flex flex-wrap items-center gap-1.5 pb-4 border-b mb-4" style="border-color: var(--card-header-border)">
                    <a href="{{ route('admin.media.index') }}"
                        class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium transition
                        {{ !request('collection') ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                        style="color: {{ !request('collection') ? '' : 'var(--table-text)' }}">
                        Semua
                    </a>
                    @foreach ($collections as $name => $total)
                        <a href="{{ route('admin.media.index', ['collection' => $name]) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium transition
                            {{ request('collection') === $name ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                            style="color: {{ request('collection') === $name ? '' : 'var(--table-text)' }}">
                            {{ ucfirst($name) }}
                            <span class="opacity-60">({{ $total }})</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($media->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($media as $item)
                        <div class="rounded-lg border overflow-hidden flex flex-col" style="border-color: var(--table-border); background-color: var(--card-bg);">
                            <div class="aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                                @if (str_starts_with($item->mime_type, 'image/'))
                                    <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-12 h-12" style="color: var(--muted-text)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="p-3 flex-1 flex flex-col gap-2 text-sm">
                                <div class="font-medium truncate" style="color: var(--table-text)" title="{{ $item->name }}">{{ $item->name }}</div>

                                <div class="flex items-center gap-2 text-xs" style="color: var(--table-text-muted)">
                                    <span>{{ $item->mime_type }}</span>
                                    <span>&middot;</span>
                                    <span>{{ number_format($item->size / 1024, 1) }} KB</span>
                                </div>

                                @if ($item->alt_text)
                                    <div class="text-xs truncate" style="color: var(--muted-text)" title="{{ $item->alt_text }}">Alt: {{ $item->alt_text }}</div>
                                @endif

                                @if ($item->collection)
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium badge-default">{{ $item->collection }}</span>
                                    </div>
                                @endif

                                @if ($item->in_use)
                                    <div>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium badge-active"
                                            title="{{ implode("\n", $item->usage) }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Dipakai
                                        </span>
                                    </div>
                                @endif

                                <div class="mt-auto pt-2 flex items-center gap-1">
                                    <input type="text" value="{{ $item->absolute_url }}" readonly
                                        class="flex-1 min-w-0 px-2 py-1 text-xs rounded border truncate"
                                        style="border-color: var(--input-border); background-color: var(--input-bg); color: var(--input-text)"
                                        id="url-{{ $item->id }}">

                                    <button type="button"
                                        onclick="navigator.clipboard.writeText({{ Js::from($item->absolute_url) }}).then(() => { this.textContent = 'Copied!'; setTimeout(() => this.textContent = '', 2000); })"
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium btn-edit shrink-0"
                                        title="Copy URL">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <a href="{{ route('admin.media.edit', $item) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-medium btn-edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    @if ($item->in_use)
                                        <button type="button" disabled
                                            title="Media sedang dipakai di: {{ implode(', ', $item->usage) }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-medium btn-delete opacity-50 cursor-not-allowed">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    @else
                                        <form action="{{ route('admin.media.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-medium btn-delete">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($media->hasPages())
                    <div class="mt-6">
                        {{ $media->links() }}
                    </div>
                @endif
            @else
                <div class="admin-table-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p>{{ request('collection') ? 'No media found in this collection.' : 'No media found.' }}</p>
                    <a href="{{ route('admin.media.create') }}">Upload your first file</a>
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
