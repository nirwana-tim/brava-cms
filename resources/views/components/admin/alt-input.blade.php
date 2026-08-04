@props(['field', 'label' => 'Alt Text', 'value' => ''])

<div class="mt-2">
    <x-input-label for="{{ $field }}" :value="__($label)" />
    <x-text-input id="{{ $field }}" name="{{ $field }}" type="text" class="mt-1 block w-full" :value="$value" placeholder="Kosongkan untuk memakai alt dari media" />
    <p id="{{ $field }}_hint" class="form-hint hidden mt-1"></p>
    <x-input-error class="mt-2" :messages="$errors->get($field)" />
</div>
