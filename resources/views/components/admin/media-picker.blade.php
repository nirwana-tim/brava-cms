@props(['target' => 'featured_image', 'collection' => 'general'])

<div x-data="mediaHandler(@js($target), @js($collection))" class="shrink-0">
    <button type="button" @click="openPicker"
        class="btn-media inline-flex items-center gap-2 w-full px-4 py-2.5 rounded-lg text-sm font-medium transition">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Choose from Media
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
                <div class="mb-4 pb-4 border-b" style="border-color: var(--table-border)">
                    <p class="text-xs font-medium mb-2" style="color: var(--muted-text)">Upload Media Baru</p>
                    <div class="flex items-center gap-2">
                        <input type="file" accept="image/*" x-ref="modalFileInput" @change="uploadFromModal($event)" class="hidden">
                        <button type="button" @click="$refs.modalFileInput.click()"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium btn-edit"
                            :disabled="uploadingFromModal">
                            <template x-if="!uploadingFromModal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                                </svg>
                            </template>
                            <template x-if="uploadingFromModal">
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                </svg>
                            </template>
                            <span x-text="uploadingFromModal ? 'Uploading...' : 'Upload'"></span>
                        </button>
                        <p class="text-xs" style="color: var(--muted-text)">JPG, PNG, GIF, WebP max 10MB</p>
                    </div>
                </div>

                <template x-if="items.length === 0 && !loadingPicker && !uploadingFromModal">
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

@pushOnce('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mediaHandler', (targetId, collectionName) => ({
            uploadingFromModal: false,
            showPicker: false,
            loadingPicker: false,
            items: [],

            async uploadFromModal(event) {
                const file = event.target.files[0];
                event.target.value = '';
                if (!file) return;

                Alpine.store('imageEditor').open(file, async ({ file: processed, alt }) => {
                    this.uploadingFromModal = true;
                    const formData = new FormData();
                    formData.append('file', processed);
                    formData.append('collection', collectionName);

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
                        this.setMedia(data.url, data.alt_text);
                        await this.loadPicker();
                    } catch (e) {
                        console.error('Upload failed', e);
                        alert('Upload failed. Please try again.');
                    } finally {
                        this.uploadingFromModal = false;
                    }
                });
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

                const altInputs = document.querySelectorAll(`[name^="${targetId}_alt"]`);
                altInputs.forEach((el) => {
                    el.value = '';
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                });

                const hints = document.querySelectorAll(`[id^="${targetId}_alt"][id$="_hint"]`);
                hints.forEach((hint) => {
                    hint.textContent = alt ? 'Alt default dari media: "' + alt + '". Kosongkan untuk memakainya, atau isi untuk override.' : '';
                    hint.style.display = alt ? '' : 'none';
                });

                const parentEl = this.$el?.parentElement?.closest('[x-data]');
                const parentScope = parentEl ? (window.Alpine ? Alpine.$data(parentEl) : (parentEl._x_dataStack ? parentEl._x_dataStack[0] : (parentEl.__x ? parentEl.__x.$data : null))) : null;
                if (parentScope) {
                    const camelVar = targetId.replace(/_([a-z])/g, (_, c) => c.toUpperCase());
                    if (camelVar in parentScope) {
                        parentScope[camelVar] = url;
                    } else if ((camelVar + 'Url') in parentScope) {
                        parentScope[camelVar + 'Url'] = url;
                    } else if ((targetId + 'Url') in parentScope) {
                        parentScope[targetId + 'Url'] = url;
                    } else if (targetId in parentScope) {
                        parentScope[targetId] = url;
                    }

                    const camelAltVar = (targetId + '_alt').replace(/_([a-z])/g, (_, c) => c.toUpperCase());
                    if (camelAltVar in parentScope) {
                        parentScope[camelAltVar] = '';
                    } else if ((camelVar + 'Alt') in parentScope) {
                        parentScope[camelVar + 'Alt'] = '';
                    } else if ((targetId + 'Alt') in parentScope) {
                        parentScope[targetId + 'Alt'] = '';
                    } else if ((targetId + '_alt') in parentScope) {
                        parentScope[targetId + '_alt'] = '';
                    }
                }
            },
        }));
    });
</script>
@endPushOnce
