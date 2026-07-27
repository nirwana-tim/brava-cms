<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Service') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.services.update', $service) }}" method="POST">
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
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $service->title)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="slug" :value="__('Slug')" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $service->slug)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="form-textarea mt-1" rows="3">{{ old('description', $service->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <x-admin.rich-text name="content" :value="old('content', $service->content)" />

                    <div>
                        <x-input-label for="photo" :value="__('Photo URL')" />
                        <x-text-input id="photo" name="photo" type="text" class="mt-1 block w-full" :value="old('photo', $service->photo)"/>
                        <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                    </div>

                    <div>
                        <x-input-label for="published_at" :value="__('Published At')" />
                        <x-text-input id="published_at" name="published_at" type="date" class="mt-1 block w-full" :value="old('published_at', $service->published_at?->format('Y-m-d'))" />
                        <x-input-error class="mt-2" :messages="$errors->get('published_at')" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="form-checkbox" {{ old('is_active', $service->is_active) ? 'checked' : '' }} />
                        <x-input-label for="is_active" :value="__('Active')" />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('admin.services.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
