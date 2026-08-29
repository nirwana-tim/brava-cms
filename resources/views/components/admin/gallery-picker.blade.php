@props([
    'initialItems' => [],
    'collection' => 'portfolio',
    'max' => 4,
    'name' => 'gallery_media_ids',
])

<div x-data="galleryHandler(@js($initialItems), @js($collection), @js($max))" class="space-y-3">
    <input type="hidden" name="{{ $name }}" :value="galleryIds" />

    <p class="text-xs" style="color: var(--muted-text)">
        <span class="font-semibold" x-text="galleryItems.length"></span> / {{ $max }} photos — maksimal {{ $max }} foto detail pendukung
    </p>

    {{-- Gallery Thumbnails Grid --}}
    <div class="flex flex-wrap gap-2.5 min-h-[76px] items-center p-2 rounded-lg border border-dashed"
        style="border-color: var(--input-border); background-color: color-mix(in srgb, var(--input-bg) 50%, transparent)">
        
        <template x-for="item in galleryItems" :key="item.id">
            <div class="relative group">
                <img :src="item.url" class="rounded-lg border object-cover shadow-xs" style="width: 96px; height: 72px; border-color: var(--table-border)">
                <button type="button" @click="removeGallery(item.id)"
                    class="absolute -top-2 -right-2 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold shadow transition hover:scale-110"
                    style="background-color: var(--btn-danger-bg, #dc2626); color: #fff;"
                    title="Remove from gallery">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>

        {{-- Uploading placeholder --}}
        <template x-if="isUploading">
            <div class="relative flex flex-col items-center justify-center rounded-lg border border-dashed text-xs p-2 animate-pulse"
                style="width: 96px; height: 72px; border-color: var(--focus-ring, #3b82f6); background-color: rgba(59, 130, 246, 0.08); color: var(--muted-text)">
                <svg class="w-5 h-5 animate-spin mb-1 text-blue-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span style="font-size: 10px; font-weight: 500;">Uploading...</span>
            </div>
        </template>

        <template x-if="galleryItems.length === 0 && !isUploading">
            <div class="text-xs py-3 px-2 flex items-center gap-1.5" style="color: var(--muted-text)">
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Belum ada foto detail yang dipilih.</span>
            </div>
        </template>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-wrap items-center gap-2">
        <button type="button" @click="openPicker()"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium btn-media transition"
            x-bind:disabled="galleryItems.length >= max"
            x-bind:class="galleryItems.length >= max && 'opacity-50 cursor-not-allowed'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Choose from Media</span>
        </button>

        <input type="file" accept="image/*" x-ref="directFileInput" class="hidden"
            @change="uploadDirect($event)"
            x-bind:disabled="galleryItems.length >= max || isUploading">
        
        <button type="button" @click="$refs.directFileInput.click()"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium btn-edit transition"
            x-bind:disabled="galleryItems.length >= max || isUploading"
            x-bind:class="(galleryItems.length >= max || isUploading) && 'opacity-50 cursor-not-allowed'">
            <template x-if="isUploading">
                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            <template x-if="!isUploading">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
            </template>
            <span x-text="isUploading ? 'Uploading...' : 'Upload Photo'"></span>
        </button>
    </div>

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
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-semibold" style="color: var(--heading-text)">Media Library &bull; Gallery</h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                        style="background-color: color-mix(in srgb, var(--btn-primary-bg) 12%, transparent); color: var(--btn-primary-bg)">
                        <span x-text="galleryItems.length"></span> / {{ $max }} dipilih
                    </span>
                </div>
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
                    <button type="button" @click="activeTab = 'media'; if (items.length === 0) loadPicker()"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition cursor-pointer"
                        :style="activeTab === 'media' ? 'background-color: var(--card-bg); color: var(--btn-primary-bg); box-shadow: 0 1px 2px rgba(0,0,0,0.12)' : 'background-color: transparent; color: var(--muted-text)'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Media Library
                    </button>
                    <button type="button" @click="activeTab = 'upload'"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-md text-sm font-medium transition cursor-pointer"
                        :style="activeTab === 'upload' ? 'background-color: var(--card-bg); color: var(--btn-primary-bg); box-shadow: 0 1px 2px rgba(0,0,0,0.12)' : 'background-color: transparent; color: var(--muted-text)'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Upload Baru
                    </button>
                </div>
            </div>

            <div class="card-body flex-1 overflow-y-auto">

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
                                <div @click="toggleGallery(item)"
                                    class="rounded-lg border overflow-hidden cursor-pointer transition relative flex flex-col"
                                    :style="isSelected(item.id)
                                        ? 'border-color: var(--btn-primary-bg); box-shadow: 0 0 0 2px var(--btn-primary-bg); background-color: var(--card-bg)'
                                        : 'border-color: var(--table-border); background-color: var(--card-bg)'">

                                    {{-- Selected Badge --}}
                                    <template x-if="isSelected(item.id)">
                                        <div class="absolute top-1.5 right-1.5 z-10 w-5 h-5 rounded-full flex items-center justify-center text-white shadow"
                                            style="background-color: var(--btn-primary-bg)">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </template>

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
                </div>
            </div>

            <div class="card-header flex items-center justify-between shrink-0 text-xs" style="color: var(--muted-text)">
                <div>
                    <span class="font-medium text-sm" style="color: var(--heading-text)" x-text="galleryItems.length"></span>
                    <span x-text="'/ ' + max + ' foto terpilih'"></span>
                </div>
                <button type="button" @click="closePicker" class="px-4 py-1.5 rounded-lg btn-primary font-medium text-xs">
                    Selesai
                </button>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('galleryHandler', (initialItems, collectionName, maxCount) => ({
            galleryItems: Array.isArray(initialItems) ? [...initialItems] : [],
            max: maxCount || 4,
            showPicker: false,
            loadingPicker: false,
            activeTab: 'media',
            searchQuery: '',
            selectedCollection: collectionName || 'portfolio',
            uploadCollection: collectionName || 'portfolio',
            items: [],
            uploadingFromModal: false,
            draggingFromModal: false,
            isUploading: false,
            availableCollections: @js(config('media.collections', [
                'general' => 'General',
                'services' => 'Services',
                'portfolio' => 'Portfolio',
                'blogs' => 'Blogs',
                'promos' => 'Promos',
                'seo' => 'Page SEO',
            ])),

            get galleryIds() {
                return this.galleryItems.map(item => item.id).join(',');
            },

            isSelected(id) {
                return this.galleryItems.some(item => item.id === id);
            },

            addGallery(id, url) {
                if (this.galleryItems.length >= this.max) {
                    alert(`Maksimal ${this.max} foto gallery.`);
                    return;
                }
                if (!this.isSelected(id)) {
                    this.galleryItems.push({ id, url });
                }
            },

            removeGallery(id) {
                this.galleryItems = this.galleryItems.filter(item => item.id !== id);
            },

            toggleGallery(item) {
                if (this.isSelected(item.id)) {
                    this.removeGallery(item.id);
                } else {
                    this.addGallery(item.id, item.url);
                }
            },

            openPicker() {
                this.showPicker = true;
                this.activeTab = 'media';
                if (this.items.length === 0) {
                    this.loadPicker();
                }
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

            async uploadDirect(event) {
                const file = event.target.files[0];
                event.target.value = '';
                if (!file) return;

                if (this.galleryItems.length >= this.max) {
                    alert(`Maksimal ${this.max} foto gallery.`);
                    return;
                }

                this.isUploading = true;
                const formData = new FormData();
                formData.append('file', file);
                formData.append('collection', this.uploadCollection || 'portfolio');

                try {
                    const res = await fetch('{{ route("admin.media.upload-ajax") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                    if (!res.ok) throw new Error('Upload failed');
                    const data = await res.json();
                    this.addGallery(data.id, data.url);
                    this.items.unshift({ ...data, is_image: true, collection: this.uploadCollection });
                } catch (e) {
                    console.error('Gallery upload failed', e);
                    alert('Upload gagal. Silakan coba lagi.');
                } finally {
                    this.isUploading = false;
                }
            },

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
                if (this.galleryItems.length >= this.max) {
                    alert(`Maksimal ${this.max} foto gallery.`);
                    return;
                }

                this.uploadingFromModal = true;
                const formData = new FormData();
                formData.append('file', file);
                formData.append('collection', this.uploadCollection || 'portfolio');

                try {
                    const res = await fetch('{{ route("admin.media.upload-ajax") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                    if (!res.ok) throw new Error('Upload failed');
                    const data = await res.json();
                    this.addGallery(data.id, data.url);
                    this.items.unshift({ ...data, is_image: true, collection: this.uploadCollection });
                    this.activeTab = 'media';
                } catch (e) {
                    console.error('Upload failed', e);
                    alert('Upload gagal. Silakan coba lagi.');
                } finally {
                    this.uploadingFromModal = false;
                }
            },
        }));
    });
</script>
@endpushOnce
