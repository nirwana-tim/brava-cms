<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit FAQ') }}</x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
                    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 p-4">
                                <div class="text-sm text-red-600 dark:text-red-400">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="question" :value="__('Question')" />
                                <x-text-input id="question" name="question" type="text" class="mt-1 block w-full" :value="old('question', $faq->question)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('question')" />
                            </div>

                            <div>
                                <x-input-label for="answer" :value="__('Answer')" />
                                <textarea id="answer" name="answer" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="5" required>{{ old('answer', $faq->answer) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('answer')" />
                            </div>

                            <div>
                                <x-input-label for="category" :value="__('Category')" />
                                <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category', $faq->category)" />
                                <x-input-error class="mt-2" :messages="$errors->get('category')" />
                            </div>

                            <div>
                                <x-input-label for="sort_order" :value="__('Sort Order')" />
                                <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full" :value="old('sort_order', $faq->sort_order ?? '0')" />
                                <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ old('is_active', $faq->is_active) ? 'checked' : '' }} />
                                <x-input-label for="is_active" :value="__('Active')" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                            <a href="{{ route('admin.faqs.index') }}">
                                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
</x-admin.layouts.app>
