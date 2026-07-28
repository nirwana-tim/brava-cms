<x-admin.layouts.app>
    <x-slot name="title">{{ __('Reset Password') }} — {{ $team->name }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ __('Reset Password') }}: {{ $team->name }}</h2>
            <a href="{{ route('admin.team.edit', $team) }}">
                <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.team.password', $team) }}" method="POST">
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

                <div class="space-y-6 max-w-md">
                    <div>
                        <x-input-label for="password" :value="__('New Password')" :required="true" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" :required="true" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Reset Password') }}</x-primary-button>
                        <a href="{{ route('admin.team.edit', $team) }}">
                            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
