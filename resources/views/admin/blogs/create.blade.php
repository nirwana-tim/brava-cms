<x-admin.layouts.app>
    <x-slot name="title">{{ __('Create Blog Post') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.blogs.store') }}" method="POST">
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
                            <x-input-label for="excerpt_id" :value="__('Excerpt (ID)')" />
                            <textarea id="excerpt_id" name="excerpt[id]" class="form-textarea mt-1" rows="3">{{ old('excerpt.id') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('excerpt.id')" />
                        </div>

                        <x-admin.rich-text id="content_id" name="content[id]" label="Content (ID)" :value="old('content.id')" />

                        <x-admin.alt-input field="featured_image_alt[id]" label="Featured Image Alt (ID)" :value="old('featured_image_alt.id')" />
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="title_en" :value="__('Title (EN - English)')" />
                                <x-text-input id="title_en" name="title[en]" type="text" class="mt-1 block w-full" :value="old('title.en')" placeholder="Biarkan kosong jika ingin fallback ke Indonesia" />
                                <x-input-error class="mt-2" :messages="$errors->get('title.en')" />
                            </div>

                            <div>
                                <x-input-label for="slug_en" :value="__('Slug (EN - English)')" />
                                <x-text-input id="slug_en" name="slug[en]" type="text" class="mt-1 block w-full" :value="old('slug.en')" placeholder="e.g. convection-tips" />
                                <x-input-error class="mt-2" :messages="$errors->get('slug.en')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="excerpt_en" :value="__('Excerpt (EN - English)')" />
                            <textarea id="excerpt_en" name="excerpt[en]" class="form-textarea mt-1" rows="3">{{ old('excerpt.en') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('excerpt.en')" />
                        </div>

                        <x-admin.rich-text id="content_en" name="content[en]" label="Content (EN)" :value="old('content.en')" />

                        <x-admin.alt-input field="featured_image_alt[en]" label="Featured Image Alt (EN)" :value="old('featured_image_alt.en')" />
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                        <div class="rounded-lg border p-5" style="border-color: var(--card-border);">
                            <div x-data="{ featured_image: @js(old('featured_image')), featured_image_alt: @js(old('featured_image_alt.id')) }">
                                <x-input-label for="featured_image" :value="__('Featured Image')" />
                                <input type="hidden" name="featured_image" id="featured_image" value="{{ old('featured_image') }}" />
                                <template x-if="featured_image">
                                    <img :src="featured_image" :alt="featured_image_alt" class="w-full rounded-lg object-cover mb-4" style="max-height: 280px;">
                                </template>
                                <template x-if="!featured_image">
                                    <div class="w-full rounded-lg overflow-hidden flex items-center justify-center mb-4"
                                        style="height: 220px; background-color: #E1E1E1; border: 1px solid var(--card-border);">
                                        <span class="text-6xl font-bold" style="color: #9CA3AF;">{{ mb_strtoupper(mb_substr(trim(old('title.id') ?: 'B'), 0, 1)) }}</span>
                                    </div>
                                </template>
                                <x-admin.media-picker target="featured_image" collection="blogs" button-class="px-6 py-10 text-base" />
                                <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="rounded-lg border p-6" style="border-color: var(--card-border);">
                                <x-input-label for="status" :value="__('Status')" :required="true" />
                                <select id="status" name="status" class="form-select mt-1">
                                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div class="rounded-lg border p-6" style="border-color: var(--card-border);">
                                <x-input-label :value="__('Categories')" />
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @forelse ($categories as $id => $name)
                                        <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm border cursor-pointer transition-colors"
                                               x-data="{ checked: {{ in_array($id, old('category_ids', [])) ? 'true' : 'false' }} }"
                                               :class="checked && 'bg-blue-600 text-white border-blue-600'"
                                               style="border-color: var(--table-border); background-color: var(--card-bg)">
                                            <input type="checkbox" name="category_ids[]" value="{{ $id }}" x-model="checked" class="form-checkbox">
                                            <span :class="checked && 'text-white'" style="color: var(--label-text)">{{ $name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm" style="color: var(--muted-text)">No categories available.</p>
                                    @endforelse
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('category_ids')" />
                            </div>

                            <x-admin.toggle name="is_featured" :checked="old('is_featured')" label="Featured Post" hint="Display this post in the featured hero section" />
                        </div>
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
                            :schemaType="old('schema_type', 'Article')"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <a href="{{ route('admin.blogs.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupSlug(titleId, slugId) {
            const title = document.getElementById(titleId);
            const slug = document.getElementById(slugId);
            if (title && slug) {
                let slugEdited = false;
                slug.addEventListener('input', function () { if (this.value) slugEdited = true; });
                title.addEventListener('input', function () {
                    if (slugEdited) return;
                    slug.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
                });
            }
        }
        setupSlug('title_id', 'slug_id');
        setupSlug('title_en', 'slug_en');
    });
</script>
@endpush
</x-admin.layouts.app>
