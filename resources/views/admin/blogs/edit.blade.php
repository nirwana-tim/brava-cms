<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Blog Post') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.blogs.update', $blog) }}" method="POST">
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
                        <div>
                            <x-input-label for="title_id" :value="__('Title (ID)')" :required="true" />
                            <x-text-input id="title_id" name="title[id]" type="text" class="mt-1 block w-full" :value="old('title.id', $blog->getTranslation('title', 'id', false))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title.id')" />
                        </div>

                        <div>
                            <x-input-label for="slug_id" :value="__('Slug (ID)')" :required="true" />
                            <x-text-input id="slug_id" name="slug[id]" type="text" class="mt-1 block w-full" :value="old('slug.id', $blog->getTranslation('slug', 'id', false))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('slug.id')" />
                        </div>

                        <div>
                            <x-input-label for="excerpt_id" :value="__('Excerpt (ID)')" />
                            <textarea id="excerpt_id" name="excerpt[id]" class="form-textarea mt-1" rows="3">{{ old('excerpt.id', $blog->getTranslation('excerpt', 'id', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('excerpt.id')" />
                        </div>

                        <x-admin.rich-text id="content_id" name="content[id]" label="Content (ID)" :value="old('content.id', $blog->getTranslation('content', 'id', false))" />

                        <x-admin.alt-input field="featured_image_alt[id]" label="Featured Image Alt (ID)" :value="old('featured_image_alt.id', $blog->getTranslation('featured_image_alt', 'id', false))" />
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div>
                            <x-input-label for="title_en" :value="__('Title (EN - English)')" />
                            <x-text-input id="title_en" name="title[en]" type="text" class="mt-1 block w-full" :value="old('title.en', $blog->getTranslation('title', 'en', false))" placeholder="Biarkan kosong jika ingin fallback ke Indonesia" />
                            <x-input-error class="mt-2" :messages="$errors->get('title.en')" />
                        </div>

                        <div>
                            <x-input-label for="slug_en" :value="__('Slug (EN - English)')" />
                            <x-text-input id="slug_en" name="slug[en]" type="text" class="mt-1 block w-full" :value="old('slug.en', $blog->getTranslation('slug', 'en', false))" placeholder="e.g. convection-tips" />
                            <x-input-error class="mt-2" :messages="$errors->get('slug.en')" />
                        </div>

                        <div>
                            <x-input-label for="excerpt_en" :value="__('Excerpt (EN - English)')" />
                            <textarea id="excerpt_en" name="excerpt[en]" class="form-textarea mt-1" rows="3">{{ old('excerpt.en', $blog->getTranslation('excerpt', 'en', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('excerpt.en')" />
                        </div>

                        <x-admin.rich-text id="content_en" name="content[en]" label="Content (EN)" :value="old('content.en', $blog->getTranslation('content', 'en', false))" />

                        <x-admin.alt-input field="featured_image_alt[en]" label="Featured Image Alt (EN)" :value="old('featured_image_alt.en', $blog->getTranslation('featured_image_alt', 'en', false))" />
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div x-data="{ imageUrl: @js(old('featured_image', $blog->featured_image)), imageAlt: @js(old('featured_image_alt.id', $blog->getTranslation('featured_image_alt', 'id', false))) }">
                        <x-input-label for="featured_image" :value="__('Featured Image')" />
                        <input type="hidden" name="featured_image" id="featured_image" value="{{ old('featured_image', $blog->featured_image) }}" />
                        <template x-if="imageUrl">
                            <div class="mb-2">
                                <img :src="imageUrl" :alt="imageAlt" class="rounded-lg" style="max-width:240px;max-height:160px;object-fit:cover">
                            </div>
                        </template>
                        <x-admin.media-picker target="featured_image" collection="blogs" />
                        <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status')" :required="true" />
                        <select id="status" name="status" class="form-select mt-1">
                            <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $blog->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    <div>
                        <x-input-label :value="__('Categories')" />
                        <div class="flex flex-wrap gap-2 mt-2">
                            @forelse ($categories as $id => $name)
                                <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm border cursor-pointer transition-colors"
                                       x-data="{ checked: {{ in_array($id, old('category_ids', $blog->categories->pluck('id')->toArray())) ? 'true' : 'false' }} }"
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

                    <div>
                        <x-admin.toggle name="is_featured" :checked="old('is_featured', $blog->is_featured)" label="Featured" />
                    </div>

                    <div class="border-t pt-6">
                        <x-admin.seo-fields
                            :metaTitle="old('meta_title.id', $blog->getTranslation('meta_title', 'id', false))"
                            :metaDescription="old('meta_description.id', $blog->getTranslation('meta_description', 'id', false))"
                            :metaKeywords="old('meta_keywords.id', $blog->getTranslation('meta_keywords', 'id', false))"
                            :ogImage="old('og_image', $blog->og_image)"
                            :ogImageAlt="old('og_image_alt.id', $blog->getTranslation('og_image_alt', 'id', false))"
                            :robotsIndex="old('robots_index', $blog->robots_index)"
                            :robotsFollow="old('robots_follow', $blog->robots_follow)"
                            :schemaType="old('schema_type', $blog->schema_type ?? 'Article')"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
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
