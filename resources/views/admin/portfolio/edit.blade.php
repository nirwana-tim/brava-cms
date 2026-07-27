<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Portfolio Item') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.portfolio.update', $portfolio) }}" method="POST">
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
                        <x-input-label for="service_id" :value="__('Service')" />
                        <select id="service_id" name="service_id" class="form-select mt-1">
                            <option value="">-- Select Service --</option>
                            @foreach ($services as $id => $name)
                                <option value="{{ $id }}" {{ old('service_id', $portfolio->service_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('service_id')" />
                    </div>

                    <div>
                        <x-input-label for="title" :value="__('Title')" :required="true" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $portfolio->title)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="slug" :value="__('Slug')" :required="true" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $portfolio->slug)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="form-textarea mt-1" rows="3">{{ old('description', $portfolio->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <x-admin.rich-text name="content" :value="old('content', $portfolio->content)" />

                    <div>
                        <x-input-label for="client" :value="__('Client')" />
                        <x-text-input id="client" name="client" type="text" class="mt-1 block w-full" :value="old('client', $portfolio->client)" />
                        <x-input-error class="mt-2" :messages="$errors->get('client')" />
                    </div>

                    <div>
                        <x-input-label for="project_url" :value="__('Project URL')" />
                        <x-text-input id="project_url" name="project_url" type="url" class="mt-1 block w-full" :value="old('project_url', $portfolio->project_url)" />
                        <x-input-error class="mt-2" :messages="$errors->get('project_url')" />
                    </div>

                    <div>
                        <x-input-label for="completed_at" :value="__('Completed At')" />
                        <x-text-input id="completed_at" name="completed_at" type="date" class="mt-1 block w-full" :value="old('completed_at', $portfolio->completed_at?->format('Y-m-d'))" />
                        <x-input-error class="mt-2" :messages="$errors->get('completed_at')" />
                    </div>

                    <div>
                        <x-input-label for="sort_order" :value="__('Sort Order')" />
                        <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full" :value="old('sort_order', $portfolio->sort_order ?? '0')" />
                        <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="form-checkbox" {{ old('is_active', $portfolio->is_active) ? 'checked' : '' }} />
                        <x-input-label for="is_active" :value="__('Active')" />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('admin.portfolio.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
