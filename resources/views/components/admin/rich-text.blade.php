@props(['name' => 'content', 'value' => '', 'label' => 'Content', 'id' => null])

@php $editorId = $id ?? $name; @endphp

<div>
    @if ($label)
        <x-input-label for="{{ $editorId }}" :value="__($label)" />
    @endif
    <textarea
        id="{{ $editorId }}"
        name="{{ $name }}"
        class="rich-editor mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        rows="15"
    >{{ old($name, $value) }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get($name)" />
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#{{ $editorId }}',
    height: 500,
    menubar: true,
    plugins: 'advlist autolink link image lists charmap preview anchor searchreplace visualblocks code fullscreen media table wordcount',
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code | fullscreen | help',
    relative_urls: false,
    remove_script_host: false,
    document_base_url: '{{ url('/') }}/',
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    },
});
</script>
@endpush
