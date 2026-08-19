<x-admin.layouts.app>
    <x-slot name="title">{{ __('Create Team Member') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.team.store') }}" method="POST">
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
                            <x-input-label for="name_id" :value="__('Name (ID)')" :required="true" />
                            <x-text-input id="name_id" name="name[id]" type="text" class="mt-1 block w-full" :value="old('name.id')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name.id')" />
                        </div>

                        <div>
                            <x-input-label for="position_id" :value="__('Position (ID)')" :required="true" />
                            <x-text-input id="position_id" name="position[id]" type="text" class="mt-1 block w-full" :value="old('position.id')" required placeholder="e.g. Kepala Produksi" />
                            <x-input-error class="mt-2" :messages="$errors->get('position.id')" />
                        </div>
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div>
                            <x-input-label for="name_en" :value="__('Name (EN - English)')" />
                            <x-text-input id="name_en" name="name[en]" type="text" class="mt-1 block w-full" :value="old('name.en')" placeholder="Leave blank to fallback to Indonesian" />
                            <x-input-error class="mt-2" :messages="$errors->get('name.en')" />
                        </div>

                        <div>
                            <x-input-label for="position_en" :value="__('Position (EN - English)')" />
                            <x-text-input id="position_en" name="position[en]" type="text" class="mt-1 block w-full" :value="old('position.en')" placeholder="e.g. Head of Production" />
                            <x-input-error class="mt-2" :messages="$errors->get('position.en')" />
                        </div>
                    </div>
                </x-admin.language-tabs>

                <div x-data="{ createAccount: {!! old('create_user_account', true) ? 'true' : 'false' !!} }" class="mt-6 space-y-6 border-t pt-6">
                    <div>
                        <x-input-label for="avatar" :value="__('Avatar')" />
                        <input type="hidden" name="avatar" id="avatar" value="{{ old('avatar') }}" />
                        <x-admin.image-upload target="avatar" />
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>

                    <div>
                        <x-input-label for="email">
                            {{ __('Email') }}<span x-show="createAccount" class="ml-0.5 text-red-500 dark:text-red-400">*</span>
                        </x-input-label>
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                        <p class="form-hint mt-1">Wajib diisi jika membuat akun login.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="create_user_account" value="0">
                        <input id="create_user_account" name="create_user_account" type="checkbox" value="1" class="rounded" x-model="createAccount" x-init="$el.checked = createAccount">
                        <label for="create_user_account" class="text-sm font-medium" style="color: var(--label-text)">Create Login Account</label>
                        <x-input-error class="mt-2" :messages="$errors->get('create_user_account')" />
                    </div>

                    @if (auth()->user()->isSuperAdmin())
                        <div x-show="createAccount">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="form-select mt-1">
                                <option value="admin" {{ old('role', 'admin') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('role')" />
                        </div>
                    @else
                        <input type="hidden" name="role" value="{{ old('role', 'staff') }}">
                    @endif

                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div x-show="createAccount">
                        <x-input-label for="password" :value="__('Password')" :required="true" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div x-show="createAccount">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" :required="true" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>

                    <div>
                        <x-admin.toggle name="is_active" :checked="old('is_active', true)" label="Active" hint="Show this team member on the website. Turning this off also disables their login account." />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    <a href="{{ route('admin.team.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
