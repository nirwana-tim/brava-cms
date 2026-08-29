<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Portfolio Item') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.portfolio.update', $portfolio) }}" method="POST">
                @csrf
                @method('PUT')

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
                                <x-text-input id="title_id" name="title[id]" type="text" class="mt-1 block w-full" :value="old('title.id', $portfolio->getTranslation('title', 'id', false))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title.id')" />
                            </div>

                            <div>
                                <x-input-label for="slug_id" :value="__('Slug (ID)')" :required="true" />
                                <x-text-input id="slug_id" name="slug[id]" type="text" class="mt-1 block w-full" :value="old('slug.id', $portfolio->getTranslation('slug', 'id', false))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug.id')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description_id" :value="__('Description (ID)')" />
                            <textarea id="description_id" name="description[id]" class="form-textarea mt-1" rows="3">{{ old('description.id', $portfolio->getTranslation('description', 'id', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.id')" />
                        </div>

                        <x-admin.alt-input field="photo_alt[id]" :value="old('photo_alt.id', $portfolio->getTranslation('photo_alt', 'id', false))" label="Cover Photo Alt Text (ID)" />

                        <x-admin.portfolio-fields
                            locale="id"
                            :specifications="old('specifications.id', $portfolio->specifications['id'] ?? [])"
                            :features="old('features.id', $portfolio->features['id'] ?? [])"
                        />
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="title_en" :value="__('Title (EN - English)')" />
                                <x-text-input id="title_en" name="title[en]" type="text" class="mt-1 block w-full" :value="old('title.en', $portfolio->getTranslation('title', 'en', false))" placeholder="Leave blank to fallback to Indonesian" />
                                <x-input-error class="mt-2" :messages="$errors->get('title.en')" />
                            </div>

                            <div>
                                <x-input-label for="slug_en" :value="__('Slug (EN - English)')" />
                                <x-text-input id="slug_en" name="slug[en]" type="text" class="mt-1 block w-full" :value="old('slug.en', $portfolio->getTranslation('slug', 'en', false))" placeholder="e.g. pdh-uniform-project" />
                                <x-input-error class="mt-2" :messages="$errors->get('slug.en')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description_en" :value="__('Description (EN - English)')" />
                            <textarea id="description_en" name="description[en]" class="form-textarea mt-1" rows="3">{{ old('description.en', $portfolio->getTranslation('description', 'en', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.en')" />
                        </div>

                        <x-admin.alt-input field="photo_alt[en]" :value="old('photo_alt.en', $portfolio->getTranslation('photo_alt', 'en', false))" label="Cover Photo Alt Text (EN - English)" />

                        <x-admin.portfolio-fields
                            locale="en"
                            :specifications="old('specifications.en', $portfolio->specifications['en'] ?? [])"
                            :features="old('features.en', $portfolio->features['en'] ?? [])"
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
                                    <option value="{{ $id }}" {{ old('service_id', $portfolio->service_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('service_id')" />
                        </div>

                        <div>
                            <x-input-label :value="__('Categories')" />
                            <div class="mt-2 space-y-1">
                                @foreach ($categories as $id => $name)
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="category_ids[]" value="{{ $id }}" @checked(in_array($id, old('category_ids', $portfolio->categories->pluck('id')->all()), true))>
                                        <span>{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('category_ids')" />
                        </div>
                    </div>

                    @php
                        $galleryMediaItems = $portfolio->media->map(fn($m) => ['id' => $m->id, 'url' => $m->url])->toArray();
                        if (old('gallery_media_ids') !== null) {
                            $oldIds = array_filter(explode(',', (string) old('gallery_media_ids')));
                            $galleryMediaItems = !empty($oldIds)
                                ? \App\Models\Media::whereIn('id', $oldIds)->get()->map(fn($m) => ['id' => $m->id, 'url' => $m->url])->toArray()
                                : [];
                        }
                    @endphp

                    <div id="portfolio-form" x-data="{
                        photoUrl: @js(old('photo', $portfolio->photo)),
                        photoAlt: @js(old('photo_alt.id', $portfolio->getTranslation('photo_alt', 'id', false))),
                    }">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <div>
                                <x-input-label for="photo" :value="__('Cover Photo')" :required="true" />
                                <input type="hidden" name="photo" id="photo" value="{{ old('photo', $portfolio->photo) }}" />
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
                                <x-admin.gallery-picker :initial-items="$galleryMediaItems" collection="portfolio" />
                                <x-input-error class="mt-2" :messages="$errors->get('gallery_media_ids')" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div>
                            <x-input-label for="client" :value="__('Client Name')" />
                            <x-text-input id="client" name="client" type="text" class="mt-1 block w-full" :value="old('client', $portfolio->client)" placeholder="Contoh: KORPRI – Instansi Pemerintah" />
                            <x-input-error class="mt-2" :messages="$errors->get('client')" />
                        </div>

                        <div>
                            <x-input-label for="completed_at" :value="__('Completion Date')" />
                            <x-text-input id="completed_at" name="completed_at" type="date" class="mt-1 block w-full" :value="old('completed_at', $portfolio->completed_at?->format('Y-m-d'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('completed_at')" />
                        </div>
                    </div>

                    <div>
                        <x-admin.toggle name="is_active" :checked="old('is_active', $portfolio->is_active)" label="Active" hint="Show this portfolio item on the website" />
                    </div>

                    <div class="border-t pt-6">
                        <x-admin.seo-fields
                            :metaTitle="old('meta_title.id', $portfolio->getTranslation('meta_title', 'id', false))"
                            :metaTitleEn="old('meta_title.en', $portfolio->getTranslation('meta_title', 'en', false))"
                            :metaDescription="old('meta_description.id', $portfolio->getTranslation('meta_description', 'id', false))"
                            :metaDescriptionEn="old('meta_description.en', $portfolio->getTranslation('meta_description', 'en', false))"
                            :ogImage="old('og_image', $portfolio->og_image)"
                            :ogImageAlt="old('og_image_alt.id', $portfolio->getTranslation('og_image_alt', 'id', false))"
                            :ogImageAltEn="old('og_image_alt.en', $portfolio->getTranslation('og_image_alt', 'en', false))"
                            :robotsIndex="old('robots_index', $portfolio->robots_index)"
                            :robotsFollow="old('robots_follow', $portfolio->robots_follow)"
                            :schemaType="old('schema_type', $portfolio->schema_type ?? 'CreativeWork')"
                            :schemaOptions="['CreativeWork' => 'CreativeWork', 'Product' => 'Product', 'WebPage' => 'WebPage']"
                            :schemaHint="'CreativeWork (default) untuk item portofolio, Product untuk produk komersial, WebPage untuk halaman statis.'"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
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
