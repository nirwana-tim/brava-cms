@props(['target' => 'featured_image', 'collection' => 'general', 'buttonClass' => ''])

<div x-data="mediaHandler(@js($target), @js($collection))" class="shrink-0">
    <button type="button" @click="openPicker"
        class="btn-media inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg text-sm font-medium transition {{ $buttonClass }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Choose from Media
    </button>

    {{-- Picker Modal --}}
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
                <h3 class="text-lg font-semibold" style="color: var(--heading-text)">Media Library</h3>
                <button type="button" @click="closePicker" class="p-1 rounded hover:opacity-70" style="color: var(--muted-text)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Tabs --}}
            <div class="px-4 pt-4 shrink-0">
                <div class="flex items-center gap-1 p-1 rounded-lg"
                    style="background-color: color-mix(in srgb, var(--btn-primary-bg) 6%, transparent)">
                    <button type="button" @click="activeTab = 'upload'"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition cursor-pointer"
                        :style="activeTab === 'upload' ? 'background-color: var(--card-bg); color: var(--btn-primary-bg); box-shadow: 0 1px 2px rgba(0,0,0,0.12)' : 'background-color: transparent; color: var(--muted-text)'">
                        <svg x-show="activeTab !== 'upload'" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Upload
                    </button>
                    <button type="button" @click="activeTab = 'media'; if (items.length === 0) loadPicker()"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition"
                        :style="activeTab === 'media' ? 'background-color: var(--card-bg); color: var(--btn-primary-bg); box-shadow: 0 1px 2px rgba(0,0,0,0.12)' : 'background-color: transparent; color: var(--muted-text)'">
                        <svg x-show="activeTab !== 'media'" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Media
                    </button>
                </div>
            </div>

            <div class="card-body flex-1 overflow-y-auto">

                {{-- Tab: Upload --}}
                <div x-show="activeTab === 'upload'" x-cloak x-transition.opacity.duration.150ms>
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <label class="text-xs font-medium" style="color: var(--label-text)">Upload to Collection:</label>
                        <select x-model="uploadCollection" class="form-select text-xs py-1 px-2.5 rounded-md" style="border-color: var(--input-border); background-color: var(--input-bg); color: var(--input-text)">
                            <template x-for="(label, key) in availableCollections" :key="key">
                                <option :value="key" x-text="label" :selected="key === uploadCollection"></option>
                            </template>
                        </select>
                    </div>

                    <input type="file" accept="image/*" x-ref="modalFileInput" @change="uploadFromModal($event)" class="hidden">

                    <div role="button" tabindex="0" :aria-busy="uploadingFromModal"
                        @click="!uploadingFromModal && $refs.modalFileInput.click()"
                        @keydown.enter="!uploadingFromModal && $refs.modalFileInput.click()"
                        @dragover.prevent="draggingFromModal = true"
                        @dragenter.prevent="draggingFromModal = true"
                        @dragleave.prevent="draggingFromModal = false"
                        @drop.prevent="handleDropFromModal($event)"
                        class="upload-dropzone w-full flex flex-col items-center justify-center gap-3 px-6 py-12 text-center cursor-pointer select-none"
                        :class="{ 'is-dragging': draggingFromModal, 'is-busy': uploadingFromModal }">
                        <span class="w-14 h-14 rounded-full flex items-center justify-center"
                            style="background-color: color-mix(in srgb, var(--btn-primary-bg) 10%, transparent); color: var(--btn-primary-bg)">
                            <svg x-show="!uploadingFromModal" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="uploadingFromModal" x-cloak class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                            </svg>
                        </span>
                        <span class="text-base font-semibold" style="color: var(--heading-text)"
                            x-text="uploadingFromModal ? 'Uploading...' : (draggingFromModal ? 'Drop it here!' : 'Click to select or drag & drop your file here')"></span>
                        <span class="text-xs" style="color: var(--muted-text)">JPG, PNG, GIF, WEBP &bull; Max 10MB</span>
                    </div>

                    <template x-if="lastUploadedId && !uploadingFromModal">
                        <div class="mt-4 flex items-center justify-between gap-3 rounded-lg border p-3"
                            style="border-color: var(--table-border); background-color: color-mix(in srgb, var(--btn-primary-bg) 4%, var(--card-bg))">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="shrink-0 w-10 h-10 rounded-md overflow-hidden flex items-center justify-center"
                                    style="background-color: var(--input-bg)">
                                    <img :src="items[0]?.url" class="w-full h-full object-cover" alt="Uploaded thumbnail">
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold truncate" style="color: var(--heading-text)">Uploaded!</p>
                                    <p class="text-xs truncate" style="color: var(--muted-text)">Gambar terupload otomatis penuh. Bisa di-edit dulu kalau mau.</p>
                                </div>
                            </div>
                            <button type="button" @click="editLastUpload"
                                class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold btn-primary transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 2v14a2 2 0 002 2h14M18 22V8a2 2 0 00-2-2H2"/>
                                </svg>
                                Edit &amp; Crop
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Tab: Media --}}
                <div x-show="activeTab === 'media'" x-cloak x-transition.opacity.duration.150ms>
                    {{-- Filter & Search Toolbar --}}
                    <div class="mb-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none" style="color: var(--muted-text)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" x-model="searchQuery" @input.debounce.300ms="loadPicker()"
                                placeholder="Search media by name or alt..."
                                class="form-input w-full pl-8 pr-7 py-1.5 text-xs rounded-md"
                                style="border-color: var(--input-border); background-color: var(--input-bg); color: var(--input-text)">
                            <button x-show="searchQuery" x-cloak @click="searchQuery = ''; loadPicker()" type="button" class="absolute inset-y-0 right-0 pr-2 flex items-center text-xs font-bold" style="color: var(--muted-text)">
                                &times;
                            </button>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <label class="text-xs whitespace-nowrap" style="color: var(--muted-text)">Collection:</label>
                            <select x-model="selectedCollection" @change="loadPicker()"
                                class="form-select text-xs py-1.5 px-2.5 rounded-md"
                                style="border-color: var(--input-border); background-color: var(--input-bg); color: var(--input-text)">
                                <option value="">Semua (All)</option>
                                <template x-for="(label, key) in availableCollections" :key="key">
                                    <option :value="key" x-text="label"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <template x-if="loadingPicker">
                        <div class="py-16 flex flex-col items-center justify-center gap-3 text-sm" style="color: var(--muted-text)">
                            <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                            </svg>
                            Loading media...
                        </div>
                    </template>

                    <template x-if="!loadingPicker && items.length === 0">
                        <div class="admin-table-empty py-10">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p>No media found. Try changing the filter or upload one from the Upload tab.</p>
                        </div>
                    </template>

                    <template x-if="!loadingPicker && items.length > 0">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <template x-for="item in items" :key="item.id">
                                <div @click="selectFromPicker(item)"
                                    class="rounded-lg border overflow-hidden cursor-pointer transition hover:opacity-80 hover:shadow-sm flex flex-col"
                                    style="border-color: var(--table-border); background-color: var(--card-bg)">

                                    <div class="aspect-video flex items-center justify-center overflow-hidden relative" style="background-color: var(--input-bg)">
                                        <template x-if="item.is_image">
                                            <img :src="item.url" :alt="item.alt_text" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!item.is_image">
                                            <svg class="w-8 h-8" style="color: var(--muted-text)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </template>
                                        <template x-if="item.collection">
                                            <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[10px] font-medium badge-default shadow-xs"
                                                x-text="availableCollections[item.collection] || item.collection"></span>
                                        </template>
                                    </div>

                                    <div class="p-2 text-xs truncate" x-text="item.name" style="color: var(--table-text)" :title="item.name"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <div class="card-header flex items-center justify-between shrink-0 text-xs" style="color: var(--muted-text)">
                <span x-text="`${items.length} files`"></span>
                <button type="button" @click="closePicker" class="px-3 py-1 rounded btn-edit font-medium">Close</button>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mediaHandler', (targetId, collectionName) => ({
            uploadingFromModal: false,
            draggingFromModal: false,
            activeTab: 'upload',
            showPicker: false,
            loadingPicker: false,
            items: [],
            lastUploadedId: null,
            lastFile: null,
            selectedCollection: '',
            searchQuery: '',
            uploadCollection: collectionName || 'general',
            availableCollections: @js(config('media.collections', [
                'general' => 'General',
                'services' => 'Services',
                'portfolio' => 'Portfolio',
                'blogs' => 'Blogs',
                'promos' => 'Promos',
                'seo' => 'Page SEO',
            ])),

            uploadFromModal(event) {
                const file = event.target.files[0];
                event.target.value = '';
                if (file) {
                    this.uploadModalFile(file);
                }
            },

            handleDropFromModal(event) {
                if (this.uploadingFromModal) return;
                const file = event.dataTransfer?.files?.[0];
                if (file) {
                    this.uploadModalFile(file);
                }
                this.draggingFromModal = false;
            },

            async uploadModalFile(file) {
                this.lastFile = file;
                this.uploadingFromModal = true;
                const formData = new FormData();
                formData.append('file', file);
                formData.append('collection', this.uploadCollection || 'general');

                try {
                    const res = await fetch('{{ route("admin.media.upload-ajax") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                    const data = await res.json();
                    this.lastUploadedId = data.id;
                    this.setMedia(data.url, data.alt_text);
                    this.items.unshift({ ...data, is_image: true, collection: this.uploadCollection });
                } catch (e) {
                    console.error('Upload failed', e);
                    alert('Upload failed. Please try again.');
                } finally {
                    this.uploadingFromModal = false;
                }
            },

            editLastUpload() {
                if (!this.lastFile) {
                    return;
                }

                Alpine.store('imageEditor').open(this.lastFile, async ({ file: processed, alt }) => {
                    this.uploadingFromModal = true;
                    const formData = new FormData();
                    formData.append('file', processed);
                    formData.append('collection', this.uploadCollection || 'general');
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
                        await this.removeMediaRow(this.lastUploadedId);
                        this.lastUploadedId = data.id;
                        this.setMedia(data.url, data.alt_text);
                        this.items.unshift({ ...data, is_image: true, collection: this.uploadCollection });
                    } catch (e) {
                        console.error('Upload failed', e);
                        alert('Upload failed. Please try again.');
                    } finally {
                        this.uploadingFromModal = false;
                    }
                });
            },

            async removeMediaRow(id) {
                if (!id) {
                    return;
                }
                try {
                    await fetch('{{ route("admin.media.destroy", ["medium" => "__ID__"]) }}'.replace('__ID__', id), {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    });
                    this.items = this.items.filter((item) => item.id !== id);
                } catch (e) {
                    console.error('Failed to replace old upload', e);
                }
            },

            openPicker() {
                this.showPicker = true;
                this.activeTab = 'upload';
                if (this.items.length === 0) this.loadPicker();
            },

            closePicker() {
                this.showPicker = false;
            },

            async loadPicker() {
                this.loadingPicker = true;
                try {
                    const params = new URLSearchParams();
                    if (this.selectedCollection) {
                        params.append('collection', this.selectedCollection);
                    }
                    if (this.searchQuery.trim()) {
                        params.append('search', this.searchQuery.trim());
                    }
                    const queryString = params.toString();
                    const url = '{{ route("admin.media.picker-list") }}' + (queryString ? `?${queryString}` : '');
                    const res = await fetch(url);
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
                if (input && input.value !== url) {
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
@endpushOnce