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

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="slug" :value="__('Slug')" />
                                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                            </div>

                            <div>
                                <x-input-label for="excerpt" :value="__('Excerpt')" />
                                <textarea id="excerpt" name="excerpt" class="form-textarea mt-1" rows="3">{{ old('excerpt') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('excerpt')" />
                            </div>

                            <x-admin.rich-text name="content" :value="old('content')" />

                            <div>
                                <x-input-label for="featured_image" :value="__('Featured Image URL')" />
                                <x-text-input id="featured_image" name="featured_image" type="text" class="mt-1 block w-full" :value="old('featured_image')" />
                                <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                            </div>

                            <div>
                                <x-input-label for="published_at" :value="__('Published At')" />
                                <x-text-input id="published_at" name="published_at" type="date" class="mt-1 block w-full" :value="old('published_at')" />
                                <x-input-error class="mt-2" :messages="$errors->get('published_at')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="form-select mt-1">
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div>
                                <x-input-label :value="__('Categories')" />
                                <div class="mt-2 space-y-2">
                                    @forelse ($categories as $id => $name)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="category_ids[]" value="{{ $id }}" class="form-checkbox" {{ in_array($id, old('category_ids', [])) ? 'checked' : '' }} />
                                            <span class="text-sm" style="color: var(--label-text)">{{ $name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm" style="color: var(--muted-text)">No categories available. <a href="{{ route('admin.categories.create') }}" class="hover:underline" style="color: var(--btn-edit-text)">Create one</a>.</p>
                                    @endforelse
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('category_ids')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_featured" value="1" class="form-checkbox" {{ old('is_featured') ? 'checked' : '' }} />
                                    <span class="text-sm" style="color: var(--label-text)">{{ __('Featured') }}</span>
                                </label>
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
</x-admin.layouts.app>
