<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit FAQ') }}</x-slot>

    <div class="card">
        <div class="card-body">
                    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
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

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="question" :value="__('Question')" :required="true" />
                                <x-text-input id="question" name="question" type="text" class="mt-1 block w-full" :value="old('question', $faq->question)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('question')" />
                            </div>

                            <div>
                                <x-input-label for="answer" :value="__('Answer')" :required="true" />
                                <textarea id="answer" name="answer" class="form-textarea mt-1" rows="5" required>{{ old('answer', $faq->answer) }}</textarea>
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
                                <input type="checkbox" id="is_active" name="is_active" value="1" class="form-checkbox" {{ old('is_active', $faq->is_active) ? 'checked' : '' }} />
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
