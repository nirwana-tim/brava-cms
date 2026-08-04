<div class="space-y-6">
    @if ($errors->any())
        <div class="rounded-lg alert-error border p-4">
            <div class="text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div>
        <x-input-label for="type" :value="__('Type')" :required="true" />
        <select id="type" name="type" class="form-select mt-1">
            <option value="blog" {{ old('type', $category->type ?? '') === 'blog' ? 'selected' : '' }}>Blog</option>
            <option value="portfolio" {{ old('type', $category->type ?? '') === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('type')" />
    </div>

    <x-admin.language-tabs>
        <!-- ID Tab -->
        <div x-show="langTab === 'id'" class="space-y-6">
            <div>
                <x-input-label for="name_id" :value="__('Name (ID)')" :required="true" />
                <x-text-input id="name_id" name="name[id]" type="text" class="mt-1 block w-full" :value="old('name.id', isset($category) ? $category->getTranslation('name', 'id', false) : '')" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('name.id')" />
            </div>

            <div>
                <x-input-label for="slug_id" :value="__('Slug (ID)')" :required="true" />
                <x-text-input id="slug_id" name="slug[id]" type="text" class="mt-1 block w-full" :value="old('slug.id', isset($category) ? $category->getTranslation('slug', 'id', false) : '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('slug.id')" />
            </div>

            <div>
                <x-input-label for="description_id" :value="__('Description (ID)')" />
                <textarea id="description_id" name="description[id]" class="form-textarea mt-1" rows="3">{{ old('description.id', isset($category) ? $category->getTranslation('description', 'id', false) : '') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description.id')" />
            </div>
        </div>

        <!-- EN Tab -->
        <div x-show="langTab === 'en'" class="space-y-6">
            <div>
                <x-input-label for="name_en" :value="__('Name (EN - English)')" />
                <x-text-input id="name_en" name="name[en]" type="text" class="mt-1 block w-full" :value="old('name.en', isset($category) ? $category->getTranslation('name', 'en', false) : '')" placeholder="Leave blank to fallback to Indonesian" />
                <x-input-error class="mt-2" :messages="$errors->get('name.en')" />
            </div>

            <div>
                <x-input-label for="slug_en" :value="__('Slug (EN - English)')" />
                <x-text-input id="slug_en" name="slug[en]" type="text" class="mt-1 block w-full" :value="old('slug.en', isset($category) ? $category->getTranslation('slug', 'en', false) : '')" placeholder="e.g. tech-tips" />
                <x-input-error class="mt-2" :messages="$errors->get('slug.en')" />
            </div>

            <div>
                <x-input-label for="description_en" :value="__('Description (EN - English)')" />
                <textarea id="description_en" name="description[en]" class="form-textarea mt-1" rows="3">{{ old('description.en', isset($category) ? $category->getTranslation('description', 'en', false) : '') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description.en')" />
            </div>
        </div>
    </x-admin.language-tabs>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleId = document.getElementById('name_id');
        const slugId = document.getElementById('slug_id');
        if (titleId && slugId) {
            let slugEdited = false;
            slugId.addEventListener('input', function () { if (this.value) slugEdited = true; });
            titleId.addEventListener('input', function () {
                if (slugEdited) return;
                slugId.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            });
        }
        const titleEn = document.getElementById('name_en');
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
