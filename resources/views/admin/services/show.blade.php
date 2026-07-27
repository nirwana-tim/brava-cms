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
                    <p class="section-title">Slug</p>
                    <p style="color: var(--table-text)">{{ $service->slug }}</p>
                </div>
                <div>
                    <p class="section-title">Active</p>
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <p class="section-title">Published At</p>
                    <p style="color: var(--table-text)">{{ $service->published_at?->format('M d, Y') ?? 'Not set' }}</p>
                </div>
            </div>

            @if ($service->photo)
                <div class="mb-6">
                    <p class="section-title">Photo</p>
                    <img src="{{ $service->photo }}" alt="{{ $service->title }}" class="mt-2 rounded-lg" style="max-width: 100%; max-height: 400px;">
                </div>
            @endif

            @if ($service->description)
                <div class="mb-6">
                    <p class="section-title">Description</p>
                    <p class="mt-2" style="color: var(--table-text)">{{ $service->description }}</p>
                </div>
            @endif

            <div>
                <p class="section-title">Content</p>
                <div class="mt-2 prose prose-sm max-w-none" style="color: var(--table-text); line-height: 1.8">
                    {!! $service->content !!}
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>