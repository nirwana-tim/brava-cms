<x-admin.layouts.app>
    <x-slot name="title">{{ __('Create Testimonial') }}</x-slot>

    <div class="card">
        <div class="card-body">
                    <form action="{{ route('admin.testimonials.store') }}" method="POST">
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
                                <x-input-label for="client_name" :value="__('Client Name')" :required="true" />
                                <x-text-input id="client_name" name="client_name" type="text" class="mt-1 block w-full" :value="old('client_name')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('client_name')" />
                            </div>

                            <div>
                                <x-input-label for="company" :value="__('Company')" />
                                <x-text-input id="company" name="company" type="text" class="mt-1 block w-full" :value="old('company')" />
                                <x-input-error class="mt-2" :messages="$errors->get('company')" />
                            </div>

                            <div>
                                <x-input-label for="content" :value="__('Testimonial')" :required="true" />
                                <textarea id="content" name="content" class="form-textarea mt-1" rows="5" required>{{ old('content') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            <div>
                                <x-input-label for="rating" :value="__('Rating (1-5)')" />
                                <x-text-input id="rating" name="rating" type="number" min="1" max="5" class="mt-1 block w-full" :value="old('rating')" />
                                <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                            </div>

                            <div x-data="{ avatarUrl: '', avatarAlt: '' }">
                                <x-input-label for="avatar" :value="__('Avatar')" />
                                <input type="hidden" name="avatar" id="avatar"
                                    :value="avatarUrl" x-on:input="avatarUrl = $event.target.value" />
                                <input type="hidden" name="avatar_alt" id="avatar_alt"
                                    :value="avatarAlt" x-on:input="avatarAlt = $event.target.value" />
                                <template x-if="avatarUrl">
                                    <div class="mb-2">
                                        <img :src="avatarUrl" :alt="avatarAlt"
                                            class="rounded-full mb-2"
                                            style="width:64px;height:64px;object-fit:cover">
                                        <p x-show="avatarAlt" class="text-xs mt-1" x-text="'Alt: ' + avatarAlt"
                                            style="color:var(--muted-text)"></p>
                                    </div>
                                </template>
                                <x-admin.media-picker target="avatar" collection="testimonials" />
                                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
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
                            <a href="{{ route('admin.testimonials.index') }}">
                                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
</x-admin.layouts.app>
