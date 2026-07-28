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

                    <x-admin.rich-text name="content" :value="old('content')" />

                    <div x-data="{ photoUrl: '', photoAlt: '' }">
                        <x-input-label for="photo" :value="__('Photo')" />
                        <input type="hidden" name="photo" id="photo"
                            :value="photoUrl" x-on:input="photoUrl = $event.target.value" />
                        <input type="hidden" name="photo_alt" id="photo_alt"
                            :value="photoAlt" x-on:input="photoAlt = $event.target.value" />
                        <template x-if="photoUrl">
                            <div class="mb-2">
                                <img :src="photoUrl" :alt="photoAlt"
                                    class="rounded-lg"
                                    style="max-width:240px;max-height:160px;object-fit:cover">
                                <p x-show="photoAlt" class="text-xs mt-1" x-text="'Alt: ' + photoAlt"
                                    style="color:var(--muted-text)"></p>
                            </div>
                        </template>
                        <x-admin.media-picker target="photo" collection="portfolio" />
                        <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                    </div>

                    @if ($categories->isNotEmpty())
                        <div>
                            <x-input-label :value="__('Categories')" />
                            <div class="mt-2 space-y-2">
                                @foreach ($categories as $id => $name)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="categories[]" value="{{ $id }}" class="form-checkbox" {{ in_array($id, old('categories', [])) ? 'checked' : '' }} />
                                        <span class="text-sm" style="color: var(--label-text)">{{ $name }}</span>
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
                        <x-input-label for="project_url" :value="__('Project URL')" />
                        <x-text-input id="project_url" name="project_url" type="url" class="mt-1 block w-full" :value="old('project_url')" />
                        <x-input-error class="mt-2" :messages="$errors->get('project_url')" />
                    </div>

                    <div>
                        <x-input-label for="completed_at" :value="__('Completed At')" />
                        <x-text-input id="completed_at" name="completed_at" type="date" class="mt-1 block w-full" :value="old('completed_at')" />
                        <x-input-error class="mt-2" :messages="$errors->get('completed_at')" />
                    </div>

                    <div>
                        <x-input-label for="sort_order" :value="__('Sort Order')" />
                        <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full" :value="old('sort_order', '0')" />
                        <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="form-checkbox" {{ old('is_active', true) ? 'checked' : '' }} />
                        <x-input-label for="is_active" :value="__('Active')" />
                    </div>

                    <details class="mt-4">
                        <summary class="text-sm font-medium cursor-pointer" style="color: var(--label-text)">{{ __('SEO Settings') }}</summary>
                        <div class="mt-4 space-y-4">
                            <div>
                                <x-input-label for="meta_title" :value="__('Meta Title')" />
                                <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title')" />
                                <p class="form-hint">Auto-filled from title. Edit to override.</p>
                            </div>
                            <div>
                                <x-input-label for="meta_description" :value="__('Meta Description')" />
                                <textarea id="meta_description" name="meta_description" class="form-textarea mt-1" rows="3">{{ old('meta_description') }}</textarea>
                                <p class="form-hint">Auto-filled from description. Edit to override.</p>
                            </div>
                            <div x-data="{ ogImage: '', ogImageAlt: '' }">
                                <x-input-label for="og_image" :value="__('OG Image')" />
                                <input type="hidden" name="og_image" id="og_image"
                                    :value="ogImage" x-on:input="ogImage = $event.target.value" />
                                <input type="hidden" name="og_image_alt" id="og_image_alt"
                                    :value="ogImageAlt" x-on:input="ogImageAlt = $event.target.value" />
                                <template x-if="ogImage">
                                    <div class="mb-2">
                                        <img :src="ogImage" :alt="ogImageAlt"
                                            class="rounded-lg"
                                            style="max-width:240px;max-height:120px;object-fit:cover">
                                        <p x-show="ogImageAlt" class="text-xs mt-1" x-text="'Alt: ' + ogImageAlt"
                                            style="color:var(--muted-text)"></p>
                                    </div>
                                </template>
                                <x-admin.media-picker target="og_image" collection="portfolio" />
                                <p class="form-hint">Defaults to cover photo.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="robots_index" name="robots_index" value="1" class="form-checkbox" {{ old('robots_index', true) ? 'checked' : '' }} />
                                <x-input-label for="robots_index" :value="__('Allow indexing')" />
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
</script>
@endpush
</x-admin.layouts.app>
