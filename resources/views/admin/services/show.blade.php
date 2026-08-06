<x-admin.layouts.app>
    <x-slot name="title">{{ $service->title }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $service->title }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.services.edit', $service) }}">
                    <x-secondary-button type="button">{{ __('Edit') }}</x-secondary-button>
                </a>
                <a href="{{ route('admin.services.index') }}">
                    <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="section-title">Slug (ID)</p>
                    <p style="color: var(--table-text)">{{ $service->getTranslation('slug', 'id', false) }}</p>
                </div>
                <div>
                    <p class="section-title">Slug (EN)</p>
                    <p style="color: var(--table-text)">{{ $service->getTranslation('slug', 'en', false) ?: '-' }}</p>
                </div>
                <div>
                    <p class="section-title">Active</p>
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <p class="section-title">Sort Order</p>
                    <p style="color: var(--table-text)">{{ $service->sort_order ?? '0' }}</p>
                </div>
            </div>

            @if ($service->photo)
                <div class="mb-6">
                    <p class="section-title">Photo</p>
                    <img src="{{ $service->photo }}" alt="{{ $service->getTranslation('title', 'id', false) }}" class="mt-2 rounded-lg" style="max-width: 100%; max-height: 400px;">
                </div>
            @endif

            <x-admin.language-tabs>
                <!-- ID Tab -->
                <div x-show="langTab === 'id'" class="space-y-6">
                    <div>
                        <p class="section-title">Title</p>
                        <p class="mt-1 text-lg font-semibold" style="color: var(--table-text)">{{ $service->getTranslation('title', 'id', false) }}</p>
                    </div>
                    @if ($service->getTranslation('description', 'id', false))
                        <div>
                            <p class="section-title">Description</p>
                            <p class="mt-2" style="color: var(--table-text)">{{ $service->getTranslation('description', 'id', false) }}</p>
                        </div>
                    @endif
                </div>

                <!-- EN Tab -->
                <div x-show="langTab === 'en'" class="space-y-6">
                    <div>
                        <p class="section-title">Title (EN)</p>
                        <p class="mt-1 text-lg font-semibold" style="color: var(--table-text)">{{ $service->getTranslation('title', 'en', false) ?: 'No English translation' }}</p>
                    </div>
                    @if ($service->getTranslation('description', 'en', false))
                        <div>
                            <p class="section-title">Description (EN)</p>
                            <p class="mt-2" style="color: var(--table-text)">{{ $service->getTranslation('description', 'en', false) }}</p>
                        </div>
                    @else
                        <div>
                            <p class="section-title">Description (EN)</p>
                            <p class="mt-2" style="color: var(--muted-text)">No English description available.</p>
                        </div>
                    @endif
                </div>
            </x-admin.language-tabs>
        </div>
    </div>
</x-admin.layouts.app>