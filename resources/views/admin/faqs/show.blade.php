<x-admin.layouts.app>
    <x-slot name="title">{{ Str::limit($faq->question, 60) }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">FAQ</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.faqs.edit', $faq) }}">
                    <x-secondary-button type="button">{{ __('Edit') }}</x-secondary-button>
                </a>
                <a href="{{ route('admin.faqs.index') }}">
                    <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="section-title">Category</p>
                    <p style="color: var(--table-text)">{{ $faq->category ?? 'Uncategorized' }}</p>
                </div>
                <div>
                    <p class="section-title">Active</p>
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $faq->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $faq->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <p class="section-title">Sort Order</p>
                    <p style="color: var(--table-text)">{{ $faq->sort_order ?? '0' }}</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="section-title">Question</p>
                <p class="mt-2 text-lg font-semibold" style="color: var(--table-text)">{{ $faq->question }}</p>
            </div>

            <div>
                <p class="section-title">Answer</p>
                <div class="mt-2 prose prose-sm max-w-none" style="color: var(--table-text); line-height: 1.8">
                    {!! $faq->answer !!}
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>