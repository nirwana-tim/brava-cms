<!DOCTYPE html>
@php($avatar = app(App\Services\AvatarService::class))
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @isset($title) - {{ $title }} @endisset</title>

    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar Backdrop (mobile) --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
        @click="sidebarOpen = false"
    ></div>

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 lg:static lg:translate-x-0 lg:z-auto flex flex-col transition-transform duration-300 ease-in-out" style="background-color: var(--sidebar-bg); border-right: 1px solid var(--sidebar-border)"
    >
        {{-- Sidebar Header --}}
        <div class="flex items-center justify-between h-16 px-6 border-b" style="border-color: var(--sidebar-border)">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <img src="/favicon.svg" alt="Brava CMS" class="w-8 h-8">
                <span class="text-lg font-semibold" style="color: var(--sidebar-user-name)">Brava CMS</span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden" style="color: var(--muted-text)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Sidebar Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <x-admin.sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </x-slot>
                Dashboard
            </x-admin.sidebar-link>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider" style="color: var(--sidebar-section-header)">Content</p>
            </div>
            <x-admin.sidebar-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </x-slot>
                Services
            </x-admin.sidebar-link>
            <x-admin.sidebar-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </x-slot>
                Categories
            </x-admin.sidebar-link>
            <x-admin.sidebar-link :href="route('admin.blogs.index')" :active="request()->routeIs('admin.blogs.*')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </x-slot>
                Blogs
            </x-admin.sidebar-link>
            <x-admin.sidebar-link :href="route('admin.portfolio.index')" :active="request()->routeIs('admin.portfolio.*')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </x-slot>
                Portfolio
            </x-admin.sidebar-link>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider" style="color: var(--sidebar-section-header)">Engagement</p>
            </div>
            <x-admin.sidebar-link :href="route('admin.promos.index')" :active="request()->routeIs('admin.promos.*')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L15 9M9.5 9.5H9.51M14.5 14.5H14.51" />
                    </svg>
                </x-slot>
                Promo
            </x-admin.sidebar-link>
            <x-admin.sidebar-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </x-slot>
                Testimonials
            </x-admin.sidebar-link>
            <x-admin.sidebar-link :href="route('admin.faqs.index')" :active="request()->routeIs('admin.faqs.*')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </x-slot>
                FAQs
            </x-admin.sidebar-link>
            @if (! Auth::user()->isStaff())
                <x-admin.sidebar-link :href="route('admin.team.index')" :active="request()->routeIs('admin.team.*')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </x-slot>
                    Team
                </x-admin.sidebar-link>
            @endif

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider" style="color: var(--sidebar-section-header)">System</p>
            </div>
            <x-admin.sidebar-link :href="route('admin.media.index')" :active="request()->routeIs('admin.media.*')">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </x-slot>
                Media
            </x-admin.sidebar-link>
            @if (! Auth::user()->isStaff())
                <x-admin.sidebar-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </x-slot>
                    Settings
                </x-admin.sidebar-link>
            @endif
            @if (Auth::user()->isSuperAdmin())
                <x-admin.sidebar-link :href="route('admin.trash.index')" :active="request()->routeIs('admin.trash.*')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </x-slot>
                    Recycle Bin
                </x-admin.sidebar-link>
            @endif
        </nav>

        {{-- Sidebar Footer --}}
        <div class="p-4 border-t" style="border-color: var(--sidebar-border)">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center overflow-hidden shrink-0" style="background-color: var(--sidebar-user-avatar-bg)">
                    @if ($avatar->hasAvatar(Auth::user()->avatar))
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="font-semibold text-sm" style="color: var(--sidebar-user-avatar-text)">{{ $avatar->initials(Auth::user()->name) }}</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate" style="color: var(--sidebar-user-name)">{{ Auth::user()->name }}</p>
                    <p class="text-xs truncate" style="color: var(--sidebar-user-role)">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top Header --}}
        <header class="h-16 flex items-center px-4 lg:px-6 shrink-0" style="background-color: var(--card-bg); border-bottom: 1px solid var(--card-header-border)">
            {{-- Mobile menu toggle --}}
            <button @click="sidebarOpen = true" class="lg:hidden mr-3" style="color: var(--muted-text)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb / Page title --}}
            <div class="flex-1">
                @isset($title)
                    <h1 class="text-lg font-semibold" style="color: var(--heading-text)">{{ $title }}</h1>
                @endisset
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" style="color: var(--muted-text)" title="View site">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

                {{-- User Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900">
                        <span class="hidden sm:block" style="color: var(--heading-text)">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center overflow-hidden" style="background-color: var(--btn-primary-bg)">
                            @if ($avatar->hasAvatar(Auth::user()->avatar))
                                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-semibold text-xs" style="color: var(--btn-primary-text)">{{ $avatar->initials(Auth::user()->name) }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg border py-1 z-50" style="background-color: var(--card-bg); border-color: var(--card-border)">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm" style="color: var(--sidebar-link-text)">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm" style="color: var(--sidebar-link-text)">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="px-4 lg:px-6 pt-4">
                <div class="rounded-lg px-4 py-3 text-sm flex items-center gap-2" style="background-color: var(--flash-success-bg); border-color: var(--flash-success-border); color: var(--flash-success-text)">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="px-4 lg:px-6 pt-4">
                <div class="rounded-lg px-4 py-3 text-sm flex items-center gap-2" style="background-color: var(--flash-error-bg); border-color: var(--flash-error-border); color: var(--flash-error-text)">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        {{-- <footer class="border-t border-gray-200 px-4 lg:px-6 py-4">
            <p class="text-sm text-gray-400 text-center">&copy; {{ date('Y') }} Brava CMS. All rights reserved.</p>
        </footer> --}}
    </div>
</div>

<x-admin.image-editor />

@stack('scripts')
</body>
</html>
