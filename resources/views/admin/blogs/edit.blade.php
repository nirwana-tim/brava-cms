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

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="title" :value="__('Title')" :required="true" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $blog->title)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="slug" :value="__('Slug')" :required="true" />
                                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $blog->slug)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                            </div>

                            <div>
                                <x-input-label for="excerpt" :value="__('Excerpt')" />
                                <textarea id="excerpt" name="excerpt" class="form-textarea mt-1" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('excerpt')" />
                            </div>

                            <x-admin.rich-text name="content" :value="old('content', $blog->content)" />

                            <div x-data="{ featuredImage: @js(old('featured_image', $blog->featured_image)), featuredImageAlt: @js(old('featured_image_alt', $blog->featured_image_alt)) }">
                                <x-input-label for="featured_image" :value="__('Featured Image')" />
                                <input type="hidden" name="featured_image" id="featured_image"
                                    value="{{ old('featured_image', $blog->featured_image) }}" />
                                <input type="hidden" name="featured_image_alt" id="featured_image_alt"
                                    value="{{ old('featured_image_alt', $blog->featured_image_alt) }}" />
                                <template x-if="featuredImage">
                                    <div class="mb-2">
                                        <img :src="featuredImage" :alt="featuredImageAlt"
                                            class="rounded-lg"
                                            style="max-width:240px;max-height:160px;object-fit:cover">
                                        <p x-show="featuredImageAlt" class="text-xs mt-1" x-text="'Alt: ' + featuredImageAlt"
                                            style="color:var(--muted-text)"></p>
                                    </div>
                                </template>
                                <x-admin.media-picker target="featured_image" collection="blogs" />
                                <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                            </div>

                            <div>
                                <x-input-label for="published_at" :value="__('Published At')" />
                                <x-text-input id="published_at" name="published_at" type="date" class="mt-1 block w-full" :value="old('published_at', $blog->published_at?->format('Y-m-d'))" />
                                <p class="form-hint">Opsional. Kosongkan agar otomatis diisi tanggal hari ini saat status Published.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('published_at')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" :required="true" />
                                <select id="status" name="status" class="form-select mt-1">
                                    <option value="draft" {{ old('status', $blog->status->value) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $blog->status->value) === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $blog->status->value) === 'archived' ? 'selected' : '' }}>Archived</option>
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

                            <details class="mt-4">
                                <summary class="text-sm font-medium cursor-pointer" style="color: var(--label-text)">SEO Settings</summary>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <x-input-label for="meta_title" :value="__('Meta Title')" />
                                        <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title', $blog->meta_title)" />
                                        <p class="form-hint">Optimal 50–60 karakter untuk Google Search. Otomatis menjadi judul share WhatsApp/Sosmed (OG Title) dan mengikuti judul utama jika dikosongkan.</p>
                                    </div>
                                    <div>
                                        <x-input-label for="meta_description" :value="__('Meta Description')" />
                                        <textarea id="meta_description" name="meta_description" class="form-textarea mt-1" rows="3">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                        <p class="form-hint">Optimal 150–160 karakter (termasuk spasi). Otomatis menjadi deskripsi share WhatsApp/Sosmed (OG Description) dan mengikuti ringkasan artikel jika dikosongkan.</p>
                                    </div>
                                    <div>
                                        <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                                        <x-text-input id="meta_keywords" name="meta_keywords" type="text" class="mt-1 block w-full" :value="old('meta_keywords', $blog->meta_keywords)" placeholder="e.g. seragam kerja, konveksi, baju kantor" />
                                        <p class="form-hint">Daftar 3–5 kata/frasa kunci relevan dipisahkan koma untuk pelabelan topik internal & referensi AI.</p>
                                    </div>
                                    <div x-data="{ ogImage: @js(old('og_image', $blog->og_image)), ogImageAlt: @js(old('og_image_alt', $blog->og_image_alt)) }">
                                        <x-input-label for="og_image" :value="__('OG Image')" />
                                        <input type="hidden" name="og_image" id="og_image"
                                            value="{{ old('og_image', $blog->og_image) }}" />
                                        <input type="hidden" name="og_image_alt" id="og_image_alt"
                                            value="{{ old('og_image_alt', $blog->og_image_alt) }}" />
                                        <template x-if="ogImage">
                                            <div class="mb-2">
                                                <img :src="ogImage" :alt="ogImageAlt"
                                                    class="rounded-lg"
                                                    style="max-width:240px;max-height:120px;object-fit:cover">
                                                <p x-show="ogImageAlt" class="text-xs mt-1" x-text="'Alt: ' + ogImageAlt"
                                                    style="color:var(--muted-text)"></p>
                                            </div>
                                        </template>
                                        <x-admin.media-picker target="og_image" collection="blogs" />
                                        <p class="form-hint">Optimal rasio 1.91:1 (1200x630 px) untuk banner sosmed. Otomatis mengikuti Featured Image jika dikosongkan.</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="robots_index" name="robots_index" value="1" class="form-checkbox" {{ old('robots_index', $blog->robots_index ?? true) ? 'checked' : '' }} />
                                        <x-input-label for="robots_index" :value="__('Allow indexing')" />
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="robots_follow" name="robots_follow" value="1" class="form-checkbox" {{ old('robots_follow', $blog->robots_follow ?? true) ? 'checked' : '' }} />
                                        <x-input-label for="robots_follow" :value="__('Allow following links')" />
                                    </div>
                                    <div>
                                        <x-input-label for="schema_type" :value="__('Schema Type')" />
                                        <select id="schema_type" name="schema_type" class="form-select mt-1 block w-full">
                                            <option value="Article" {{ old('schema_type', $blog->schema_type ?? 'Article') === 'Article' ? 'selected' : '' }}>Article</option>
                                            <option value="BlogPosting" {{ old('schema_type', $blog->schema_type ?? 'Article') === 'BlogPosting' ? 'selected' : '' }}>BlogPosting</option>
                                            <option value="NewsArticle" {{ old('schema_type', $blog->schema_type ?? 'Article') === 'NewsArticle' ? 'selected' : '' }}>NewsArticle</option>
                                        </select>
                                    </div>
                                </div>
                            </details>
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
        const title = document.getElementById('title');
        const metaTitle = document.getElementById('meta_title');
        const metaDesc = document.getElementById('meta_description');
        const excerpt = document.getElementById('excerpt');

        if (title && metaTitle) {
            let metaTitleEdited = metaTitle.value && metaTitle.value !== title.value;
            metaTitle.addEventListener('input', function () { metaTitleEdited = true; });
            title.addEventListener('input', function () {
                if (metaTitleEdited) return;
                metaTitle.value = this.value;
            });
        }

        if (excerpt && metaDesc) {
            let metaDescEdited = metaDesc.value && metaDesc.value !== excerpt.value;
            metaDesc.addEventListener('input', function () { metaDescEdited = true; });
            excerpt.addEventListener('input', function () {
                if (metaDescEdited) return;
                metaDesc.value = this.value;
            });
        }
    });
</script>
@endpush
</x-admin.layouts.app>
