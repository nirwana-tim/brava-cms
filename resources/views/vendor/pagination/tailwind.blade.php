@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">

            <div>
                <p class="text-sm" style="color: var(--muted-text)">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-semibold" style="color: var(--heading-text)">{{ $paginator->firstItem() }}</span>
                        {!! '-' !!}
                        <span class="font-semibold" style="color: var(--heading-text)">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-semibold" style="color: var(--heading-text)">{{ $paginator->total() }}</span>
                </p>
            </div>

            <div class="flex items-center gap-1 flex-wrap justify-center">

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border text-sm transition"
                        style="border-color: var(--table-border); background-color: var(--table-header-bg); color: var(--muted-text); opacity: .5; cursor: not-allowed" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border text-sm transition hover:opacity-80"
                        style="border-color: var(--table-border); background-color: var(--card-bg); color: var(--muted-text)" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border text-sm"
                            style="border-color: transparent; color: var(--muted-text)">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-2.5 text-sm font-semibold"
                                    style="background-color: var(--btn-primary-bg); color: #fff">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border px-2.5 text-sm transition hover:opacity-80"
                                    style="border-color: var(--table-border); background-color: var(--card-bg); color: var(--muted-text)"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border text-sm transition hover:opacity-80"
                        style="border-color: var(--table-border); background-color: var(--card-bg); color: var(--muted-text)" aria-label="{{ __('pagination.next') }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border text-sm transition"
                        style="border-color: var(--table-border); background-color: var(--table-header-bg); color: var(--muted-text); opacity: .5; cursor: not-allowed" aria-label="true">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif