<x-admin.layouts.app>
    <x-slot name="title">{{ $service->title }}</x-slot>

    <div x-data="{ langTab: 'id' }">
        <div class="flex items-center gap-2 border-b pb-2 mb-6" style="border-color: var(--table-border)">
            <button type="button"
                    @click="langTab = 'id'"
                    :style="langTab === 'id' ? 'background-color: var(--btn-primary-bg); color: var(--btn-primary-text); font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.2);' : 'background-color: var(--btn-secondary-bg); color: var(--btn-secondary-text); border: 1px solid var(--btn-secondary-border);'"
                    class="px-4 py-2 text-sm rounded-lg transition flex items-center gap-2 cursor-pointer">
                Bahasa Indonesia (Default)
            </button>
            <button type="button"
                    @click="langTab = 'en'"
                    :style="langTab === 'en' ? 'background-color: var(--btn-primary-bg); color: var(--btn-primary-text); font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.2);' : 'background-color: var(--btn-secondary-bg); color: var(--btn-secondary-text); border: 1px solid var(--btn-secondary-border);'"
                    class="px-4 py-2 text-sm rounded-lg transition flex items-center gap-2 cursor-pointer">
                English (Inggris)
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $service->title }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.services.edit', $service) }}">
                        <x-secondary-button type="button" class="gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </x-secondary-button>
                    </a>
                    <a href="{{ route('admin.services.index') }}">
                        <x-primary-button type="button">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back') }}
                        </x-primary-button>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- ID Tab -->
                <div x-show="langTab === 'id'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="section-title">Slug</p>
                            <p style="color: var(--table-text)">{{ $service->getTranslation('slug', 'id', false) ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="section-title">Active</p>
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="space-y-6">
                            <div>
                                <p class="section-title">Sort Order</p>
                                <p style="color: var(--table-text)">{{ $service->sort_order ?? '0' }}</p>
                            </div>
                            <div>
                                <p class="section-title">Description</p>
                                <p class="mt-2" style="color: var(--table-text); line-height: 1.8;">
                                    {{ $service->getTranslation('description', 'id', false) ?: '-' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <p class="section-title">Photo</p>
                            @if ($service->photo)
                                <div class="mt-2 overflow-hidden rounded-lg shadow-sm" style="max-width: 320px; border: 1px solid var(--card-border);">
                                    <img src="{{ $service->photo }}" alt="{{ $service->getTranslation('photo_alt', 'id', false) ?: $service->getTranslation('title', 'id', false) }}"
                                        class="w-full object-cover" style="max-height: 200px;">
                                </div>
                            @else
                                <div class="mt-2 overflow-hidden rounded-lg shadow-sm" style="width: 100%; max-width: 320px; height: 160px; background-color: #E1E1E1; border: 1px solid var(--card-border);">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-4xl font-bold" style="color: #9CA3AF;">{{ mb_strtoupper(mb_substr($service->getTranslation('title', 'id', false), 0, 1)) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="section-title">Photo Alt Text</p>
                        <p style="color: var(--table-text)">{{ $service->getTranslation('photo_alt', 'id', false) ?: '-' }}</p>
                    </div>
                </div>

                <!-- EN Tab -->
                <div x-show="langTab === 'en'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="section-title">Slug</p>
                            <p style="color: var(--table-text)">{{ $service->getTranslation('slug', 'en', false) ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="section-title">Active</p>
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="space-y-6">
                            <div>
                                <p class="section-title">Sort Order</p>
                                <p style="color: var(--table-text)">{{ $service->sort_order ?? '0' }}</p>
                            </div>
                            <div>
                                <p class="section-title">Description (EN)</p>
                                <p class="mt-2" style="color: var(--table-text); line-height: 1.8;">
                                    {{ $service->getTranslation('description', 'en', false) ?: 'No English description available.' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <p class="section-title">Photo</p>
                            @if ($service->photo)
                                <div class="mt-2 overflow-hidden rounded-lg shadow-sm" style="max-width: 320px; border: 1px solid var(--card-border);">
                                    <img src="{{ $service->photo }}" alt="{{ $service->getTranslation('photo_alt', 'en', false) ?: $service->getTranslation('title', 'en', false) }}"
                                        class="w-full object-cover" style="max-height: 200px;">
                                </div>
                            @else
                                <div class="mt-2 overflow-hidden rounded-lg shadow-sm" style="width: 100%; max-width: 320px; height: 160px; background-color: #E1E1E1; border: 1px solid var(--card-border);">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-4xl font-bold" style="color: #9CA3AF;">{{ mb_strtoupper(mb_substr($service->getTranslation('title', 'en', false) ?: $service->getTranslation('title', 'id', false), 0, 1)) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="section-title">Photo Alt Text (EN)</p>
                        <p style="color: var(--table-text)">{{ $service->getTranslation('photo_alt', 'en', false) ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>