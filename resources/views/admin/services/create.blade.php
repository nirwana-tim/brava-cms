<x-admin.layouts.app>
    <x-slot name="title">{{ __('Create Service') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.services.store') }}" method="POST">
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
                        <x-admin.media-picker target="photo" collection="services" />
                        <x-input-error class="mt-2" :messages="$errors->get('photo')" />
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
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <a href="{{ route('admin.services.index') }}">
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
        if (!title || !slug) return;
        let slugEdited = false;
        slug.addEventListener('input', function () { if (this.value) slugEdited = true; });
        title.addEventListener('input', function () {
            if (slugEdited) return;
            slug.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        });
    });
</script>
@endpush
</x-admin.layouts.app>
