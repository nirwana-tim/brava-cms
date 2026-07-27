<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Media') }}</x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
                    <form action="{{ route('admin.media.update', $media) }}" method="POST">
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
                            @if ($media->path)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($media->path) }}" alt="{{ $media->alt_text }}" class="max-w-xs rounded shadow-sm">
                                </div>
                            @endif

                            <div>
                                <x-input-label for="alt_text" :value="__('Alt Text')" />
                                <x-text-input id="alt_text" name="alt_text" type="text" class="mt-1 block w-full" :value="old('alt_text', $media->alt_text)" />
                                <x-input-error class="mt-2" :messages="$errors->get('alt_text')" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                            <a href="{{ route('admin.media.index') }}">
                                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
</x-admin.layouts.app>
