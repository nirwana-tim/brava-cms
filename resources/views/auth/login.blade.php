<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <div class="lg:hidden mb-8 text-center">
            <img src="/favicon.svg" alt="BRAVA" class="w-12 h-12 mb-4 mx-auto">
        </div>

        <h1 class="text-center lg:text-left font-semibold tracking-tight" style="color: var(--btn-primary-bg); font-size: 40px; line-height: 1.15;">Welcome Back!</h1>
        <p class="mt-1.5 text-sm text-center lg:text-left" style="color: var(--muted-text)">Sign in to manage your BRAVA dashboard.</p>

        <x-auth-session-status class="mt-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />

                <div class="relative mt-1.5" x-data="{ show: false }">
                    <input id="password"
                        :type="show ? 'text' : 'password'"
                        name="password"
                        class="form-input w-full pr-11"
                        required autocomplete="current-password" placeholder="••••••••" />
                    <button type="button"
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5"
                        style="color: var(--muted-text)">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="pt-1">
                <x-primary-button class="w-full justify-center py-2.5">
                    Get Started
                </x-primary-button>
            </div>

            <div class="text-center">
                <p class="text-xs leading-relaxed" style="color: var(--muted-text)">Forgot your password?<br>Please contact your administrator.</p>
            </div>
        </form>
    </div>
</x-guest-layout>