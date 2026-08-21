<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Page SEO') }}</x-slot>

    <div class="card">
        <div class="card-body">
            @php
                $path = $pageSeo->page_key === 'home' ? '/' : '/'.$pageSeo->page_key;
            @endphp
            <div class="mb-6">
                <h2 class="text-2xl font-semibold capitalize" style="color: var(--heading-text)">{{ $pageSeo->page_key }} <span class="text-sm font-normal" style="color: var(--muted-text)">({{ $path }})</span></h2>
                <p class="text-xs mt-1" style="color: var(--muted-text)">Fields yang dikosongkan otomatis memakai fallback bawaan (hardcoded) sehingga metadata tidak pernah rusak.</p>
            </div>

            <form action="{{ route('admin.page-seo.update', $pageSeo->page_key) }}" method="POST">
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

                <x-admin.seo-fields
                    :meta-title="$pageSeo->getTranslations('meta_title')"
                    :meta-description="$pageSeo->getTranslations('meta_description')"
                    :og-image="$pageSeo->og_image"
                    :og-image-alt="$pageSeo->getTranslations('og_image_alt')"
                    :robots-index="$pageSeo->robots_index"
                    :robots-follow="$pageSeo->robots_follow"
                    :show-schema-type="false"
                />

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('admin.page-seo.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>