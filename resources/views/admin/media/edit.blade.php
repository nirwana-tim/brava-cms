<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Media') }}</x-slot>

    <div class="card">
        <div class="card-body">
                    <form action="{{ route('admin.media.update', $medium) }}" method="POST">
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
                            @if ($medium->path)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($medium->path) }}" alt="{{ $medium->alt_text }}" class="max-w-xs rounded shadow-sm">
                                </div>
                            @endif

                            <div>
                                <x-input-label for="alt_text" :value="__('Alt Text')" />
                                <x-text-input id="alt_text" name="alt_text" type="text" class="mt-1 block w-full" :value="old('alt_text', $medium->alt_text)" />
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
