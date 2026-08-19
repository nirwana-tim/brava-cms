<x-admin.layouts.app>
    <x-slot name="title">Settings</x-slot>

    <div class="card">
        <div class="card-body">
            @if (auth()->user()->isSuperAdmin())
                <div class="mb-6 rounded-lg border p-4" style="border-color: var(--card-border)">
                    <p class="text-sm" style="color: var(--heading-text)">
                        Global SEO defaults (<code>default_meta_title</code>, <code>default_meta_description</code>, <code>default_og_image</code>) kini dikelola di menu
                        <a href="{{ route('admin.page-seo.defaults') }}" style="color: var(--btn-edit-text); text-decoration: underline"><strong>SEO → Global Defaults</strong></a>.
                    </p>
                </div>
            @endif

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

                @php
                    $groupLabels = ['general' => 'General', 'contact' => 'Contact', 'social' => 'Social Media', 'seo' => 'SEO', 'adsense' => 'AdSense', 'system' => 'System'];
                    $restrictedSettings = [];
                    $adsenseEnabled = (bool) ($settings->flatten()->firstWhere('key', 'adsense_enabled')?->value ?? false);
                    $hideInactiveAdsense = ! auth()->user()->isSuperAdmin() && ! $adsenseEnabled;
                @endphp

                @foreach ($settings as $group => $groupSettings)
                    @php
                        $editable = $groupSettings->filter(fn ($s) => auth()->user()->can('update', $s));
                        $restricted = $groupSettings->filter(fn ($s) => ! auth()->user()->can('update', $s));
                        $restrictedSettings = array_merge($restrictedSettings, $restricted->all());
                    @endphp

                    @if ($editable->isNotEmpty())
                        <div class="mb-8">
                            <h3 class="section-title">{{ $groupLabels[$group] ?? ucfirst($group) }}</h3>
                            <div class="space-y-6">
                                @foreach ($editable as $setting)
                                    @if (in_array($setting->key, \App\Models\Setting::translatableKeys()))
                                        <div>
                                            <x-input-label :value="$setting->label" />
                                            <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label for="setting_{{ $setting->key }}_id" value="Indonesia" class="text-xs" />
                                                    @if ($setting->type === 'textarea')
                                                        <textarea id="setting_{{ $setting->key }}_id" name="{{ $setting->key }}[id]" class="form-textarea mt-1" rows="3">{{ old($setting->key.'.id', $setting->getTranslation('value', 'id', false)) }}</textarea>
                                                    @else
                                                        <x-text-input id="setting_{{ $setting->key }}_id" name="{{ $setting->key }}[id]" type="text" class="mt-1 block w-full" :value="old($setting->key.'.id', $setting->getTranslation('value', 'id', false))" />
                                                    @endif
                                                </div>
                                                <div>
                                                    <x-input-label for="setting_{{ $setting->key }}_en" value="English" class="text-xs" />
                                                    @if ($setting->type === 'textarea')
                                                        <textarea id="setting_{{ $setting->key }}_en" name="{{ $setting->key }}[en]" class="form-textarea mt-1" rows="3">{{ old($setting->key.'.en', $setting->getTranslation('value', 'en', false)) }}</textarea>
                                                    @else
                                                        <x-text-input id="setting_{{ $setting->key }}_en" name="{{ $setting->key }}[en]" type="text" class="mt-1 block w-full" :value="old($setting->key.'.en', $setting->getTranslation('value', 'en', false))" placeholder="Kosongkan untuk fallback ke Indonesia" />
                                                    @endif
                                                </div>
                                            </div>
                                            <x-input-error class="mt-2" :messages="$errors->get($setting->key.'.id')" />
                                            <x-input-error class="mt-2" :messages="$errors->get($setting->key.'.en')" />
                                            @if ($setting->hint)
                                                <p class="mt-1.5 text-xs" style="color: var(--muted-text)">{{ $setting->hint }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <div>
                                            <x-input-label for="setting_{{ $setting->key }}" :value="$setting->label" />
                                            @if ($setting->type === 'textarea')
                                                <textarea id="setting_{{ $setting->key }}" name="{{ $setting->key }}" class="form-textarea mt-1" rows="3">{{ old($setting->key, $setting->value) }}</textarea>
                                            @elseif ($setting->type === 'boolean' || $setting->type === 'bool')
                                                <div class="mt-1">
                                                    <x-admin.toggle name="{{ $setting->key }}" :checked="old($setting->key, $setting->value)" label="Enabled" :hint="$setting->hint" />
                                                </div>
                                            @else
                                                <x-text-input id="setting_{{ $setting->key }}" name="{{ $setting->key }}" type="text" class="mt-1 block w-full" :value="old($setting->key, $setting->value)" />
                                            @endif
                                            <x-input-error class="mt-2" :messages="$errors->get($setting->key)" />
                                            @unless ($setting->type === 'boolean' || $setting->type === 'bool')
                                                @if ($setting->hint)
                                                    <p class="mt-1.5 text-xs" style="color: var(--muted-text)">{{ $setting->hint }}</p>
                                                @endif
                                            @endunless
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                @php
                    $restrictedSettings = array_values(array_filter($restrictedSettings, fn ($setting) => ! ($hideInactiveAdsense && $setting->group === 'adsense')));
                @endphp

                @if (count($restrictedSettings) > 0)
                    <div class="mb-8">
                        <h3 class="section-title">Technical Settings</h3>
                        <div class="card" style="background: transparent; border: 1px solid var(--card-border)">
                            <div class="card-body">
                                <div class="space-y-4">
                                    @foreach ($restrictedSettings as $setting)
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-text)">{{ $setting->label }}</p>
                                            <p class="text-sm" style="color: var(--heading-text)">{{ $setting->value ?: '-' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-4 text-xs" style="color: var(--flash-error-text)">
                                    Untuk perubahan pengaturan ini, silakan hubungi developer.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>Save Settings</x-primary-button>
                    <a href="{{ route('admin.dashboard') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
