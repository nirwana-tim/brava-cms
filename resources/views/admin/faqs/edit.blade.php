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

                <x-admin.language-tabs>
                    <!-- ID Tab -->
                    <div x-show="langTab === 'id'" class="space-y-6">
                        <div>
                            <x-input-label for="question_id" :value="__('Question (ID)')" :required="true" />
                            <x-text-input id="question_id" name="question[id]" type="text" class="mt-1 block w-full" :value="old('question.id', $faq->getTranslation('question', 'id', false))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('question.id')" />
                        </div>

                        <div>
                            <x-input-label for="answer_id" :value="__('Answer (ID)')" :required="true" />
                            <textarea id="answer_id" name="answer[id]" class="form-textarea mt-1" rows="5" required>{{ old('answer.id', $faq->getTranslation('answer', 'id', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('answer.id')" />
                        </div>
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div>
                            <x-input-label for="question_en" :value="__('Question (EN - English)')" />
                            <x-text-input id="question_en" name="question[en]" type="text" class="mt-1 block w-full" :value="old('question.en', $faq->getTranslation('question', 'en', false))" placeholder="Leave blank to fallback to Indonesian" />
                            <x-input-error class="mt-2" :messages="$errors->get('question.en')" />
                        </div>

                        <div>
                            <x-input-label for="answer_en" :value="__('Answer (EN - English)')" />
                            <textarea id="answer_en" name="answer[en]" class="form-textarea mt-1" rows="5">{{ old('answer.en', $faq->getTranslation('answer', 'en', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('answer.en')" />
                        </div>
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div>
                        <x-input-label for="sort_order" :value="__('Sort Order')" />
                        <x-text-input id="sort_order" name="sort_order" type="number" class="mt-1 block w-full" :value="old('sort_order', $faq->sort_order ?? '0')" />
                        <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
                    </div>

                    <div class="flex items-center gap-2">
                        <x-admin.toggle name="is_active" :checked="old('is_active', $faq->is_active)" label="Active" />
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
