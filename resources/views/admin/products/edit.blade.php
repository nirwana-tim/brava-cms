<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 p-4">
                                <div class="text-sm text-red-600 dark:text-red-400">
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
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $product->title)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="slug" :value="__('Slug')" />
                                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $product->slug)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('description', $product->description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <x-input-label for="content" :value="__('Content')" />
                                <textarea id="content" name="content" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="10">{{ old('content', $product->content) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('Price')" />
                                <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price', $product->price)" />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <div>
                                <x-input-label for="published_at" :value="__('Published At')" />
                                <x-text-input id="published_at" name="published_at" type="date" class="mt-1 block w-full" :value="old('published_at', $product->published_at?->format('Y-m-d'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('published_at')" />
                            </div>

                            <div>
                                <x-input-label :value="__('Categories')" />
                                <div class="mt-2 space-y-2">
                                    @forelse ($categories as $id => $name)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="category_ids[]" value="{{ $id }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ in_array($id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }} />
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No categories available.</p>
                                    @endforelse
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('category_ids')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ old('is_active', $product->is_active) ? 'checked' : '' }} />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Active') }}</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_featured" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Featured') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                            <a href="{{ route('admin.products.index') }}">
                                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
