@props(['name' => 'content', 'value' => '', 'label' => 'Content', 'id' => null])

@php
    $editorId = $id ?? \Illuminate\Support\Str::slug($name, '_');
    $dotName = \Illuminate\Support\Str::replace(['[', ']'], ['.', ''], $name);
@endphp

<div>
    @if ($label)
        <x-input-label for="{{ $editorId }}" :value="__($label)" />
    @endif
    <textarea
        id="{{ $editorId }}"
        name="{{ $name }}"
        class="rich-editor mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[var(--input-focus-border)] dark:focus:border-[var(--input-focus-border)] focus:ring-[var(--input-focus-ring)] dark:focus:ring-[var(--input-focus-ring)] rounded-md shadow-sm"
        rows="15"
    >{{ old($dotName, $value) }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get($dotName)" />

    <div x-data="tinymceMediaHandler(@js($editorId))" x-cloak>
        <div x-show="showPicker"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[9999] flex items-center justify-center"
            style="background-color: rgba(0,0,0,0.6)"
            @click.self="closePicker">

            <div
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="card w-full max-w-3xl max-h-[80vh] flex flex-col m-4">

                <div class="card-header flex items-center justify-between shrink-0">
                    <h3 class="text-lg font-semibold" style="color: var(--heading-text)">Pilih Media</h3>
                    <button type="button" @click="closePicker" class="p-1 rounded hover:opacity-70" style="color: var(--muted-text)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="card-body flex-1 overflow-y-auto">
                    <div class="mb-4 pb-4 border-b" style="border-color: var(--table-border)">
                        <p class="text-xs font-medium mb-2" style="color: var(--muted-text)">Upload Media Baru</p>
                        <div class="flex items-center gap-2">
                            <input type="file" accept="image/*" x-ref="modalFileInput" @change="uploadFromModal($event)" class="hidden">
                            <button type="button" @click="$refs.modalFileInput.click()"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium btn-edit"
                                :disabled="uploading">
                                <template x-if="!uploading">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                                    </svg>
                                </template>
                                <template x-if="uploading">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                    </svg>
                                </template>
                                <span x-text="uploading ? 'Uploading...' : 'Upload'"></span>
                            </button>
                            <p class="text-xs" style="color: var(--muted-text)">JPG, PNG, GIF, WebP max 10MB</p>
                        </div>
                    </div>

                    <template x-if="items.length === 0 && !loading">
                        <div class="admin-table-empty">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p>Belum ada media. Upload dulu lewat tombol Upload.</p>
                        </div>
                    </template>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <template x-for="item in items" :key="item.id">
                            <div @click="selectItem(item)"
                                class="rounded-lg border overflow-hidden cursor-pointer transition hover:opacity-80"
                                style="border-color: var(--table-border); background-color: var(--card-bg)">

                                <div class="aspect-video flex items-center justify-center overflow-hidden" style="background-color: var(--input-bg)">
                                    <template x-if="item.is_image">
                                        <img :src="item.url" :alt="item.alt_text" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!item.is_image">
                                        <svg class="w-8 h-8" style="color: var(--muted-text)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </template>
                                </div>

                                <div class="p-2 text-xs truncate" x-text="item.name" style="color: var(--table-text)" :title="item.name"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="card-header flex items-center justify-between shrink-0 text-xs" style="color: var(--muted-text)">
                    <span x-text="`${items.length} file`"></span>
                    <button type="button" @click="closePicker" class="px-3 py-1 rounded btn-edit font-medium">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@once
<script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    document.addEventListener('submit', function (e) {
        if (window.tinymce) {
            window.tinymce.triggerSave();
        }
    });
</script>
@endonce
<script>
var __bravaEditorBg = getComputedStyle(document.documentElement).getPropertyValue('--bg-dashboard').trim() || '#f0f3ff';
var __bravaEditorText = getComputedStyle(document.documentElement).getPropertyValue('--input-text').trim() || '#111827';
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
    content_style: 'body { background-color: ' + __bravaEditorBg + '; color: ' + __bravaEditorText + '; font-size: 0.875rem; } p { margin: 0 0 0.75rem; }',
    valid_elements: 'h1,h2,h3,h4,h5,h6,p,blockquote,ul,ol,li,a[href|title|rel|target],img[alt|src|class|width|height|style],strong,em,u,s,br,pre,code,table[*],thead[*],tbody[*],tr[*],th[*],td[*],span[class|style],div[class|style]',
    invalid_styles: 'color font-size font-family background-color backgroundColor',
    link_default_target: '_blank',
    link_default_protocol: 'https',
    target_list: [{ title: 'New window', value: '_blank' }, { title: 'Same window', value: '' }],
    rel_list: [{ title: 'Noopener', value: 'noopener' }, { title: 'Nofollow', value: 'nofollow' }, { title: 'Noopener + Nofollow', value: 'noopener noreferrer' }],
    file_picker_callback: function (callback, value, meta) {
        window.__tinymcePickerCallback = callback;
        window.dispatchEvent(new CustomEvent('open-tinymce-picker-{{ $editorId }}'));
    },
    setup: function (editor) {
        editor.on('change keyup NodeChange blur', function () {
            editor.save();
        });
    },
});
</script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tinymceMediaHandler', (editorId) => ({
            showPicker: false,
            loading: false,
            uploading: false,
            items: [],

            init() {
                window.addEventListener('open-tinymce-picker-' + editorId, () => {
                    this.openPicker();
                });
            },

            openPicker() {
                this.showPicker = true;
                if (this.items.length === 0) this.loadItems();
            },

            closePicker() {
                this.showPicker = false;
            },

            async loadItems() {
                this.loading = true;
                try {
                    const res = await fetch('{{ route("admin.media.picker-list") }}');
                    this.items = await res.json();
                } catch (e) {
                    console.error('Failed to load media', e);
                } finally {
                    this.loading = false;
                }
            },

            async uploadFromModal(event) {
                const file = event.target.files[0];
                event.target.value = '';
                if (!file) return;

                Alpine.store('imageEditor').open(file, async ({ file: processed, alt }) => {
                    this.uploading = true;
                    const formData = new FormData();
                    formData.append('file', processed);

                    if (alt) {
                        formData.append('alt_text', alt);
                    }

                    try {
                        const res = await fetch('{{ route("admin.media.upload-ajax") }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: formData,
                        });
                        const data = await res.json();
                        await this.loadItems();
                        const cb = window.__tinymcePickerCallback;
                        if (cb) {
                            cb(data.url, { alt: data.alt_text });
                            window.__tinymcePickerCallback = null;
                            this.closePicker();
                        }
                    } catch (e) {
                        console.error('Upload failed', e);
                        alert('Upload failed. Please try again.');
                    } finally {
                        this.uploading = false;
                    }
                });
            },

            selectItem(item) {
                const cb = window.__tinymcePickerCallback;
                if (cb) {
                    cb(item.url, { alt: item.alt_text });
                    window.__tinymcePickerCallback = null;
                }
                this.closePicker();
            },
        }));
    });
</script>
@endpush
