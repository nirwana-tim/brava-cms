<x-admin.layouts.app>
    <x-slot name="title">{{ __('Settings') }}</x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 capitalize">{{ $group }}</h3>
                                <div class="space-y-4">
                                    @foreach ($groupSettings as $setting)
                                        <div>
                                            <x-input-label for="setting_{{ $setting->key }}" :value="__($setting->key)" />
                                            @if ($setting->type === 'text' || $setting->type === 'string')
                                                <x-text-input id="setting_{{ $setting->key }}" name="{{ $setting->key }}" type="text" class="mt-1 block w-full" :value="old($setting->key, $setting->value)" />
                                            @elseif ($setting->type === 'textarea')
                                                <textarea id="setting_{{ $setting->key }}" name="{{ $setting->key }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old($setting->key, $setting->value) }}</textarea>
                                            @elseif ($setting->type === 'boolean' || $setting->type === 'bool')
                                                <label class="flex items-center gap-2 mt-1">
                                                    <input type="checkbox" name="{{ $setting->key }}" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ old($setting->key, $setting->value) ? 'checked' : '' }} />
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Enabled') }}</span>
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
                            <p class="text-gray-500 dark:text-gray-400">No settings configured yet. Add settings via the database.</p>
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
