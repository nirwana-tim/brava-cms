<x-admin.layouts.app>
    <x-slot name="title">{{ __('Upload Media') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" x-data="mediaUploadHandler(@js(old('alt_text')))">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 rounded-lg alert-error border p-4">
                        <div class="text-sm">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <x-input-label for="file" :value="__('File')" />
                        <input id="file" x-ref="fileInput" name="file" type="file"
                            @change="onFileChange($event)"
                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800" required />
                        <p class="form-hint">Pilih gambar, lalu potong / putar / atur rasio pada preview sebelum upload.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('file')" />
                    </div>

                    <template x-if="previewUrl">
                        <div class="rounded-lg border overflow-hidden" style="border-color: var(--table-border)">
                            <img :src="previewUrl" alt="Preview" class="w-full max-h-72 object-contain" style="background-color: var(--input-bg)">
                        </div>
                    </template>

                    <div>
                        <x-input-label for="alt_text" :value="__('Alt Text')" />
                        <x-text-input id="alt_text" name="alt_text" type="text" class="mt-1 block w-full"
                            x-model="altText" placeholder="Deskripsi gambar untuk aksesibilitas & SEO" />
                        <p class="form-hint">Describes the image for accessibility and SEO. Screen readers and search engines use this text.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('alt_text')" />
                    </div>

                    <div>
                        <x-input-label for="collection" :value="__('Collection')" />
                        <x-text-input id="collection" name="collection" type="text" class="mt-1 block w-full" :value="old('collection')" placeholder="e.g., products, blogs, general" />
                        <x-input-error class="mt-2" :messages="$errors->get('collection')" />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Upload') }}</x-primary-button>
                    <a href="{{ route('admin.media.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mediaUploadHandler', (initialAlt) => ({
                previewUrl: null,
                altText: initialAlt || '',

                onFileChange(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    if (!file.type.startsWith('image/')) {
                        return;
                    }

                    Alpine.store('imageEditor').open(file, ({ file: processed, alt }) => {
                        const transfer = new DataTransfer();
                        transfer.items.add(processed);

                        this.$refs.fileInput.files = transfer.files;

                        if (this.previewUrl) {
                            URL.revokeObjectURL(this.previewUrl);
                        }

                        this.previewUrl = URL.createObjectURL(processed);
                        this.altText = alt;
                    });
                },
            }));
        });
    </script>
    @endpush
</x-admin.layouts.app>
