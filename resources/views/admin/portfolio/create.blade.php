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

                <div class="space-y-6">
                    <div>
                        <x-input-label for="service_id" :value="__('Service')" />
                        <select id="service_id" name="service_id" class="form-select mt-1">
                            <option value="">-- Select Service --</option>
                            @foreach ($services as $id => $name)
                                <option value="{{ $id }}" {{ old('service_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('service_id')" />
                    </div>

                    <div>
                        <x-input-label for="title" :value="__('Title')" :required="true" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="slug" :value="__('Slug')" :required="true" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="form-textarea mt-1" rows="3">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <x-admin.portfolio-fields
                        :specifications="old('specifications', [])"
                        :features="old('features', [])"
                    />

                    <div id="portfolio-form" x-data="{
                        photoUrl: @js(old('photo')),
                        photoAlt: @js(old('photo_alt')),
                        galleryItems: [],
                        get galleryIds() { return this.galleryItems.map(i => i.id).join(',') },
                        addGallery(id, url) { if (this.galleryItems.length < 4) this.galleryItems.push({ id, url }) },
                        removeGallery(id) { this.galleryItems = this.galleryItems.filter(i => i.id !== id) },
                    }">
                        <x-input-label for="photo" :value="__('Cover Photo')" :required="true" />
                        <input type="hidden" name="photo" id="photo"
                            value="{{ old('photo') }}" />
                        <input type="hidden" name="gallery_media_ids" :value="galleryIds" />
                        <template x-if="photoUrl">
                            <div class="mb-2">
                                <img :src="photoUrl" :alt="photoAlt"
                                    class="rounded-lg"
                                    style="max-width:240px;max-height:160px;object-fit:cover">
                            </div>
                        </template>
                        <x-admin.media-picker target="photo" collection="portfolio" />
                        <x-admin.alt-input field="photo_alt" :value="old('photo_alt')" />
                        <x-input-error class="mt-2" :messages="$errors->get('photo')" />

                        {{-- Gallery Photos --}}
                        <div class="mt-6 border-t pt-4" style="border-color: var(--card-header-border)">
                            <x-input-label :value="__('Gallery Photos')" />
                            <p class="text-xs mb-2" style="color:var(--muted-text)">
                                <span x-text="galleryItems.length"></span> / 4 photos — maksimal 4 foto detail pendukung
                            </p>
                            <div class="flex items-center gap-2">
                                <input type="file" accept="image/*" id="gallery-file-input" class="hidden">
                                <button type="button" onclick="document.getElementById('gallery-file-input').click()"
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
                            <p x-show="!photoUrl" class="text-xs mt-2 text-amber-500">
                                * Silakan pilih Cover Photo terlebih dahulu untuk mengaktifkan upload foto detail.
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-3">
                                <template x-for="item in galleryItems" :key="item.id">
                                    <div class="relative group rounded-lg border overflow-hidden" style="border-color: var(--table-border)">
                                        <div class="aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                                            <img :src="item.url" alt="Gallery photo" class="w-full h-full object-cover">
                                        </div>
                                        <button type="button"
                                            x-on:click="fetch('{{ url('admin/media') }}/' + item.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => removeGallery(item.id))"
                                            class="absolute top-1 right-1 p-1 rounded-full bg-red-600 text-white opacity-0 group-hover:opacity-100 transition text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div x-show="photoUrl && galleryItems.length === 0" class="text-sm mt-2" style="color: var(--muted-text)">
                                Belum ada gallery photos. Klik tombol di atas untuk menambahkan foto detail.
                            </div>
                        </div>
                    </div>

                    @if ($categories->isNotEmpty())
                        <div>
                            <x-input-label :value="__('Categories')" />
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach ($categories as $id => $name)
                                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm border cursor-pointer transition-colors"
                                           x-data="{ checked: {{ in_array($id, old('categories', [])) ? 'true' : 'false' }} }"
                                           :class="checked && 'bg-blue-600 text-white border-blue-600'"
                                           style="border-color: var(--table-border); background-color: var(--card-bg)">
                                        <input type="checkbox" name="categories[]" value="{{ $id }}" x-model="checked" class="form-checkbox">
                                        <span :class="checked && 'text-white'" style="color: var(--label-text)">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('categories')" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="client" :value="__('Client')" />
                        <x-text-input id="client" name="client" type="text" class="mt-1 block w-full" :value="old('client')" />
                        <x-input-error class="mt-2" :messages="$errors->get('client')" />
                    </div>

                    <div>
                        <x-input-label for="completed_at" :value="__('Completed At')" />
                        <x-text-input id="completed_at" name="completed_at" type="date" class="mt-1 block w-full" :value="old('completed_at')" />
                        <x-input-error class="mt-2" :messages="$errors->get('completed_at')" />
                    </div>

                    <div>
                        <x-admin.toggle name="is_active" :checked="old('is_active', true)" label="Active" />
                    </div>

                    <details class="mt-4">
                        <summary class="text-sm font-medium cursor-pointer" style="color: var(--label-text)">{{ __('SEO Settings') }}</summary>
                        <div class="mt-4 space-y-4">
                            <div>
                                <x-input-label for="meta_title" :value="__('Meta Title')" />
                                <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title')" />
                                <p class="form-hint">Optimal 50–60 karakter untuk Google Search. Otomatis menjadi judul share WhatsApp/Sosmed (OG Title) dan mengikuti judul utama jika dikosongkan.</p>
                            </div>
                            <div>
                                <x-input-label for="meta_description" :value="__('Meta Description')" />
                                <textarea id="meta_description" name="meta_description" class="form-textarea mt-1" rows="3">{{ old('meta_description') }}</textarea>
                                <p class="form-hint">Optimal 150–160 karakter (termasuk spasi). Otomatis menjadi deskripsi share WhatsApp/Sosmed (OG Description) dan mengikuti deskripsi/konten jika dikosongkan.</p>
                            </div>
                            <div>
                                <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                                <x-text-input id="meta_keywords" name="meta_keywords" type="text" class="mt-1 block w-full" :value="old('meta_keywords')" placeholder="e.g. seragam lapangan, tambang, custom uniform" />
                                <p class="form-hint">Daftar 3–5 kata/frasa kunci relevan dipisahkan koma untuk pelabelan topik internal & referensi AI.</p>
                            </div>
                            <div x-data="{ ogImage: @js(old('og_image')), ogImageAlt: @js(old('og_image_alt')) }">
                                <x-input-label for="og_image" :value="__('OG Image')" />
                                <input type="hidden" name="og_image" id="og_image"
                                    value="{{ old('og_image') }}" />
                                <template x-if="ogImage">
                                    <div class="mb-2">
                                        <img :src="ogImage" :alt="ogImageAlt"
                                            class="rounded-lg"
                                            style="max-width:240px;max-height:120px;object-fit:cover">
                                    </div>
                                </template>
                                <x-admin.media-picker target="og_image" collection="portfolio" />
                                <x-admin.alt-input field="og_image_alt" :value="old('og_image_alt')" />
                                <p class="form-hint">Optimal rasio 1.91:1 (1200x630 px) untuk banner sosmed. Otomatis mengikuti Cover Photo jika dikosongkan.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="robots_index" name="robots_index" value="1" class="form-checkbox" {{ old('robots_index', true) ? 'checked' : '' }} />
                                <x-input-label for="robots_index" :value="__('Allow indexing')" />
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="robots_follow" name="robots_follow" value="1" class="form-checkbox" {{ old('robots_follow', true) ? 'checked' : '' }} />
                                <x-input-label for="robots_follow" :value="__('Allow following links')" />
                            </div>
                            <div>
                                <x-input-label for="schema_type" :value="__('Schema Type')" />
                                <select id="schema_type" name="schema_type" class="form-select mt-1 block w-full">
                                    <option value="CreativeWork" {{ old('schema_type', 'CreativeWork') === 'CreativeWork' ? 'selected' : '' }}>CreativeWork</option>
                                    <option value="WebPage" {{ old('schema_type', 'CreativeWork') === 'WebPage' ? 'selected' : '' }}>WebPage</option>
                                    <option value="Article" {{ old('schema_type', 'CreativeWork') === 'Article' ? 'selected' : '' }}>Article</option>
                                </select>
                            </div>
                        </div>
                    </details>
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
        const title = document.getElementById('title');
        const slug = document.getElementById('slug');
        const metaTitle = document.getElementById('meta_title');
        const metaDesc = document.getElementById('meta_description');
        const description = document.getElementById('description');

        if (title && slug) {
            let slugEdited = false;
            slug.addEventListener('input', function () { if (this.value) slugEdited = true; });
            title.addEventListener('input', function () {
                if (slugEdited) return;
                slug.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            });
        }

        if (title && metaTitle) {
            let metaTitleEdited = false;
            metaTitle.addEventListener('input', function () { if (this.value) metaTitleEdited = true; });
            title.addEventListener('input', function () {
                if (metaTitleEdited) return;
                metaTitle.value = this.value;
            });
        }

        if (description && metaDesc) {
            let metaDescEdited = false;
            metaDesc.addEventListener('input', function () { if (this.value) metaDescEdited = true; });
            description.addEventListener('input', function () {
                if (metaDescEdited) return;
                metaDesc.value = this.value;
            });
        }
    });

    document.getElementById('gallery-file-input').addEventListener('change', async function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const btn = document.getElementById('gallery-upload-btn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg> Uploading...';

        const formData = new FormData();
        formData.append('file', file);
        formData.append('collection', 'portfolio');

        try {
            const res = await fetch('{{ route("admin.media.upload-ajax") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData,
            });
            const data = await res.json();

            const alpineEl = document.getElementById('portfolio-form');
            const alpineData = alpineEl ? (window.Alpine ? Alpine.$data(alpineEl) : (alpineEl._x_dataStack ? alpineEl._x_dataStack[0] : (alpineEl.__x ? alpineEl.__x.$data : null))) : null;
            if (alpineData) {
                alpineData.addGallery(data.id, data.url);
            }
        } catch (err) {
            console.error('Gallery upload failed', err);
            alert('Upload failed.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg> Upload Gallery Photo';
            e.target.value = '';
        }
    });
</script>
@endpush
</x-admin.layouts.app>
