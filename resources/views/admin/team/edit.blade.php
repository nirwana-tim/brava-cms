<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Team Member') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.team.update', $team) }}" method="POST">
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
                            <x-input-label for="name_id" :value="__('Name (ID)')" :required="true" />
                            <x-text-input id="name_id" name="name[id]" type="text" class="mt-1 block w-full" :value="old('name.id', $team->getTranslation('name', 'id', false))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name.id')" />
                        </div>

                        <div>
                            <x-input-label for="position_id" :value="__('Position (ID)')" :required="true" />
                            <x-text-input id="position_id" name="position[id]" type="text" class="mt-1 block w-full" :value="old('position.id', $team->getTranslation('position', 'id', false))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('position.id')" />
                        </div>

                        <div>
                            <x-input-label for="bio_id" :value="__('Bio / Short Description (ID)')" />
                            <textarea id="bio_id" name="bio[id]" class="form-textarea mt-1 w-full" rows="3">{{ old('bio.id', $team->getTranslation('bio', 'id', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('bio.id')" />
                        </div>
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div>
                            <x-input-label for="name_en" :value="__('Name (EN - English)')" />
                            <x-text-input id="name_en" name="name[en]" type="text" class="mt-1 block w-full" :value="old('name.en', $team->getTranslation('name', 'en', false))" placeholder="Leave blank to fallback to Indonesian" />
                            <x-input-error class="mt-2" :messages="$errors->get('name.en')" />
                        </div>

                        <div>
                            <x-input-label for="position_en" :value="__('Position (EN - English)')" />
                            <x-text-input id="position_en" name="position[en]" type="text" class="mt-1 block w-full" :value="old('position.en', $team->getTranslation('position', 'en', false))" placeholder="e.g. Head of Production" />
                            <x-input-error class="mt-2" :messages="$errors->get('position.en')" />
                        </div>

                        <div>
                            <x-input-label for="bio_en" :value="__('Bio / Short Description (EN - English)')" />
                            <textarea id="bio_en" name="bio[en]" class="form-textarea mt-1 w-full" rows="3">{{ old('bio.en', $team->getTranslation('bio', 'en', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('bio.en')" />
                        </div>
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div>
                        <x-input-label for="avatar" :value="__('Avatar')" />
                        <input type="hidden" name="avatar" id="avatar" value="{{ old('avatar', $team->avatar) }}" />
                        <x-admin.image-upload target="avatar" />
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $team->email)" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="role" :value="__('Role')" />
                        <select id="role" name="role" class="form-select mt-1">
                            @if (auth()->user()->isSuperAdmin())
                                <option value="admin" {{ old('role', $team->user?->role?->value ?? 'admin') === 'admin' ? 'selected' : '' }}>Admin</option>
                            @endif
                            <option value="staff" {{ old('role', $team->user?->role?->value ?? 'staff') === 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $team->phone)" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div>
                        <x-input-label for="sort_order" :value="__('Sort Order')" />
                        <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full" :value="old('sort_order', $team->sort_order ?? '0')" />
                        <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
                    </div>

                    <div>
                        <x-admin.toggle name="is_active" :checked="old('is_active', $team->is_active)" label="Active" />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('admin.team.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                    <a href="{{ route('admin.team.reset-password', $team) }}" class="ms-auto">
                        <x-secondary-button type="button">{{ __('Reset Password') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
