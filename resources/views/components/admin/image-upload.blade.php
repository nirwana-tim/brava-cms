@props(['target' => 'avatar', 'accept' => 'image/*', 'previewClass' => 'w-16 h-16 rounded-full'])

<div x-data="imageUpload(@js($target))" class="flex items-center gap-3">
    <input type="file" :accept="accept" x-ref="fileInput" @change="upload($event)" class="hidden">

    <template x-if="preview">
        <div class="shrink-0">
            <img :src="preview" :alt="altText"
                class="{{ $previewClass }} object-cover"
                style="border: 2px solid var(--table-border)">
        </div>
    </template>

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
        <span x-text="uploading ? 'Uploading...' : 'Pilih Foto'"></span>
    </button>

    <template x-if="preview">
        <button type="button" @click="remove"
            class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium text-red-600 hover:text-red-800">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus
        </button>
    </template>
</div>

@pushOnce('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imageUpload', (targetId) => ({
            uploading: false,
            preview: null,
            altText: '',
            accept: 'image/*',
            pendingPath: null,

            init() {
                const input = document.getElementById(targetId);
                if (input && input.value) {
                    this.preview = input.value;
                }
            },

            async upload(event) {
                const file = event.target.files[0];
                event.target.value = '';
                if (!file) return;

                Alpine.store('imageEditor').open(file, async ({ file: processed }) => {
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
                if (path) {
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
                }
            },
        }));
    });
</script>
@endpushOnce
