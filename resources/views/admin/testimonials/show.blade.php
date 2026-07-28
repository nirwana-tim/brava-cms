<x-admin.layouts.app>
    <x-slot name="title">{{ $testimonial->client_name }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $testimonial->client_name }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.testimonials.edit', $testimonial) }}">
                    <x-secondary-button type="button">{{ __('Edit') }}</x-secondary-button>
                </a>
                <a href="{{ route('admin.testimonials.index') }}">
                    <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="section-title">Rating</p>
                    <p style="color: var(--badge-active-text)">{{ $testimonial->rating ? str_repeat('★', $testimonial->rating).str_repeat('☆', 5 - $testimonial->rating) : 'N/A' }}</p>
                </div>
                <div>
                    <p class="section-title">Active</p>
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $testimonial->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            @if ($testimonial->avatar)
                <div class="mb-6">
                    <p class="section-title">Avatar</p>
                    <img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->client_name }}" class="mt-2 rounded-full" style="width: 80px; height: 80px; object-fit: cover;">
                </div>
            @endif

            <div>
                <p class="section-title">Content</p>
                <div class="mt-2" style="color: var(--table-text); line-height: 1.8; font-style: italic;">
                    &ldquo;{{ $testimonial->content }}&rdquo;
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>