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
<script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#{{ $editorId }}',
    license_key: 'gpl',
    height: 500,
    menubar: true,
    plugins: 'advlist autolink link image lists charmap preview anchor searchreplace visualblocks code fullscreen media table wordcount help',
    toolbar: 'undo redo | blocks bold italic underline strikethrough | bullist numlist outdent indent | link image media | code | fullscreen | help',
    relative_urls: false,
    remove_script_host: false,
    document_base_url: '{{ url('/') }}/',
    block_formats: 'Heading 2=h2; Heading 3=h3; Heading 4=h4; Paragraph=p; Blockquote=blockquote',
    valid_elements: 'h2,h3,h4,p,blockquote,ul,ol,li,a[href|title|rel|target],img[alt|src|class|width|height],strong,em,u,s,br,pre,code,table[class],thead,tbody,tr,th[scope],td,span[class],div[class]',
    invalid_styles: 'color font-size font-family background-color backgroundColor',
    link_default_target: '_blank',
    link_default_protocol: 'https',
    target_list: [{ title: 'New window', value: '_blank' }, { title: 'Same window', value: '' }],
    rel_list: [{ title: 'Noopener', value: 'noopener' }, { title: 'Nofollow', value: 'nofollow' }, { title: 'Noopener + Nofollow', value: 'noopener noreferrer' }],
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    },
});
</script>
@endpush
