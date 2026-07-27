<x-admin.layouts.app>
    <x-slot name="title">{{ __('Settings') }}</x-slot>

    <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
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

                        @forelse ($settings as $group => $groupSettings)
                            <div class="mb-8">
                                <h3 class="text-lg font-medium mb-4 capitalize" style="color: var(--heading-text)">{{ $group }}</h3>
                                <div class="space-y-4">
                                    @foreach ($groupSettings as $setting)
                                        <div>
                                            <x-input-label for="setting_{{ $setting->key }}" :value="__($setting->key)" />
                                            @if ($setting->type === 'text' || $setting->type === 'string')
                                                <x-text-input id="setting_{{ $setting->key }}" name="{{ $setting->key }}" type="text" class="mt-1 block w-full" :value="old($setting->key, $setting->value)" />
                                            @elseif ($setting->type === 'textarea')
                                                <textarea id="setting_{{ $setting->key }}" name="{{ $setting->key }}" class="form-textarea mt-1" rows="3">{{ old($setting->key, $setting->value) }}</textarea>
                                            @elseif ($setting->type === 'boolean' || $setting->type === 'bool')
                                                <label class="flex items-center gap-2 mt-1">
                                                    <input type="checkbox" name="{{ $setting->key }}" value="1" class="form-checkbox" {{ old($setting->key, $setting->value) ? 'checked' : '' }} />
                                                    <span class="text-sm" style="color: var(--label-text)">{{ __('Enabled') }}</span>
                                                </label>
                                            @else
                                                <x-text-input id="setting_{{ $setting->key }}" name="{{ $setting->key }}" type="text" class="mt-1 block w-full" :value="old($setting->key, $setting->value)" />
                                            @endif
                                            <x-input-error class="mt-2" :messages="$errors->get($setting->key)" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm" style="color: var(--muted-text)">No settings configured yet. Add settings via the database.</p>
                        @endforelse

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                            <a href="{{ route('dashboard') }}">
                                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
</x-admin.layouts.app>
