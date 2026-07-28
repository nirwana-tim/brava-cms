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
        <x-input-label for="name" :value="__('Name')" :required="true" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name ?? '')" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="slug" :value="__('Slug')" :required="true" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $category->slug ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
    </div>

    <div>
        <x-input-label for="type" :value="__('Type')" :required="true" />
        <select id="type" name="type" class="form-select mt-1">
            <option value="blog" {{ old('type', $category->type ?? '') === 'blog' ? 'selected' : '' }}>Blog</option>
            <option value="portfolio" {{ old('type', $category->type ?? '') === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('type')" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" class="form-textarea mt-1" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const title = document.getElementById('name');
        const slug = document.getElementById('slug');
        if (!title || !slug) return;
        if (slug.value) return;
        let slugEdited = false;
        slug.addEventListener('input', function () { if (this.value) slugEdited = true; });
        title.addEventListener('input', function () {
            if (slugEdited) return;
            slug.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        });
    });
</script>
@endpush
