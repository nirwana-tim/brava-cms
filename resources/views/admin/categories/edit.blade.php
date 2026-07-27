<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Category') }}</x-slot>

    <div class="card">
        <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('admin.categories.form')
                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                            <a href="{{ route('admin.categories.index') }}">
                                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
</x-admin.layouts.app>
