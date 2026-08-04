<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Testimonial') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST">
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

                <x-admin.language-tabs>
                    <!-- ID Tab -->
                    <div x-show="langTab === 'id'" class="space-y-6">
                        <div>
                            <x-input-label for="client_name_id" :value="__('Company / Organization (ID)')" :required="true" />
                            <x-text-input id="client_name_id" name="client_name[id]" type="text" class="mt-1 block w-full" :value="old('client_name.id', $testimonial->getTranslation('client_name', 'id', false))" required />
                            <p class="form-hint">Nama perusahaan, organisasi, atau instansi klien.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('client_name.id')" />
                        </div>

                        <div>
                            <x-input-label for="content_id" :value="__('Testimonial (ID)')" :required="true" />
                            <textarea id="content_id" name="content[id]" class="form-textarea mt-1" rows="5" required>{{ old('content.id', $testimonial->getTranslation('content', 'id', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content.id')" />
                        </div>
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div>
                            <x-input-label for="client_name_en" :value="__('Company / Organization (EN - English)')" />
                            <x-text-input id="client_name_en" name="client_name[en]" type="text" class="mt-1 block w-full" :value="old('client_name.en', $testimonial->getTranslation('client_name', 'en', false))" placeholder="Leave blank to fallback to Indonesian" />
                            <x-input-error class="mt-2" :messages="$errors->get('client_name.en')" />
                        </div>

                        <div>
                            <x-input-label for="content_en" :value="__('Testimonial (EN - English)')" />
                            <textarea id="content_en" name="content[en]" class="form-textarea mt-1" rows="5">{{ old('content.en', $testimonial->getTranslation('content', 'en', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content.en')" />
                        </div>
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div>
                        <x-input-label for="rating" :value="__('Rating (1-5)')" />
                        <x-text-input id="rating" name="rating" type="number" min="1" max="5" class="mt-1 block w-full" :value="old('rating', $testimonial->rating)" />
                        <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                    </div>

                    <div>
                        <x-input-label for="avatar" :value="__('Avatar / Logo')" />
                        <input type="hidden" name="avatar" id="avatar" value="{{ old('avatar', $testimonial->avatar) }}" />
                        <x-admin.image-upload target="avatar" />
                        <x-admin.alt-input field="avatar_alt[id]" :value="old('avatar_alt.id', $testimonial->getTranslation('avatar_alt', 'id', false))" label="Avatar Alt Text (ID)" />
                        <x-admin.alt-input field="avatar_alt[en]" :value="old('avatar_alt.en', $testimonial->getTranslation('avatar_alt', 'en', false))" label="Avatar Alt Text (EN - English)" />
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>

                    <div>
                        <x-input-label for="sort_order" :value="__('Sort Order')" />
                        <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full" :value="old('sort_order', $testimonial->sort_order ?? '0')" />
                        <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
                    </div>

                    <div>
                        <x-admin.toggle name="is_active" :checked="old('is_active', $testimonial->is_active)" label="Active" />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('admin.testimonials.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
