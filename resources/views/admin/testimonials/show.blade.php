@php($avatar = app(App\Services\AvatarService::class))
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

            @if ($avatar->hasAvatar($testimonial->avatar))
                <div class="mb-6">
                    <p class="section-title">Avatar</p>
                    <img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->getTranslation('client_name', 'id', false) }}" class="mt-2 rounded-full" style="width: 80px; height: 80px; object-fit: cover;">
                </div>
            @else
                <div class="mb-6">
                    <p class="section-title">Avatar</p>
                    <div class="mt-2 rounded-full flex items-center justify-center text-xl font-bold" style="width: 80px; height: 80px; background: {{ $avatar->color($testimonial->getTranslation('client_name', 'id', false)) }}; color: #fff;">
                        {{ $avatar->initials($testimonial->getTranslation('client_name', 'id', false)) }}
                    </div>
                </div>
            @endif

            <x-admin.language-tabs>
                <!-- ID Tab -->
                <div x-show="langTab === 'id'" class="space-y-6">
                    <div>
                        <p class="section-title">Content</p>
                        <div class="mt-2" style="color: var(--table-text); line-height: 1.8; font-style: italic;">
                            &ldquo;{{ $testimonial->getTranslation('content', 'id', false) }}&rdquo;
                        </div>
                    </div>
                </div>

                <!-- EN Tab -->
                <div x-show="langTab === 'en'" class="space-y-6">
                    <div>
                        <p class="section-title">Content (EN)</p>
                        <div class="mt-2" style="color: var(--table-text); line-height: 1.8; font-style: italic;">
                            @if ($testimonial->getTranslation('content', 'en', false))
                                &ldquo;{{ $testimonial->getTranslation('content', 'en', false) }}&rdquo;
                            @else
                                <span style="color: var(--muted-text)">No English translation available.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </x-admin.language-tabs>
        </div>
    </div>
</x-admin.layouts.app>