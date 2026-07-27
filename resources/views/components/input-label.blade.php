@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'form-label']) }}>
    {{ $value ?? $slot }}
    @if ($required)<span class="ml-0.5 text-red-500 dark:text-red-400">*</span>@endif
</label>
