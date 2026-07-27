<x-admin.layouts.app>
    <x-slot name="title">{{ $portfolio->title }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $portfolio->title }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.portfolio.edit', $portfolio) }}">
                    <x-secondary-button type="button">{{ __('Edit') }}</x-secondary-button>
                </a>
                <a href="{{ route('admin.portfolio.index') }}">
                    <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="section-title">Slug</p>
                    <p style="color: var(--table-text)">{{ $portfolio->slug }}</p>
                </div>
                <div>
                    <p class="section-title">Active</p>
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $portfolio->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $portfolio->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @if ($portfolio->service)
                <div>
                    <p class="section-title">Service</p>
                    <p style="color: var(--table-text)">{{ $portfolio->service->title }}</p>
                </div>
                @endif
                @if ($portfolio->client)
                <div>
                    <p class="section-title">Client</p>
                    <p style="color: var(--table-text)">{{ $portfolio->client }}</p>
                </div>
                @endif
                @if ($portfolio->project_url)
                <div>
                    <p class="section-title">Project URL</p>
                    <a href="{{ $portfolio->project_url }}" target="_blank" rel="noopener noreferrer" style="color: var(--btn-edit-text)">{{ $portfolio->project_url }}</a>
                </div>
                @endif
                @if ($portfolio->completed_at)
                <div>
                    <p class="section-title">Completed At</p>
                    <p style="color: var(--table-text)">{{ $portfolio->completed_at->format('M d, Y') }}</p>
                </div>
                @endif
                <div>
                    <p class="section-title">Sort Order</p>
                    <p style="color: var(--table-text)">{{ $portfolio->sort_order ?? '0' }}</p>
                </div>
            </div>

            @if ($portfolio->description)
                <div class="mb-6">
                    <p class="section-title">Description</p>
                    <p class="mt-2" style="color: var(--table-text)">{{ $portfolio->description }}</p>
                </div>
            @endif

            <div>
                <p class="section-title">Content</p>
                <div class="mt-2 prose prose-sm max-w-none" style="color: var(--table-text); line-height: 1.8">
                    {!! $portfolio->content !!}
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>