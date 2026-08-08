<x-admin.layouts.app>
    <x-slot name="title">{{ __('Create Portfolio Item') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.portfolio.store') }}" method="POST">
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

                <x-admin.language-tabs>
                    <!-- ID Tab -->
                    <div x-show="langTab === 'id'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="title_id" :value="__('Title (ID)')" :required="true" />
                                <x-text-input id="title_id" name="title[id]" type="text" class="mt-1 block w-full" :value="old('title.id')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title.id')" />
                            </div>

                            <div>
                                <x-input-label for="slug_id" :value="__('Slug (ID)')" :required="true" />
                                <x-text-input id="slug_id" name="slug[id]" type="text" class="mt-1 block w-full" :value="old('slug.id')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug.id')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description_id" :value="__('Description (ID)')" />
                            <textarea id="description_id" name="description[id]" class="form-textarea mt-1" rows="3">{{ old('description.id') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.id')" />
                        </div>

                        <x-admin.alt-input field="photo_alt[id]" :value="old('photo_alt.id')" label="Cover Photo Alt Text (ID)" />

                        <x-admin.portfolio-fields
                            locale="id"
                            :specifications="old('specifications.id', [])"
                            :features="old('features.id', [])"
                        />
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="title_en" :value="__('Title (EN - English)')" />
                                <x-text-input id="title_en" name="title[en]" type="text" class="mt-1 block w-full" :value="old('title.en')" placeholder="Leave blank to fallback to Indonesian" />
                                <x-input-error class="mt-2" :messages="$errors->get('title.en')" />
                            </div>

                            <div>
                                <x-input-label for="slug_en" :value="__('Slug (EN - English)')" />
                                <x-text-input id="slug_en" name="slug[en]" type="text" class="mt-1 block w-full" :value="old('slug.en')" placeholder="e.g. pdh-uniform-project" />
                                <x-input-error class="mt-2" :messages="$errors->get('slug.en')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description_en" :value="__('Description (EN - English)')" />
                            <textarea id="description_en" name="description[en]" class="form-textarea mt-1" rows="3">{{ old('description.en') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.en')" />
                        </div>

                        <x-admin.alt-input field="photo_alt[en]" :value="old('photo_alt.en')" label="Cover Photo Alt Text (EN - English)" />

                        <x-admin.portfolio-fields
                            locale="en"
                            :specifications="old('specifications.en', [])"
                            :features="old('features.en', [])"
                        />
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div>
                            <x-input-label for="service_id" :value="__('Service')" :required="true" />
                            <select id="service_id" name="service_id" class="form-select mt-1" required>
                                <option value="">-- Select Service --</option>
                                @foreach ($services as $id => $name)
                                    <option value="{{ $id }}" {{ old('service_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('service_id')" />
                        </div>

                        <div>
                            <x-input-label :value="__('Categories')" />
                            <div class="mt-2 space-y-1">
                                @foreach ($categories as $id => $name)
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="category_ids[]" value="{{ $id }}" @checked(in_array($id, old('category_ids', []), true))>
                                        <span>{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('category_ids')" />
                        </div>
                    </div>

                    <div id="portfolio-form" x-data="{
                        photoUrl: @js(old('photo')),
                        photoAlt: @js(old('photo_alt.id')),
                        galleryItems: [],
                        get galleryIds() { return this.galleryItems.map(i => i.id).join(',') },
                        addGallery(id, url) { if (this.galleryItems.length < 4) this.galleryItems.push({ id, url }) },
                        removeGallery(id) { this.galleryItems = this.galleryItems.filter(i => i.id !== id) },
                        async uploadGallery(event) {
                            const file = event.target.files[0];
                            event.target.value = '';
                            if (!file) return;
                            const formData = new FormData();
                            formData.append('file', file);
                            formData.append('collection', 'portfolio');
                            try {
                                const res = await fetch('{{ route('admin.media.upload-ajax') }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: formData,
                                });
                                const data = await res.json();
                                this.addGallery(data.id, data.url);
                            } catch (e) {
                                console.error('Gallery upload failed', e);
                                alert('Upload gagal. Silakan coba lagi.');
                            }
                        },
                    }">
                        <input type="hidden" name="gallery_media_ids" :value="galleryIds" />
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <div>
                                <x-input-label for="photo" :value="__('Cover Photo')" :required="true" />
                                <input type="hidden" name="photo" id="photo" value="{{ old('photo') }}" />
                                <template x-if="photoUrl">
                                    <div class="mb-2">
                                        <img :src="photoUrl" :alt="photoAlt" class="rounded-lg" style="max-width:240px;max-height:160px;object-fit:cover">
                                    </div>
                                </template>
                                <x-admin.media-picker target="photo" collection="portfolio" />
                                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                            </div>

                            <div>
                                <x-input-label :value="__('Gallery Photos')" />
                                <p class="text-xs mb-2" style="color:var(--muted-text)">
                                    <span x-text="galleryItems.length"></span> / 4 photos — maksimal 4 foto detail pendukung
                                </p>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="item in galleryItems" :key="item.id">
                                        <div class="relative">
                                            <img :src="item.url" class="rounded-lg border" style="width:96px;height:72px;object-fit:cover">
                                            <button type="button" @click="removeGallery(item.id)"
                                                class="absolute -top-2 -right-2 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold shadow"
                                                style="background-color: var(--btn-danger-bg, #dc2626); color: #fff;"
                                                title="Remove">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="file" accept="image/*" id="gallery-file-input" class="hidden"
                                        @change="uploadGallery($event)"
                                        x-bind:disabled="!photoUrl || galleryItems.length >= 4">
                                    <button type="button" @click="document.getElementById('gallery-file-input').click()"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium btn-edit"
                                        id="gallery-upload-btn"
                                        x-bind:disabled="!photoUrl || galleryItems.length >= 4"
                                        x-bind:class="(!photoUrl || galleryItems.length >= 4) && 'opacity-50 cursor-not-allowed'">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                                        </svg>
                                        Upload Gallery Photo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div>
                            <x-input-label for="client" :value="__('Client Name')" />
                            <x-text-input id="client" name="client" type="text" class="mt-1 block w-full" :value="old('client')" placeholder="Contoh: KORPRI – Instansi Pemerintah" />
                            <x-input-error class="mt-2" :messages="$errors->get('client')" />
                        </div>

                        <div>
                            <x-input-label for="completed_at" :value="__('Completion Date')" />
                            <x-text-input id="completed_at" name="completed_at" type="date" class="mt-1 block w-full" :value="old('completed_at')" />
                            <x-input-error class="mt-2" :messages="$errors->get('completed_at')" />
                        </div>
                    </div>

                    <div>
                        <x-admin.toggle name="is_active" :checked="old('is_active', true)" label="Active" hint="Show this portfolio item on the website" />
                    </div>

                    <div class="border-t pt-6">
                        <x-admin.seo-fields
                            :metaTitle="old('meta_title.id')"
                            :metaTitleEn="old('meta_title.en')"
                            :metaDescription="old('meta_description.id')"
                            :metaDescriptionEn="old('meta_description.en')"
                            :metaKeywords="old('meta_keywords.id')"
                            :metaKeywordsEn="old('meta_keywords.en')"
                            :ogImage="old('og_image')"
                            :ogImageAlt="old('og_image_alt.id')"
                            :ogImageAltEn="old('og_image_alt.en')"
                            :robotsIndex="old('robots_index', true)"
                            :robotsFollow="old('robots_follow', true)"
                            :schemaType="old('schema_type', 'WebPage')"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <a href="{{ route('admin.portfolio.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleId = document.getElementById('title_id');
        const slugId = document.getElementById('slug_id');
        if (titleId && slugId) {
            let slugEdited = false;
            slugId.addEventListener('input', function () { if (this.value) slugEdited = true; });
            titleId.addEventListener('input', function () {
                if (slugEdited) return;
                slugId.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            });
        }
        const titleEn = document.getElementById('title_en');
        const slugEn = document.getElementById('slug_en');
        if (titleEn && slugEn) {
            let slugEditedEn = false;
            slugEn.addEventListener('input', function () { if (this.value) slugEditedEn = true; });
            titleEn.addEventListener('input', function () {
                if (slugEditedEn) return;
                slugEn.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            });
        }
    });
</script>
@endpush
</x-admin.layouts.app>
