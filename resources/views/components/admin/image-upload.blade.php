@props(['target' => 'avatar', 'accept' => 'image/*', 'previewClass' => 'w-20 h-20 rounded-full'])

<div x-data="imageUpload(@js($target))" class="w-full">
    <input type="file" :accept="accept" x-ref="fileInput" @change="upload($event)" class="hidden">

    {{-- Empty state: clickable & droppable upload zone --}}
    <template x-if="!preview">
        <div role="button" tabindex="0" :aria-busy="uploading"
            @click="!uploading && $refs.fileInput.click()"
            @keydown.enter="!uploading && $refs.fileInput.click()"
            @dragover.prevent="dragging = true"
            @dragenter.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="handleDrop($event)"
            class="upload-dropzone w-full flex flex-col items-center justify-center gap-2.5 px-4 py-8 text-center cursor-pointer select-none"
            :class="{ 'is-dragging': dragging, 'is-busy': uploading }">
            <span class="w-12 h-12 rounded-full flex items-center justify-center"
                style="background-color: color-mix(in srgb, var(--btn-primary-bg) 10%, transparent); color: var(--btn-primary-bg)">
                <svg x-show="!uploading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <svg x-show="uploading" x-cloak class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                </svg>
            </span>
            <span class="text-sm font-semibold" style="color: var(--heading-text)" x-text="uploading ? 'Uploading...' : (dragging ? 'Drop it here!' : 'Choose a Photo')"></span>
            <span class="text-xs" style="color: var(--muted-text)">Click to select or drag &amp; drop &bull; JPG, PNG, WEBP &bull; Max 2MB</span>
        </div>
    </template>

    {{-- With Preview --}}
    <template x-if="preview">
        <div class="flex items-center gap-5 p-3 border-2 border-dashed rounded-xl transition"
            :class="dragging ? 'upload-dropzone is-dragging' : 'border-transparent'"
            @dragover.prevent="dragging = true"
            @dragenter.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="handleDrop($event)">
            <div class="shrink-0">
                <img :src="preview" :alt="altText"
                    class="{{ $previewClass }} object-cover"
                    style="border: 2px solid var(--table-border); box-shadow: 0 2px 8px rgba(0,0,0,0.08)">
            </div>
            <div class="flex flex-col gap-2.5">
                <button type="button" @click="editPhoto" :disabled="uploading || !lastFile"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold btn-primary transition cursor-pointer"
                    :class="(uploading || !lastFile) ? 'opacity-50 cursor-not-allowed' : ''">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 2v14a2 2 0 002 2h14M18 22V8a2 2 0 00-2-2H2"/>
                    </svg>
                    Edit &amp; Crop
                </button>
                <button type="button" @click="!uploading && $refs.fileInput.click()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold btn-edit transition cursor-pointer"
                    :class="uploading ? 'opacity-50 cursor-not-allowed' : ''">
                    <svg x-show="!uploading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    <svg x-cloak x-show="uploading" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    <span x-text="uploading ? 'Uploading...' : 'Replace Photo'"></span>
                </button>
                <button type="button" @click="remove"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold btn-delete transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Remove
                </button>
            </div>
        </div>
    </template>
</div>

@pushOnce('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imageUpload', (targetId) => ({
            uploading: false,
            dragging: false,
            preview: null,
            altText: '',
            accept: 'image/*',
            pendingPath: null,
            lastFile: null,

            init() {
                const input = document.getElementById(targetId);
                if (input && input.value) {
                    this.preview = input.value;
                }
            },

            handleDrop(event) {
                if (this.uploading) return;
                const file = event.dataTransfer?.files?.[0];
                if (file) {
                    this.uploadFile(file);
                }
                this.dragging = false;
            },

            upload(event) {
                const file = event?.target?.files?.[0];
                if (event?.target) {
                    event.target.value = '';
                }
                if (file) {
                    this.uploadFile(file);
                }
            },

            async uploadFile(file) {
                this.lastFile = file;
                this.uploading = true;
                const formData = new FormData();
                formData.append('file', file);

                try {
                    const res = await fetch('{{ route("admin.upload") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                    const data = await res.json();
                    this.pendingPath = data.path;
                    this.setMedia(data.url);
                } catch (e) {
                    console.error('Upload failed', e);
                    alert('Upload failed. Please try again.');
                } finally {
                    this.uploading = false;
                }
            },

            editPhoto() {
                if (!this.lastFile) {
                    return;
                }

                Alpine.store('imageEditor').open(this.lastFile, async ({ file: processed }) => {
                    this.uploading = true;
                    const formData = new FormData();
                    formData.append('file', processed);

                    try {
                        const res = await fetch('{{ route("admin.upload") }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: formData,
                        });
                        const data = await res.json();
                        if (this.pendingPath && this.pendingPath !== data.path) {
                            await this.cleanup(this.pendingPath);
                        }
                        this.pendingPath = data.path;
                        this.setMedia(data.url);
                    } catch (e) {
                        console.error('Upload failed', e);
                        alert('Upload failed. Please try again.');
                    } finally {
                        this.uploading = false;
                    }
                });
            },

            setMedia(url) {
                this.preview = url;
                const input = document.getElementById(targetId);
                if (input) {
                    input.value = url;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            },

            async remove() {
                const path = this.pendingPath;
                this.pendingPath = null;
                this.preview = null;
                const input = document.getElementById(targetId);
                if (input) {
                    input.value = '';
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
                await this.cleanup(path);
            },

            async cleanup(path) {
                if (!path) {
                    return;
                }
                const formData = new FormData();
                formData.append('path', path);
                try {
                    await fetch('{{ route("admin.upload.destroy") }}', {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                } catch (e) {
                    console.error('Cleanup failed', e);
                }
            },
        }));
    });
</script>
@endpushOnce