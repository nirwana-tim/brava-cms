<x-admin.layouts.app>
    <x-slot name="title">{{ Str::limit($faq->question, 60) }}</x-slot>

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
                <h2 class="text-lg font-semibold" style="color: var(--heading-text)">FAQ</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.faqs.edit', $faq) }}">
                        <x-secondary-button type="button" class="gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </x-secondary-button>
                    </a>
                    <a href="{{ route('admin.faqs.index') }}">
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
                <div class="grid grid-cols-2 gap-6 mb-6">
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

                <!-- ID Tab -->
                <div x-show="langTab === 'id'" class="space-y-6">
                    <div>
                        <p class="section-title">Question</p>
                        <p class="mt-2 text-lg font-semibold" style="color: var(--table-text)">{{ $faq->getTranslation('question', 'id', false) }}</p>
                    </div>

                    <div>
                        <p class="section-title">Answer</p>
                        <div class="mt-2 prose prose-sm max-w-none" style="color: var(--table-text); line-height: 1.8">
                            {!! $faq->getTranslation('answer', 'id', false) !!}
                        </div>
                    </div>
                </div>

                <!-- EN Tab -->
                <div x-show="langTab === 'en'" class="space-y-6">
                    <div>
                        <p class="section-title">Question (EN)</p>
                        <p class="mt-2 text-lg font-semibold" style="color: var(--table-text)">{{ $faq->getTranslation('question', 'en', false) ?: 'No English translation' }}</p>
                    </div>

                    <div>
                        <p class="section-title">Answer (EN)</p>
                        <div class="mt-2 prose prose-sm max-w-none" style="color: var(--table-text); line-height: 1.8">
                            @if ($faq->getTranslation('answer', 'en', false))
                                {!! $faq->getTranslation('answer', 'en', false) !!}
                            @else
                                <span style="color: var(--muted-text)">No English answer available.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>