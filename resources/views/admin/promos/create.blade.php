<x-admin.layouts.app>
    <x-slot name="title">{{ __('Create Promo & Voucher') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.promos.store') }}" method="POST">
                @csrf

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
                            <x-input-label for="title_id" :value="__('Promo Title (ID)')" :required="true" />
                            <x-text-input id="title_id" name="title[id]" type="text" class="mt-1 block w-full" :value="old('title.id')" required placeholder="e.g. 40% Diskon Untuk Pemesanan Seragam" />
                            <x-input-error class="mt-2" :messages="$errors->get('title.id')" />
                        </div>

                        <div>
                            <x-input-label for="slug_id" :value="__('Slug (ID)')" :required="true" />
                            <x-text-input id="slug_id" name="slug[id]" type="text" class="mt-1 block w-full" :value="old('slug.id')" required placeholder="e.g. 40-diskon-seragam" />
                            <x-input-error class="mt-2" :messages="$errors->get('slug.id')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="badge_text_id" :value="__('Badge Text (ID)')" />
                                <x-text-input id="badge_text_id" name="badge_text[id]" type="text" class="mt-1 block w-full" :value="old('badge_text.id')" placeholder="e.g. PROMO TERBATAS" />
                                <x-input-error class="mt-2" :messages="$errors->get('badge_text.id')" />
                            </div>

                            <div>
                                <x-input-label for="discount_info_id" :value="__('Discount Info (ID)')" />
                                <x-text-input id="discount_info_id" name="discount_info[id]" type="text" class="mt-1 block w-full" :value="old('discount_info.id')" placeholder="e.g. 40% / Rp 500.000" />
                                <x-input-error class="mt-2" :messages="$errors->get('discount_info.id')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description_id" :value="__('Description (ID)')" />
                            <textarea id="description_id" name="description[id]" class="form-textarea mt-1 w-full" rows="4" placeholder="Keterangan promo...">{{ old('description.id') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.id')" />
                        </div>

                        <div>
                            <x-input-label for="cta_text_id" :value="__('CTA Button Text (ID)')" />
                            <x-text-input id="cta_text_id" name="cta_text[id]" type="text" class="mt-1 block w-full" :value="old('cta_text.id', 'Klaim Promo')" />
                            <x-input-error class="mt-2" :messages="$errors->get('cta_text.id')" />
                        </div>
                    </div>

                    <!-- EN Tab -->
                    <div x-show="langTab === 'en'" class="space-y-6">
                        <div>
                            <x-input-label for="title_en" :value="__('Promo Title (EN - English)')" />
                            <x-text-input id="title_en" name="title[en]" type="text" class="mt-1 block w-full" :value="old('title.en')" placeholder="Leave blank to fallback to Indonesian" />
                            <x-input-error class="mt-2" :messages="$errors->get('title.en')" />
                        </div>

                        <div>
                            <x-input-label for="slug_en" :value="__('Slug (EN - English)')" />
                            <x-text-input id="slug_en" name="slug[en]" type="text" class="mt-1 block w-full" :value="old('slug.en')" placeholder="e.g. 40-percent-discount-uniforms" />
                            <x-input-error class="mt-2" :messages="$errors->get('slug.en')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="badge_text_en" :value="__('Badge Text (EN - English)')" />
                                <x-text-input id="badge_text_en" name="badge_text[en]" type="text" class="mt-1 block w-full" :value="old('badge_text.en')" placeholder="e.g. LIMITED OFFER" />
                                <x-input-error class="mt-2" :messages="$errors->get('badge_text.en')" />
                            </div>

                            <div>
                                <x-input-label for="discount_info_en" :value="__('Discount Info (EN - English)')" />
                                <x-text-input id="discount_info_en" name="discount_info[en]" type="text" class="mt-1 block w-full" :value="old('discount_info.en')" placeholder="e.g. 40% OFF" />
                                <x-input-error class="mt-2" :messages="$errors->get('discount_info.en')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description_en" :value="__('Description (EN - English)')" />
                            <textarea id="description_en" name="description[en]" class="form-textarea mt-1 w-full" rows="4" placeholder="Promo details...">{{ old('description.en') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description.en')" />
                        </div>

                        <div>
                            <x-input-label for="cta_text_en" :value="__('CTA Button Text (EN - English)')" />
                            <x-text-input id="cta_text_en" name="cta_text[en]" type="text" class="mt-1 block w-full" :value="old('cta_text.en', 'Claim Promo')" />
                            <x-input-error class="mt-2" :messages="$errors->get('cta_text.en')" />
                        </div>
                    </div>
                </x-admin.language-tabs>

                <div class="mt-6 space-y-6 border-t pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="code" :value="__('Promo / Voucher Code')" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full uppercase" :value="old('code')" placeholder="e.g. SERAGAM40" />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>

                        <div>
                            <x-input-label for="cta_url" :value="__('CTA Link URL')" />
                            <x-text-input id="cta_url" name="cta_url" type="text" class="mt-1 block w-full" :value="old('cta_url')" placeholder="e.g. https://wa.me/628123456789" />
                            <x-input-error class="mt-2" :messages="$errors->get('cta_url')" />
                        </div>
                    </div>

                    <div x-data="{ image: @js(old('image')), imageAlt: @js(old('image_alt.id')) }">
                        <x-input-label for="image" :value="__('Banner Image')" />
                        <input type="hidden" name="image" id="image" value="{{ old('image') }}" />
                        <template x-if="image">
                            <div class="mb-2">
                                <img :src="image" :alt="imageAlt" class="rounded-lg" style="max-width:240px;max-height:160px;object-fit:cover">
                            </div>
                        </template>
                        <x-admin.media-picker target="image" collection="promos" />
                        <x-admin.alt-input field="image_alt[id]" :value="old('image_alt.id')" label="Banner Image Alt Text (ID)" />
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="starts_at" :value="__('Start Date')" />
                            <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full" :value="old('starts_at')" />
                            <x-input-error class="mt-2" :messages="$errors->get('starts_at')" />
                        </div>

                        <div>
                            <x-input-label for="ends_at" :value="__('End Date')" />
                            <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full" :value="old('ends_at')" />
                            <x-input-error class="mt-2" :messages="$errors->get('ends_at')" />
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <x-admin.toggle name="is_active" :checked="old('is_active', true)" label="Active" />
                        <x-admin.toggle name="is_featured" :checked="old('is_featured', false)" label="Featured on Homepage" />
                    </div>

                    <div class="border-t pt-6">
                        <x-admin.seo-fields
                            :metaTitle="old('meta_title.id')"
                            :metaDescription="old('meta_description.id')"
                            :metaKeywords="old('meta_keywords.id')"
                            :ogImage="old('og_image')"
                            :ogImageAlt="old('og_image_alt.id')"
                            :robotsIndex="old('robots_index', true)"
                            :robotsFollow="old('robots_follow', true)"
                            :schemaType="old('schema_type', 'Offer')"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Save Promo') }}</x-primary-button>
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
        const titleId = document.getElementById('title_id');
        const slugId = document.getElementById('slug_id');
        if (titleId && slugId) {
            let slugEdited = false;
            slugId.addEventListener('input', function () { if (this.value) slugEdited = true; });
            titleId.addEventListener('input', function () {
                if (slugEdited) return;
                slugId.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            });
        }
        const titleEn = document.getElementById('title_en');
        const slugEn = document.getElementById('slug_en');
        if (titleEn && slugEn) {
            let slugEditedEn = false;
            slugEn.addEventListener('input', function () { if (this.value) slugEditedEn = true; });
            titleEn.addEventListener('input', function () {
                if (slugEditedEn) return;
                slugEn.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            });
        }
    });
</script>
@endpush
</x-admin.layouts.app>
