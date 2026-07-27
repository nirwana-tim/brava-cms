@props(['active' => false, 'href' => '#'])

@php
$classes = $active
    ? 'flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors sidebar-link-active'
    : 'flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors sidebar-link-inactive';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $icon }}
    <span>{{ $slot }}</span>
</a>
