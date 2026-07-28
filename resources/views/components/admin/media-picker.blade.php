@props(['target' => 'featured_image', 'collection' => 'general'])

<div x-data="mediaHandler('{{ $target }}', '{{ $collection }}')" class="flex items-center gap-1.5 shrink-0">
    <button type="button" @click="$refs.fileInput.click()"
        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-medium btn-edit"
        :disabled="uploading">
        <template x-if="!uploading">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
            </svg>
        </template>
        <template x-if="uploading">
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
            </svg>
        </template>
        <span x-text="uploading ? 'Uploading...' : 'Upload'"></span>
    </button>

    <input type="file" accept="image/*" x-ref="fileInput" @change="upload($event)" class="hidden">

    <button type="button" @click="openPicker"
        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-medium btn-edit">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Pilih
    </button>

    <div x-show="showPicker"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center"
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
                <template x-if="items.length === 0 && !loadingPicker">
                    <div class="admin-table-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p>Belum ada media. Upload dulu lewat tombol Upload.</p>
                    </div>
                </template>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <template x-for="item in items" :key="item.id">
                        <div @click="selectFromPicker(item)"
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

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mediaHandler', (targetId, collectionName) => ({
            uploading: false,
            showPicker: false,
            loadingPicker: false,
            items: [],

            async upload(event) {
                const file = event.target.files[0];
                if (!file) return;

                this.uploading = true;
                const formData = new FormData();
                formData.append('file', file);
                formData.append('collection', collectionName);

                try {
                    const res = await fetch('{{ route("admin.media.upload-ajax") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                    const data = await res.json();
                    this.setMedia(data.url, data.alt_text);
                } catch (e) {
                    console.error('Upload failed', e);
                    alert('Upload failed. Please try again.');
                } finally {
                    this.uploading = false;
                    event.target.value = '';
                }
            },

            openPicker() {
                this.showPicker = true;
                if (this.items.length === 0) this.loadPicker();
            },

            closePicker() {
                this.showPicker = false;
            },

            async loadPicker() {
                this.loadingPicker = true;
                try {
                    const res = await fetch('{{ route("admin.media.picker-list") }}');
                    this.items = await res.json();
                } catch (e) {
                    console.error('Failed to load media', e);
                } finally {
                    this.loadingPicker = false;
                }
            },

            selectFromPicker(item) {
                this.setMedia(item.url, item.alt_text);
                this.closePicker();
            },

            setMedia(url, alt) {
                const input = document.getElementById(targetId);
                if (input) {
                    input.value = url;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
                const altInput = document.getElementById(targetId + '_alt');
                if (altInput && alt) {
                    altInput.value = alt;
                    altInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            },
        }));
    });
</script>
@endpush
