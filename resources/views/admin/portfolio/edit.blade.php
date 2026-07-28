<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Portfolio Item') }}</x-slot>

    <div class="card">
        <div class="card-body" x-data="{ photoUrl: '{{ old('photo', $portfolio->photo) }}', photoAlt: '{{ old('photo_alt', $portfolio->photo_alt) }}' }">
            <form action="{{ route('admin.portfolio.update', $portfolio) }}" method="POST">
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
                        <x-input-label for="service_id" :value="__('Service')" />
                        <select id="service_id" name="service_id" class="form-select mt-1">
                            <option value="">-- Select Service --</option>
                            @foreach ($services as $id => $name)
                                <option value="{{ $id }}" {{ old('service_id', $portfolio->service_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('service_id')" />
                    </div>

                    <div>
                        <x-input-label for="title" :value="__('Title')" :required="true" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $portfolio->title)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="slug" :value="__('Slug')" :required="true" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $portfolio->slug)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="form-textarea mt-1" rows="3">{{ old('description', $portfolio->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <x-admin.rich-text name="content" :value="old('content', $portfolio->content)" />

                    <div>
                        <x-input-label for="photo" :value="__('Cover Photo')" :required="true" />
                        <input type="hidden" name="photo" id="photo"
                            value="{{ old('photo', $portfolio->photo) }}" />
                        <input type="hidden" name="photo_alt" id="photo_alt"
                            value="{{ old('photo_alt', $portfolio->photo_alt) }}" />
                        <template x-if="photoUrl">
                            <div class="mb-2">
                                <img :src="photoUrl" :alt="photoAlt"
                                    class="rounded-lg"
                                    style="max-width:240px;max-height:160px;object-fit:cover">
                                <p x-show="photoAlt" class="text-xs mt-1" x-text="'Alt: ' + photoAlt"
                                    style="color:var(--muted-text)"></p>
                            </div>
                        </template>
                        <x-admin.media-picker target="photo" collection="portfolio" />
                        <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                    </div>

                    @if ($categories->isNotEmpty())
                        <div>
                            <x-input-label :value="__('Categories')" />
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach ($categories as $id => $name)
                                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm border cursor-pointer transition-colors"
                                           x-data="{ checked: {{ in_array($id, old('categories', $portfolio->categories->pluck('id')->toArray())) ? 'true' : 'false' }} }"
                                           :class="checked && 'bg-blue-600 text-white border-blue-600'"
                                           style="border-color: var(--table-border); background-color: var(--card-bg)">
                                        <input type="checkbox" name="categories[]" value="{{ $id }}" x-model="checked" class="form-checkbox">
                                        <span :class="checked && 'text-white'" style="color: var(--label-text)">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('categories')" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="client" :value="__('Client')" />
                        <x-text-input id="client" name="client" type="text" class="mt-1 block w-full" :value="old('client', $portfolio->client)" />
                        <x-input-error class="mt-2" :messages="$errors->get('client')" />
                    </div>

                    <div>
                        <x-input-label for="completed_at" :value="__('Completed At')" />
                        <x-text-input id="completed_at" name="completed_at" type="date" class="mt-1 block w-full" :value="old('completed_at', $portfolio->completed_at?->format('Y-m-d'))" />
                        <x-input-error class="mt-2" :messages="$errors->get('completed_at')" />
                    </div>

                    <div>
                        <x-admin.toggle name="is_active" :checked="old('is_active', $portfolio->is_active)" label="Active" />
                    </div>

                    <details class="mt-4">
                        <summary class="text-sm font-medium cursor-pointer" style="color: var(--label-text)">{{ __('SEO Settings') }}</summary>
                        <div class="mt-4 space-y-4">
                            <div>
                                <x-input-label for="meta_title" :value="__('Meta Title')" />
                                <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title', $portfolio->meta_title)" />
                                <p class="form-hint">Optimal 50–60 karakter untuk Google Search. Otomatis menjadi judul share WhatsApp/Sosmed (OG Title) dan mengikuti judul utama jika dikosongkan.</p>
                            </div>
                            <div>
                                <x-input-label for="meta_description" :value="__('Meta Description')" />
                                <textarea id="meta_description" name="meta_description" class="form-textarea mt-1" rows="3">{{ old('meta_description', $portfolio->meta_description) }}</textarea>
                                <p class="form-hint">Optimal 150–160 karakter (termasuk spasi). Otomatis menjadi deskripsi share WhatsApp/Sosmed (OG Description) dan mengikuti deskripsi/konten jika dikosongkan.</p>
                            </div>
                            <div x-data="{ ogImage: '{{ old('og_image', $portfolio->og_image) }}', ogImageAlt: '{{ old('og_image_alt', $portfolio->og_image_alt) }}' }">
                                <x-input-label for="og_image" :value="__('OG Image')" />
                                <input type="hidden" name="og_image" id="og_image"
                                    value="{{ old('og_image', $portfolio->og_image) }}" />
                                <input type="hidden" name="og_image_alt" id="og_image_alt"
                                    value="{{ old('og_image_alt', $portfolio->og_image_alt) }}" />
                                <template x-if="ogImage">
                                    <div class="mb-2">
                                        <img :src="ogImage" :alt="ogImageAlt"
                                            class="rounded-lg"
                                            style="max-width:240px;max-height:120px;object-fit:cover">
                                        <p x-show="ogImageAlt" class="text-xs mt-1" x-text="'Alt: ' + ogImageAlt"
                                            style="color:var(--muted-text)"></p>
                                    </div>
                                </template>
                                <x-admin.media-picker target="og_image" collection="portfolio" />
                                <p class="form-hint">Optimal rasio 1.91:1 (1200x630 px) untuk banner sosmed. Otomatis mengikuti Cover Photo jika dikosongkan.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="robots_index" name="robots_index" value="1" class="form-checkbox" {{ old('robots_index', $portfolio->robots_index ?? true) ? 'checked' : '' }} />
                                <x-input-label for="robots_index" :value="__('Allow indexing')" />
                            </div>
                        </div>
                    </details>
                </div>

                <div class="border-t pt-6" style="border-color: var(--card-header-border)">
                    <h3 class="text-md font-semibold mb-4" style="color: var(--heading-text)">Gallery Photos</h3>

                    <div x-data="{ count: {{ $portfolio->media->count() }} }">
                        <div class="flex items-center gap-2">
                            <input type="file" accept="image/*" id="gallery-file-input" class="hidden">
                            <button type="button" onclick="document.getElementById('gallery-file-input').click()"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium btn-edit"
                                id="gallery-upload-btn"
                                x-bind:disabled="!photoUrl || count >= 4"
                                x-bind:class="(!photoUrl || count >= 4) && 'opacity-50 cursor-not-allowed'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                                </svg>
                                Upload Gallery Photo
                            </button>
                            <span class="text-xs" style="color: var(--muted-text)"><span x-text="count"></span> / 4 photos</span>
                        </div>
                        <p x-show="!photoUrl" class="text-xs mt-2 text-amber-500">
                            * Silakan pilih Cover Photo terlebih dahulu untuk mengaktifkan upload foto detail.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-4">
                        @forelse ($portfolio->media as $media)
                            <div class="relative group rounded-lg border overflow-hidden" style="border-color: var(--table-border)">
                                <div class="aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $media->url }}" alt="{{ $media->alt_text }}" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute inset-x-0 bottom-0 flex justify-center gap-1 p-1 opacity-0 group-hover:opacity-100 transition">
                                    <button type="button"
                                        onclick="if(confirm('Set this photo as cover?')) fetch('{{ route('admin.portfolio.media.set-cover', [$portfolio, $media]) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => location.reload())"
                                        class="p-1 rounded bg-blue-600 text-white text-xs font-medium px-2">
                                        Cover
                                    </button>
                                    <button type="button"
                                        onclick="if(confirm('Remove this photo?')) fetch('{{ route('admin.portfolio.media.detach', [$portfolio, $media]) }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => location.reload())"
                                        class="p-1 rounded bg-red-600 text-white text-xs font-medium px-2">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-sm" style="color: var(--muted-text)">Belum ada gallery photos.</div>
                        @endforelse
                    </div>
                </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const title = document.getElementById('title');
        const metaTitle = document.getElementById('meta_title');
        const metaDesc = document.getElementById('meta_description');
        const description = document.getElementById('description');

        if (title && metaTitle) {
            let metaTitleEdited = false;
            metaTitle.addEventListener('input', function () { if (this.value) metaTitleEdited = true; });
            title.addEventListener('input', function () {
                if (metaTitleEdited) return;
                metaTitle.value = this.value;
            });
        }

        if (description && metaDesc) {
            let metaDescEdited = false;
            metaDesc.addEventListener('input', function () { if (this.value) metaDescEdited = true; });
            description.addEventListener('input', function () {
                if (metaDescEdited) return;
                metaDesc.value = this.value;
            });
        }
    });

    document.getElementById('gallery-file-input').addEventListener('change', async function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const btn = document.getElementById('gallery-upload-btn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg> Uploading...';

        const formData = new FormData();
        formData.append('file', file);
        formData.append('collection', 'portfolio');

        try {
            const res = await fetch('{{ route("admin.media.upload-ajax") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData,
            });
            const data = await res.json();

            const attachRes = await fetch('{{ route("admin.portfolio.media.attach", $portfolio) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ media_id: data.id }),
            });

            if (!attachRes.ok) {
                const err = await attachRes.json();
                alert(err.message || 'Max 4 gallery photos reached.');
                return;
            }

            location.reload();
        } catch (err) {
            console.error('Gallery upload failed', err);
            alert('Upload failed.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg> Upload Gallery Photo';
            e.target.value = '';
        }
    });
</script>
@endpush

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('admin.portfolio.index') }}">
                        <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
