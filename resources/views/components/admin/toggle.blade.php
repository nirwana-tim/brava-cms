@props(['name', 'id' => null, 'checked' => false, 'label' => null])

<label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" name="{{ $name }}" value="1"
        id="{{ $id ?? $name }}"
        class="sr-only peer"
        {{ $checked ? 'checked' : '' }}>
    <div class="w-9 h-5 rounded-full peer
        after:content-[''] after:absolute after:top-0.5 after:start-[2px]
        after:bg-white after:rounded-full after:h-4 after:w-4
        after:transition-all peer-checked:after:translate-x-full
        bg-gray-300 peer-checked:bg-green-600 peer-focus:ring-2 peer-focus:ring-green-400"></div>
    @if ($label)
        <span class="ms-2 text-sm font-medium" style="color: var(--label-text)">{{ $label }}</span>
    @endif
</label>
