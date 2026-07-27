<x-admin.layouts.app>
    <x-slot name="title">{{ __('Create Category') }}</x-slot>

    <div class="card">
        <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        @include('admin.categories.form')
                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <a href="{{ route('admin.categories.index') }}">
                                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
</x-admin.layouts.app>
