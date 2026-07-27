<x-admin.layouts.app>
    <x-slot name="title">{{ $team->name }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $team->name }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.team.edit', $team) }}">
                    <x-secondary-button type="button">{{ __('Edit') }}</x-secondary-button>
                </a>
                <a href="{{ route('admin.team.index') }}">
                    <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="section-title">Position</p>
                    <p style="color: var(--table-text)">{{ $team->position ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="section-title">Active</p>
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $team->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $team->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @if ($team->email)
                <div>
                    <p class="section-title">Email</p>
                    <a href="mailto:{{ $team->email }}" style="color: var(--btn-edit-text)">{{ $team->email }}</a>
                </div>
                @endif
                @if ($team->phone)
                <div>
                    <p class="section-title">Phone</p>
                    <p style="color: var(--table-text)">{{ $team->phone }}</p>
                </div>
                @endif
            </div>

            @if ($team->avatar)
                <div class="mb-6">
                    <p class="section-title">Avatar</p>
                    <img src="{{ $team->avatar }}" alt="{{ $team->name }}" class="mt-2 rounded-full" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            @endif

            @if ($team->bio)
            <div>
                <p class="section-title">Bio</p>
                <div class="mt-2" style="color: var(--table-text); line-height: 1.8">
                    {!! nl2br(e($team->bio)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>