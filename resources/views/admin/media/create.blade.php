<x-admin.layouts.app>
    <x-slot name="title">{{ __('Upload Media') }}</x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
                    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
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
                                <x-input-label for="file" :value="__('File')" />
                                <input id="file" name="file" type="file" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800" required />
                                <x-input-error class="mt-2" :messages="$errors->get('file')" />
                            </div>

                            <div>
                                <x-input-label for="alt_text" :value="__('Alt Text')" />
                                <x-text-input id="alt_text" name="alt_text" type="text" class="mt-1 block w-full" :value="old('alt_text')" />
                                <x-input-error class="mt-2" :messages="$errors->get('alt_text')" />
                            </div>

                            <div>
                                <x-input-label for="collection" :value="__('Collection')" />
                                <x-text-input id="collection" name="collection" type="text" class="mt-1 block w-full" :value="old('collection')" placeholder="e.g., products, blogs, general" />
                                <x-input-error class="mt-2" :messages="$errors->get('collection')" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Upload') }}</x-primary-button>
                            <a href="{{ route('admin.media.index') }}">
                                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
</x-admin.layouts.app>
