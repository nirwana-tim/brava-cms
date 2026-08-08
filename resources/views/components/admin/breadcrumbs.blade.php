@props(['crumbs' => []])

@if (count($crumbs) > 0)
    <nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'mb-4']) }}>
        <ol class="flex flex-wrap items-center gap-1 text-sm">
            @foreach ($crumbs as $index => $crumb)
                @if ($index > 0)
                    <li aria-hidden="true" class="text-xs font-bold" style="color: var(--btn-primary-bg)">&gt;</li>
                @endif

                @if (! $loop->last && $crumb['href'])
                    <li>
                        <a href="{{ $crumb['href'] }}"
                            class="transition hover:opacity-80"
                            style="color: var(--muted-text)">
                            {{ $crumb['label'] }}
                        </a>
                    </li>
                @else
                    <li>
                        <span class="font-semibold" style="color: var(--btn-primary-bg)" aria-current="page">
                            {{ $crumb['label'] }}
                        </span>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif