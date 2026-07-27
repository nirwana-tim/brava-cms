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
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $blog->title)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="slug" :value="__('Slug')" />
                                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $blog->slug)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                            </div>

                            <div>
                                <x-input-label for="excerpt" :value="__('Excerpt')" />
                                <textarea id="excerpt" name="excerpt" class="form-textarea mt-1" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('excerpt')" />
                            </div>

                            <x-admin.rich-text name="content" :value="old('content', $blog->content)" />

                            <div>
                                <x-input-label for="featured_image" :value="__('Featured Image URL')" />
                                <x-text-input id="featured_image" name="featured_image" type="text" class="mt-1 block w-full" :value="old('featured_image', $blog->featured_image)" />
                                <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                            </div>

                            <div>
                                <x-input-label for="published_at" :value="__('Published At')" />
                                <x-text-input id="published_at" name="published_at" type="date" class="mt-1 block w-full" :value="old('published_at', $blog->published_at?->format('Y-m-d'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('published_at')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="form-select mt-1">
                                    <option value="draft" {{ old('status', $blog->status->value) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $blog->status->value) === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $blog->status->value) === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div>
                                <x-input-label :value="__('Categories')" />
                                <div class="mt-2 space-y-2">
                                    @forelse ($categories as $id => $name)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="category_ids[]" value="{{ $id }}" class="form-checkbox" {{ in_array($id, old('category_ids', $blog->categories->pluck('id')->toArray())) ? 'checked' : '' }} />
                                            <span class="text-sm" style="color: var(--label-text)">{{ $name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm" style="color: var(--muted-text)">No categories available.</p>
                                    @endforelse
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('category_ids')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_featured" value="1" class="form-checkbox" {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }} />
                                    <span class="text-sm" style="color: var(--label-text)">{{ __('Featured') }}</span>
                                </label>
                            </div>

                            <details class="mt-4">
                                <summary class="text-sm font-medium cursor-pointer" style="color: var(--label-text)">SEO Settings</summary>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <x-input-label for="meta_title" :value="__('Meta Title')" />
                                        <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title', $blog->meta_title)" />
                                        <p class="form-hint">Auto-filled from title. Edit to override.</p>
                                    </div>
                                    <div>
                                        <x-input-label for="meta_description" :value="__('Meta Description')" />
                                        <textarea id="meta_description" name="meta_description" class="form-textarea mt-1" rows="3">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                        <p class="form-hint">Auto-filled from excerpt. Edit to override.</p>
                                    </div>
                                    <div>
                                        <x-input-label for="og_title" :value="__('OG Title')" />
                                        <x-text-input id="og_title" name="og_title" type="text" class="mt-1 block w-full" :value="old('og_title', $blog->og_title)" />
                                        <p class="form-hint">Defaults to meta title.</p>
                                    </div>
                                    <div>
                                        <x-input-label for="og_description" :value="__('OG Description')" />
                                        <textarea id="og_description" name="og_description" class="form-textarea mt-1" rows="2">{{ old('og_description', $blog->og_description) }}</textarea>
                                        <p class="form-hint">Defaults to meta description.</p>
                                    </div>
                                    <div>
                                        <x-input-label for="og_image" :value="__('OG Image URL')" />
                                        <x-text-input id="og_image" name="og_image" type="text" class="mt-1 block w-full" :value="old('og_image', $blog->og_image)" />
                                        <p class="form-hint">Defaults to featured image.</p>
                                    </div>
                                    <div>
                                        <x-input-label for="canonical_url" :value="__('Canonical URL')" />
                                        <x-text-input id="canonical_url" name="canonical_url" type="url" class="mt-1 block w-full" :value="old('canonical_url', $blog->canonical_url)" />
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
