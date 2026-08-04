@props([
    'metaTitle' => null,
    'metaTitleEn' => null,
    'metaDescription' => null,
    'metaDescriptionEn' => null,
    'metaKeywords' => null,
    'metaKeywordsEn' => null,
    'ogImage' => null,
    'ogImageAlt' => null,
    'ogImageAltEn' => null,
    'showOgImageAlt' => true,
    'robotsIndex' => true,
    'robotsFollow' => true,
    'schemaType' => 'WebPage',
])

<details class="mt-4 border rounded-lg p-4" style="border-color: var(--table-border)">
    <summary class="text-sm font-semibold cursor-pointer select-none" style="color: var(--heading-text)">
        SEO & OpenGraph Settings (ID / EN)
    </summary>
    <div class="mt-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="meta_title_id" :value="__('Meta Title (ID)')" />
                <x-text-input id="meta_title_id" name="meta_title[id]" type="text" class="mt-1 block w-full" :value="is_array($metaTitle) ? ($metaTitle['id'] ?? '') : (old('meta_title.id') ?: $metaTitle)" placeholder="Max 70 karakter" />
                <x-input-error class="mt-2" :messages="$errors->get('meta_title.id')" />
            </div>
            <div>
                <x-input-label for="meta_title_en" :value="__('Meta Title (EN)')" />
                <x-text-input id="meta_title_en" name="meta_title[en]" type="text" class="mt-1 block w-full" :value="old('meta_title.en', is_array($metaTitle) ? ($metaTitle['en'] ?? '') : $metaTitleEn)" placeholder="Max 70 characters" />
                <x-input-error class="mt-2" :messages="$errors->get('meta_title.en')" />
            </div>
        </div>
        <p class="form-hint mt-1">Optimal 50–60 karakter untuk Google Search. Otomatis menjadi judul share WhatsApp/sosmed (OG Title) dan mengikuti judul utama jika dikosongkan.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="meta_description_id" :value="__('Meta Description (ID)')" />
                <textarea id="meta_description_id" name="meta_description[id]" class="form-textarea mt-1 w-full" rows="3" placeholder="Max 160 karakter">{{ is_array($metaDescription) ? ($metaDescription['id'] ?? '') : (old('meta_description.id') ?: $metaDescription) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('meta_description.id')" />
            </div>
            <div>
                <x-input-label for="meta_description_en" :value="__('Meta Description (EN)')" />
                <textarea id="meta_description_en" name="meta_description[en]" class="form-textarea mt-1 w-full" rows="3" placeholder="Max 160 characters">{{ old('meta_description.en', is_array($metaDescription) ? ($metaDescription['en'] ?? '') : $metaDescriptionEn) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('meta_description.en')" />
            </div>
        </div>
        <p class="form-hint mt-1">Optimal 150–160 karakter (termasuk spasi). Otomatis menjadi deskripsi share WhatsApp/sosmed (OG Description) dan mengikuti ringkasan artikel jika dikosongkan.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="meta_keywords_id" :value="__('Meta Keywords (ID)')" />
                <x-text-input id="meta_keywords_id" name="meta_keywords[id]" type="text" class="mt-1 block w-full" :value="is_array($metaKeywords) ? ($metaKeywords['id'] ?? '') : (old('meta_keywords.id') ?: $metaKeywords)" placeholder="Dipisah koma" />
                <x-input-error class="mt-2" :messages="$errors->get('meta_keywords.id')" />
            </div>
            <div>
                <x-input-label for="meta_keywords_en" :value="__('Meta Keywords (EN)')" />
                <x-text-input id="meta_keywords_en" name="meta_keywords[en]" type="text" class="mt-1 block w-full" :value="old('meta_keywords.en', is_array($metaKeywords) ? ($metaKeywords['en'] ?? '') : $metaKeywordsEn)" placeholder="Comma separated" />
                <x-input-error class="mt-2" :messages="$errors->get('meta_keywords.en')" />
            </div>
        </div>
        <p class="form-hint mt-1">Daftar 3–5 kata/frasa kunci relevan, dipisahkan koma.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t" style="border-color: var(--table-border)">
            <div>
                <x-input-label for="og_image" :value="__('OpenGraph Custom Image')" />
                <input type="hidden" name="og_image" id="og_image" value="{{ old('og_image', $ogImage) }}" />
                <x-admin.media-picker target="og_image" collection="seo" />
                <x-input-error class="mt-2" :messages="$errors->get('og_image')" />
            </div>

            <div>
                <x-input-label for="schema_type" :value="__('Schema Type (JSON-LD)')" />
                <select id="schema_type" name="schema_type" class="form-select mt-1">
                    <option value="WebPage" {{ old('schema_type', $schemaType) === 'WebPage' ? 'selected' : '' }}>WebPage</option>
                    <option value="Article" {{ old('schema_type', $schemaType) === 'Article' ? 'selected' : '' }}>Article</option>
                    <option value="NewsArticle" {{ old('schema_type', $schemaType) === 'NewsArticle' ? 'selected' : '' }}>NewsArticle</option>
                    <option value="Product" {{ old('schema_type', $schemaType) === 'Product' ? 'selected' : '' }}>Product</option>
                    <option value="Offer" {{ old('schema_type', $schemaType) === 'Offer' ? 'selected' : '' }}>Offer</option>
                    <option value="Service" {{ old('schema_type', $schemaType) === 'Service' ? 'selected' : '' }}>Service</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('schema_type')" />
            </div>
        </div>
        <p class="form-hint mt-1">Optimal rasio 1.91:1 (1200x630 px) untuk banner sosmed. Otomatis mengikuti gambar utama jika dikosongkan.</p>

        @if ($showOgImageAlt)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="og_image_alt_id" :value="__('OpenGraph Image Alt (ID)')" />
                    <x-text-input id="og_image_alt_id" name="og_image_alt[id]" type="text" class="mt-1 block w-full" :value="old('og_image_alt.id', is_array($ogImageAlt) ? ($ogImageAlt['id'] ?? '') : $ogImageAlt)" />
                    <x-input-error class="mt-2" :messages="$errors->get('og_image_alt.id')" />
                </div>
                <div>
                    <x-input-label for="og_image_alt_en" :value="__('OpenGraph Image Alt (EN)')" />
                    <x-text-input id="og_image_alt_en" name="og_image_alt[en]" type="text" class="mt-1 block w-full" :value="old('og_image_alt.en', is_array($ogImageAlt) ? ($ogImageAlt['en'] ?? '') : $ogImageAltEn)" />
                    <x-input-error class="mt-2" :messages="$errors->get('og_image_alt.en')" />
                </div>
            </div>
        @endif

        <div class="flex items-center gap-6 pt-2">
            <x-admin.toggle name="robots_index" :checked="old('robots_index', $robotsIndex)" label="Allow Search Indexing (Robots Index)" />
            <x-admin.toggle name="robots_follow" :checked="old('robots_follow', $robotsFollow)" label="Allow Following Links (Robots Follow)" />
        </div>
    </div>
</details>
