@props([
    'specifications' => [],
    'features' => [],
])

<div x-data="{
    specifications: @js($specifications),
    features: @js($features),
    addSpec() { this.specifications.push({ key: '', value: '' }); },
    removeSpec(index) { this.specifications.splice(index, 1); },
    addFeature() { this.features.push(''); },
    removeFeature(index) { this.features.splice(index, 1); },
}">
    <div class="border-t pt-4" style="border-color: var(--card-header-border)">
        <x-input-label :value="__('Spesifikasi Produk')" />
        <p class="text-xs mb-3" style="color: var(--muted-text)">
            Tabel spesifikasi key/value produk. Klik "+ Tambah Spesifikasi" untuk menambah baris.
        </p>

        <div class="space-y-2">
            <template x-for="(spec, index) in specifications" :key="index">
                <div class="flex items-start gap-2">
                    <x-text-input
                        type="text"
                        name="specifications[][key]"
                        x-model="spec.key"
                        placeholder="Key (contoh: Material)"
                        class="flex-1"
                    />
                    <x-text-input
                        type="text"
                        name="specifications[][value]"
                        x-model="spec.value"
                        placeholder="Value (contoh: Lacoste CVC)"
                        class="flex-1"
                    />
                    <button type="button" @click="removeSpec(index)"
                        class="inline-flex items-center justify-center p-2 rounded-md text-xs font-medium btn-edit shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <button type="button" @click="addSpec()"
            class="mt-3 inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium btn-edit">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            + Tambah Spesifikasi
        </button>
        <x-input-error class="mt-2" :messages="$errors?->get('specifications')" />
    </div>

    <div class="border-t pt-4 mt-4" style="border-color: var(--card-header-border)">
        <x-input-label :value="__('Fitur Produk')" />
        <p class="text-xs mb-3" style="color: var(--muted-text)">
            Daftar fitur produk. Klik "+ Tambah Fitur" untuk menambah item.
        </p>

        <div class="space-y-2">
            <template x-for="(feature, index) in features" :key="index">
                <div class="flex items-start gap-2">
                    <x-text-input
                        type="text"
                        name="features[]"
                        x-model="features[index]"
                        placeholder="contoh: Nyaman digunakan"
                        class="flex-1"
                    />
                    <button type="button" @click="removeFeature(index)"
                        class="inline-flex items-center justify-center p-2 rounded-md text-xs font-medium btn-edit shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <button type="button" @click="addFeature()"
            class="mt-3 inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium btn-edit">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            + Tambah Fitur
        </button>
        <x-input-error class="mt-2" :messages="$errors?->get('features')" />
    </div>
</div>
