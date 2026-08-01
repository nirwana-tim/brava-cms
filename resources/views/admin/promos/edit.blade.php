<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Promo & Voucher') }}</x-slot>

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

                <div class="space-y-6">
                    <div>
                        <x-input-label for="title" :value="__('Promo Title')" :required="true" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $promo->title)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="slug" :value="__('Slug')" :required="true" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $promo->slug)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="badge_text" :value="__('Badge Text')" />
                            <x-text-input id="badge_text" name="badge_text" type="text" class="mt-1 block w-full" :value="old('badge_text', $promo->badge_text)" />
                            <x-input-error class="mt-2" :messages="$errors->get('badge_text')" />
                        </div>

                        <div>
                            <x-input-label for="discount_info" :value="__('Discount Info')" />
                            <x-text-input id="discount_info" name="discount_info" type="text" class="mt-1 block w-full" :value="old('discount_info', $promo->discount_info)" />
                            <x-input-error class="mt-2" :messages="$errors->get('discount_info')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="form-textarea mt-1 w-full" rows="4">{{ old('description', $promo->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div x-data="{ image: @js(old('image', $promo->image)), imageAlt: @js(old('image_alt', $promo->image_alt)) }">
                        <x-input-label for="image" :value="__('Banner Image')" />
                        <input type="hidden" name="image" id="image" value="{{ old('image', $promo->image) }}" />
                        <input type="hidden" name="image_alt" id="image_alt" value="{{ old('image_alt', $promo->image_alt) }}" />
                        <template x-if="image">
                            <div class="mb-2">
                                <img :src="image" :alt="imageAlt" class="rounded-lg" style="max-width:240px;max-height:160px;object-fit:cover">
                                <p x-show="imageAlt" class="text-xs mt-1" x-text="'Alt: ' + imageAlt" style="color:var(--muted-text)"></p>
                            </div>
                        </template>
                        <x-admin.media-picker target="image" collection="promos" />
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="valid_from" :value="__('Valid From')" />
                            <x-text-input id="valid_from" name="valid_from" type="datetime-local" class="mt-1 block w-full" :value="old('valid_from', $promo->valid_from?->format('Y-m-d\TH:i'))" />
                            <p class="mt-1 text-xs" style="color: var(--muted-text)">Kosongkan jika promo langsung berlaku. Jika diisi, promo tampil sebagai "Coming Soon" sampai tanggal ini — pengunjung bisa lihat tapi belum bisa klaim.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('valid_from')" />
                        </div>

                        <div>
                            <x-input-label for="valid_until" :value="__('Valid Until')" />
                            <x-text-input id="valid_until" name="valid_until" type="datetime-local" class="mt-1 block w-full" :value="old('valid_until', $promo->valid_until?->format('Y-m-d\TH:i'))" />
                            <p class="mt-1 text-xs" style="color: var(--muted-text)">Kosongkan jika tidak ada batas waktu. Promo yang sudah lewat tanggal ini tetap tampil sebagai "Expired" — hilangkan centang Active untuk menyembunyikannya.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('valid_until')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="wa_template" :value="__('WhatsApp Message Template')" />
                        <textarea id="wa_template" name="wa_template" class="form-textarea mt-1 w-full" rows="2">{{ old('wa_template', $promo->wa_template) }}</textarea>
                        <p class="mt-1 text-xs" style="color: var(--muted-text)">Pesan otomatis yang akan dikirim saat pengunjung mengklik tombol 'Klaim Sekarang' di Modal atau Hero Banner.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('wa_template')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t" style="border-color: var(--card-border)">
                        <div>
                            <x-admin.toggle name="is_highlighted" :checked="old('is_highlighted', $promo->is_highlighted)" label="Highlight as Hero Banner" />
                            <p class="text-xs mt-1 text-amber-600">Hanya ada 1 promo Highlight. Jika dicentang, promo highlight lain otomatis turun menjadi reguler.</p>
                        </div>

                        <div>
                            <x-admin.toggle name="is_active" :checked="old('is_active', $promo->is_active)" label="Active" />
                            <p class="text-xs mt-1" style="color: var(--muted-text)">Non-aktifkan untuk menyembunyikan promo sepenuhnya dari website, termasuk yang sudah terlewat tanggalnya.</p>
                        </div>
                    </div>

                    <details class="mt-4 pt-4 border-t" style="border-color: var(--card-border)">
                        <summary class="text-sm font-medium cursor-pointer" style="color: var(--label-text)">{{ __('SEO Settings') }}</summary>
                        <div class="mt-4 space-y-4">
                            <div>
                                <x-input-label for="meta_title" :value="__('Meta Title')" />
                                <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title', $promo->meta_title)" placeholder="e.g. Promo Diskon 40% Seragam Kantor | Brava" />
                                <p class="form-hint">Optimal 50–60 karakter untuk Google Search. Otomatis mengikuti Judul Promo jika dikosongkan.</p>
                            </div>
                            <div>
                                <x-input-label for="meta_description" :value="__('Meta Description')" />
                                <textarea id="meta_description" name="meta_description" class="form-textarea mt-1 w-full" rows="3">{{ old('meta_description', $promo->meta_description) }}</textarea>
                                <p class="form-hint">Optimal 150–160 karakter (termasuk spasi). Otomatis mengikuti deskripsi promo jika dikosongkan.</p>
                            </div>
                            <div>
                                <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                                <x-text-input id="meta_keywords" name="meta_keywords" type="text" class="mt-1 block w-full" :value="old('meta_keywords', $promo->meta_keywords)" placeholder="e.g. promo seragam, diskon konveksi, baju kantor murah" />
                                <p class="form-hint">Daftar 3–5 kata/frasa kunci relevan dipisahkan koma untuk pelabelan topik internal & referensi AI.</p>
                            </div>
                        </div>
                    </details>

                    <div class="flex items-center gap-4 pt-4">
                        <x-primary-button>{{ __('Update Promo') }}</x-primary-button>
                        <a href="{{ route('admin.promos.index') }}">
                            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const title = document.getElementById('title');
        const slug = document.getElementById('slug');
        if (!title || !slug) return;
        let slugEdited = false;
        slug.addEventListener('input', function () { if (this.value) slugEdited = true; });
        title.addEventListener('input', function () {
            if (slugEdited) return;
            slug.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        });
    });
</script>
@endpush
</x-admin.layouts.app>
