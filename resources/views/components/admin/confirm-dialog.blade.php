@props([
    'action' => null,
    'method' => 'DELETE',
    'label' => 'Delete',
    'title' => 'Confirm action',
    'message' => 'Are you sure you want to continue?',
    'confirmLabel' => 'Delete',
    'icon' => 'trash',
    'confirmIcon' => 'trash',
    'buttonClass' => null,
    'triggerEvent' => null,
])

@php
$icons = [
    'trash' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
    'logout' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
    'alert' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 18c-.77 1.333.192 3 1.732 3z',
];

$triggerClass = $buttonClass ?: 'inline-flex items-center gap-1.5 px-3 py-1.5 btn-delete rounded-md text-xs font-medium cursor-pointer';
@endphp

<form action="{{ $action }}" method="POST" x-data="{ open: false }"
    {!! $triggerEvent ? 'x-on:'.$triggerEvent.'.window="open = true"' : '' !!}
    @keydown.escape.window="open = false"
    x-init="$watch('open', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            $nextTick(() => $refs.cancelBtn?.focus());
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })">
    @csrf
    @if (strtoupper($method) === 'DELETE')
        @method('DELETE')
    @endif

    {{-- Trigger Button --}}
    @if (! $triggerEvent)
        @if ($buttonClass)
            <button type="button" @click="open = true" class="{{ $buttonClass }}" {!! $attributes->whereDoesntStartWith('class') !!}>
        @else
            <button type="button" @click="open = true" {!! $attributes->merge(['class' => $triggerClass]) !!}>
        @endif
            @if ($icon !== 'none')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$icon] }}"/>
                </svg>
            @endif
            {{ $label }}
        </button>
    @endif

    {{-- Confirm Modal --}}
    <div x-cloak x-show="open" class="fixed inset-0 z-[90] flex items-center justify-center px-4 py-6"
        style="display: none;" x-transition.opacity.duration.200ms>
        <div x-show="open" x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50" @click="open = false"></div>

        <div x-show="open" x-transition:enter="transition-all ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition-all ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-1"
            class="relative w-full max-w-sm rounded-2xl border p-6 shadow-2xl"
            style="background-color: var(--card-bg); border-color: var(--card-border)">
            <div class="flex items-start gap-4">
                <div class="flex items-center justify-center w-11 h-11 rounded-full shrink-0"
                    style="background-color: var(--btn-delete-bg); color: var(--btn-delete-text)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$confirmIcon] }}"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-base font-bold leading-snug" style="color: var(--heading-text)">{{ $title }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed" style="color: var(--muted-text)">{{ $message }}</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" @click="open = false" x-ref="cancelBtn"
                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition cursor-pointer"
                    style="background-color: transparent; color: var(--muted-text); border: 1px solid var(--card-border)">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold btn-danger transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$confirmIcon] }}"/>
                    </svg>
                    {{ $confirmLabel }}
                </button>
            </div>
        </div>
    </div>
</form>