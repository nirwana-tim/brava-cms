<x-admin.layouts.app>
    <x-slot name="title">{{ __('Global SEO Defaults') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold" style="color: var(--heading-text)">Global SEO Defaults</h2>
                <p class="text-xs mt-1" style="color: var(--muted-text)">Fallback seluruh situs: hanya dipakai saat suatu halaman tidak memiliki meta sendiri (root layout &amp; halaman dinamis). SEO tiap halaman statis diatur di <a href="{{ route('admin.page-seo.index') }}" style="color: var(--btn-edit-text); text-decoration: underline">SEO → daftar halaman</a>.</p>
            </div>

            <form action="{{ route('admin.page-seo.defaults.update') }}" method="POST">
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

                @php
                    $title = $defaults['default_meta_title'];
                    $description = $defaults['default_meta_description'];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="default_meta_title_id" :value="__('Default Meta Title (ID)')" />
                        <x-text-input id="default_meta_title_id" name="default_meta_title[id]" type="text" class="mt-1 block w-full" :value="old('default_meta_title.id', $title->getTranslation('value', 'id', false))" placeholder="Max 70 karakter" />
                        <x-input-error class="mt-2" :messages="$errors->get('default_meta_title.id')" />
                    </div>
                    <div>
                        <x-input-label for="default_meta_title_en" :value="__('Default Meta Title (EN)')" />
                        <x-text-input id="default_meta_title_en" name="default_meta_title[en]" type="text" class="mt-1 block w-full" :value="old('default_meta_title.en', $title->getTranslation('value', 'en', false))" placeholder="Max 70 characters" />
                        <x-input-error class="mt-2" :messages="$errors->get('default_meta_title.en')" />
                    </div>
                </div>
                <p class="form-hint mt-1">Judul standar (root title) untuk Google &amp; OpenGraph saat halaman tidak punya meta title khusus.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <x-input-label for="default_meta_description_id" :value="__('Default Meta Description (ID)')" />
                        <textarea id="default_meta_description_id" name="default_meta_description[id]" class="form-textarea mt-1 w-full" rows="3" placeholder="Max 160 karakter">{{ old('default_meta_description.id', $description->getTranslation('value', 'id', false)) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('default_meta_description.id')" />
                    </div>
                    <div>
                        <x-input-label for="default_meta_description_en" :value="__('Default Meta Description (EN)')" />
                        <textarea id="default_meta_description_en" name="default_meta_description[en]" class="form-textarea mt-1 w-full" rows="3" placeholder="Max 160 characters">{{ old('default_meta_description.en', $description->getTranslation('value', 'en', false)) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('default_meta_description.en')" />
                    </div>
                </div>
                <p class="form-hint mt-1">Deskripsi standar untuk hasil pencarian Google saat halaman tidak punya deskripsi khusus.</p>

                <div class="mt-4">
                    <x-input-label for="default_og_image" :value="__('Default OpenGraph Image')" />
                    <input type="hidden" name="default_og_image" id="default_og_image" value="{{ old('default_og_image', $defaults['default_og_image']->value) }}" />
                    <x-admin.media-picker target="default_og_image" collection="seo" />
                    <p class="form-hint mt-1">Gambar OG default untuk halaman yang tidak punya gambar sendiri. Rasio ideal 1200x630 px.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('default_og_image')" />
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('admin.page-seo.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>