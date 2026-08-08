<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Promo') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.promos.update', $promo) }}" method="POST">
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="title_id" :value="__('Title (ID)')" :required="true" />
                                <x-text-input id="title_id" name="title[id]" type="text" class="mt-1 block w-full" :value="old('title.id', $promo->getTranslation('title', 'id', false))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title.id')" />
                            </div>

                            <div>
                                <x-input-label for="slug_id" :value="__('Slug (ID)')" :required="true" />
                                <x-text-input id="slug_id" name="slug[id]" type="text" class="mt-1 block w-full" :value="old('slug.id', $promo->getTranslation('slug', 'id', false))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug.id')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="badge_text_id" :value="__('Badge Text (ID)')" />
                                <x-text-input id="badge_text_id" name="badge_text[id]" type="text" class="mt-1 block w-full" :value="old('badge_text.id', $promo->getTranslation('badge_text', 'id', false))" placeholder="e.g. PROMO KEMERDEKAAN" />
                                <x-input-error class="mt-2" :messages="$errors->get('badge_text.id')" />
                            </div>
                            <div>
                                <x-input-label for="discount_info_id" :value="__('Discount Info (ID)')" />
                                <x-text-input id="discount_info_id" name="discount_info[id]" type="text" class="mt-1 block w-full" :value="old('discount_info.id', $promo->getTranslation('discount_info', 'id', false))" placeholder="e.g. Diskon 20% / Cash Back 50rb" />
                                <x-input-error class="mt-2" :messages="$errors->get('discount_info.id')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description_id" :value="__('Description (ID)')" />
                            <textarea id="description_id" name="description[id]" class="form-textarea mt-1" rows="3">{{ old('description.id', $promo->getTranslation('description', 'id', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.id')" />
                        </div>

                        <div>
                            <x-input-label for="wa_template_id" :value="__('WhatsApp Message Template (ID)')" />
                            <textarea id="wa_template_id" name="wa_template[id]" class="form-textarea mt-1" rows="2" placeholder="Halo Brava, saya ingin mengklaim promo ini...">{{ old('wa_template.id', $promo->getTranslation('wa_template', 'id', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('wa_template.id')" />
                        </div>

                        <x-admin.alt-input field="image_alt[id]" label="Banner Image Alt (ID)" :value="old('image_alt.id', $promo->getTranslation('image_alt', 'id', false))" />
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="title_en" :value="__('Title (EN - English)')" />
                                <x-text-input id="title_en" name="title[en]" type="text" class="mt-1 block w-full" :value="old('title.en', $promo->getTranslation('title', 'en', false))" placeholder="Leave blank to fallback to Indonesian" />
                                <x-input-error class="mt-2" :messages="$errors->get('title.en')" />
                            </div>

                            <div>
                                <x-input-label for="slug_en" :value="__('Slug (EN - English)')" />
                                <x-text-input id="slug_en" name="slug[en]" type="text" class="mt-1 block w-full" :value="old('slug.en', $promo->getTranslation('slug', 'en', false))" placeholder="e.g. independence-promo" />
                                <x-input-error class="mt-2" :messages="$errors->get('slug.en')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="badge_text_en" :value="__('Badge Text (EN)')" />
                                <x-text-input id="badge_text_en" name="badge_text[en]" type="text" class="mt-1 block w-full" :value="old('badge_text.en', $promo->getTranslation('badge_text', 'en', false))" placeholder="e.g. SPECIAL OFFER" />
                                <x-input-error class="mt-2" :messages="$errors->get('badge_text.en')" />
                            </div>
                            <div>
                                <x-input-label for="discount_info_en" :value="__('Discount Info (EN)')" />
                                <x-text-input id="discount_info_en" name="discount_info[en]" type="text" class="mt-1 block w-full" :value="old('discount_info.en', $promo->getTranslation('discount_info', 'en', false))" placeholder="e.g. 20% OFF / Free Shipping" />
                                <x-input-error class="mt-2" :messages="$errors->get('discount_info.en')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description_en" :value="__('Description (EN - English)')" />
                            <textarea id="description_en" name="description[en]" class="form-textarea mt-1" rows="3">{{ old('description.en', $promo->getTranslation('description', 'en', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.en')" />
                        </div>

                        <div>
                            <x-input-label for="wa_template_en" :value="__('WhatsApp Message Template (EN)')" />
                            <textarea id="wa_template_en" name="wa_template[en]" class="form-textarea mt-1" rows="2" placeholder="Hi Brava, I would like to claim this promo...">{{ old('wa_template.en', $promo->getTranslation('wa_template', 'en', false)) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('wa_template.en')" />
                        </div>

                        <x-admin.alt-input field="image_alt[en]" label="Banner Image Alt (EN)" :value="old('image_alt.en', $promo->getTranslation('image_alt', 'en', false))" />
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div x-data="{ image: @js(old('image', $promo->image)), imageAlt: @js(old('image_alt.id', $promo->getTranslation('image_alt', 'id', false))) }">
                        <x-input-label for="image" :value="__('Banner Image')" />
                        <input type="hidden" name="image" id="image" value="{{ old('image', $promo->image) }}" />
                        <template x-if="image">
                            <div class="mb-2">
                                <img :src="image" :alt="imageAlt" class="rounded-lg" style="max-width:240px;max-height:160px;object-fit:cover">
                            </div>
                        </template>
                        <x-admin.media-picker target="image" collection="promos" />
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="valid_from" :value="__('Valid From')" />
                            <x-text-input id="valid_from" name="valid_from" type="datetime-local" class="mt-1 block w-full" :value="old('valid_from', $promo->valid_from?->format('Y-m-d\TH:i'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('valid_from')" />
                        </div>

                        <div>
                            <x-input-label for="valid_until" :value="__('Valid Until')" />
                            <x-text-input id="valid_until" name="valid_until" type="datetime-local" class="mt-1 block w-full" :value="old('valid_until', $promo->valid_until?->format('Y-m-d\TH:i'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('valid_until')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <x-admin.toggle name="is_active" :checked="old('is_active', $promo->is_active)" label="Active" hint="Show this promo on the website" />
                        <x-admin.toggle name="is_highlighted" :checked="old('is_highlighted', $promo->is_highlighted)" label="Featured Hero Banner (Highlight)" hint="Displays this promo as the hero banner in the promotions highlight" />
                    </div>

                    <div class="border-t pt-6">
                        <x-admin.seo-fields
                            :metaTitle="old('meta_title.id', $promo->getTranslation('meta_title', 'id', false))"
                            :metaTitleEn="old('meta_title.en', $promo->getTranslation('meta_title', 'en', false))"
                            :metaDescription="old('meta_description.id', $promo->getTranslation('meta_description', 'id', false))"
                            :metaDescriptionEn="old('meta_description.en', $promo->getTranslation('meta_description', 'en', false))"
                            :metaKeywords="old('meta_keywords.id', $promo->getTranslation('meta_keywords', 'id', false))"
                            :metaKeywordsEn="old('meta_keywords.en', $promo->getTranslation('meta_keywords', 'en', false))"
                            :showOgImageAlt="false"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update Promo') }}</x-primary-button>
                    <a href="{{ route('admin.promos.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupSlug(titleId, slugId) {
            const title = document.getElementById(titleId);
            const slug = document.getElementById(slugId);
            if (title && slug) {
                let slugEdited = false;
                slug.addEventListener('input', function () { if (this.value) slugEdited = true; });
                title.addEventListener('input', function () {
                    if (slugEdited) return;
                    slug.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
                });
            }
        }
        setupSlug('title_id', 'slug_id');
        setupSlug('title_en', 'slug_en');
    });
</script>
@endpush
</x-admin.layouts.app>
