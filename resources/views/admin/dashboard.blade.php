<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['products'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Products</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['blogs'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Blog Posts</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['categories'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Categories</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['users'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Users</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
