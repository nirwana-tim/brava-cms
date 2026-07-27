<x-admin.layouts.app>
    <x-slot name="title">{{ __('Admin Dashboard') }}</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['services'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Services</div>
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
</x-admin.layouts.app>
