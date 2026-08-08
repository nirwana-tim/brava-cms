<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Konfirmasi Password</h1>
        <p class="mt-1.5 text-sm" style="color: var(--muted-text)">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="mt-8">
            @csrf

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="mt-1.5 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
                <x-primary-button>
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>