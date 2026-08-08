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

                <x-admin.language-tabs>
                    <!-- ID Tab -->
                    <div x-show="langTab === 'id'" class="space-y-6">
                        <div>
                            <x-input-label for="title_id" :value="__('Title (ID)')" :required="true" />
                            <x-text-input id="title_id" name="title[id]" type="text" class="mt-1 block w-full" :value="old('title.id')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title.id')" />
                        </div>

                        <div>
                            <x-input-label for="slug_id" :value="__('Slug (ID)')" :required="true" />
                            <x-text-input id="slug_id" name="slug[id]" type="text" class="mt-1 block w-full" :value="old('slug.id')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('slug.id')" />
                        </div>

                        <div>
                            <x-input-label for="description_id" :value="__('Description (ID)')" />
                            <textarea id="description_id" name="description[id]" class="form-textarea mt-1" rows="3">{{ old('description.id') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.id')" />
                        </div>

                        <div>
                            <x-admin.alt-input field="photo_alt[id]" :value="old('photo_alt.id')" label="Photo Alt Text (ID)" />
                        </div>
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div>
                            <x-input-label for="title_en" :value="__('Title (EN - English)')" />
                            <x-text-input id="title_en" name="title[en]" type="text" class="mt-1 block w-full" :value="old('title.en')" placeholder="Leave blank to fallback to Indonesian" />
                            <x-input-error class="mt-2" :messages="$errors->get('title.en')" />
                        </div>

                        <div>
                            <x-input-label for="slug_en" :value="__('Slug (EN - English)')" />
                            <x-text-input id="slug_en" name="slug[en]" type="text" class="mt-1 block w-full" :value="old('slug.en')" placeholder="e.g. corporate-uniforms" />
                            <x-input-error class="mt-2" :messages="$errors->get('slug.en')" />
                        </div>

                        <div>
                            <x-input-label for="description_en" :value="__('Description (EN - English)')" />
                            <textarea id="description_en" name="description[en]" class="form-textarea mt-1" rows="3">{{ old('description.en') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.en')" />
                        </div>

                        <div>
                            <x-admin.alt-input field="photo_alt[en]" :value="old('photo_alt.en')" label="Photo Alt Text (EN - English)" />
                        </div>
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div x-data="{ photoUrl: @js(old('photo')), photoAlt: @js(old('photo_alt.id')) }">
                        <x-input-label for="photo" :value="__('Photo')" />
                        <input type="hidden" name="photo" id="photo" value="{{ old('photo') }}" />
                        <template x-if="photoUrl">
                            <div class="mb-2">
                                <img :src="photoUrl" :alt="photoAlt" class="rounded-lg" style="max-width:240px;max-height:160px;object-fit:cover">
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

                    <div>
                        <x-admin.toggle name="is_active" :checked="old('is_active', true)" label="Active" />
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
        const titleId = document.getElementById('title_id');
        const slugId = document.getElementById('slug_id');
        if (titleId && slugId) {
            let slugEdited = false;
            slugId.addEventListener('input', function () { if (this.value) slugEdited = true; });
            titleId.addEventListener('input', function () {
                if (slugEdited) return;
                slugId.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            });
        }
        const titleEn = document.getElementById('title_en');
        const slugEn = document.getElementById('slug_en');
        if (titleEn && slugEn) {
            let slugEditedEn = false;
            slugEn.addEventListener('input', function () { if (this.value) slugEditedEn = true; });
            titleEn.addEventListener('input', function () {
                if (slugEditedEn) return;
                slugEn.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            });
        }
    });
</script>
@endpush
</x-admin.layouts.app>
