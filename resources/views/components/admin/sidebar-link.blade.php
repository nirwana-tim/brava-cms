@props(['active' => false, 'href' => '#'])

@php
$classes = $active
    ? 'flex items-center gap-3 px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-lg transition-colors'
    : 'flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $icon }}
    <span>{{ $slot }}</span>
</a>
