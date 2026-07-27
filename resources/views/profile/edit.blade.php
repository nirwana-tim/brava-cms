<x-admin.layouts.app>
    <x-slot name="title">{{ __('Profile') }}</x-slot>

    <div class="grid gap-6 max-w-2xl">
        <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color: var(--card-bg)">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color: var(--card-bg)">
            @include('profile.partials.update-password-form')
        </div>

        <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color: var(--card-bg)">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-admin.layouts.app>
