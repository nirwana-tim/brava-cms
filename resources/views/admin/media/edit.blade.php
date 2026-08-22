<x-admin.layouts.app>
    <x-slot name="title">{{ __('Edit Media') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.media.update', $medium) }}" method="POST">
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

                @if ($usage !== [])
                    <div class="mb-4 rounded-lg border p-4" style="border-color: var(--badge-active-bg); background-color: var(--badge-active-bg);">
                        <p class="text-sm font-medium" style="color: var(--badge-active-text)">Media ini sedang dipakai di:</p>
                        <ul class="mt-1 list-disc pl-5 text-sm" style="color: var(--badge-active-text)">
                            @foreach ($usage as $usedIn)
                                <li>{{ $usedIn }}</li>
                            @endforeach
                        </ul>
                        <p class="mt-2 text-sm" style="color: var(--badge-active-text)">Tidak bisa dihapus sampai referensinya dilepas dari konten tersebut.</p>
                    </div>
                @endif

                <div class="space-y-6">
                    @if (str_starts_with($medium->mime_type, 'image/'))
                        <div class="mb-4">
                            <img src="{{ $medium->url }}" alt="{{ $medium->alt_text }}" class="max-w-sm rounded shadow-sm">
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm pb-4 border-b" style="border-color: var(--card-header-border)">
                        <div>
                            <span class="font-medium" style="color: var(--label-text)">URL</span>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="text" value="{{ $medium->absolute_url }}" readonly
                                    class="flex-1 px-3 py-1.5 text-xs rounded border"
                                    style="border-color: var(--input-border); background-color: var(--input-bg); color: var(--input-text)">
                                <button type="button"
                                    onclick="navigator.clipboard.writeText({{ Js::from($medium->absolute_url) }}).then(() => { const s = this.querySelector('.copy-label'); if (s) { s.textContent = 'Copied!'; setTimeout(() => s.textContent = 'Copy', 2000); } })"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium btn-edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="copy-label">Copy</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <span class="font-medium" style="color: var(--label-text)">File Name</span>
                            <div class="mt-1" style="color: var(--table-text)">{{ $medium->file_name }}</div>
                        </div>
                        <div>
                            <span class="font-medium" style="color: var(--label-text)">Type</span>
                            <div class="mt-1" style="color: var(--table-text)">{{ $medium->mime_type }}</div>
                        </div>
                        <div>
                            <span class="font-medium" style="color: var(--label-text)">Size</span>
                            <div class="mt-1" style="color: var(--table-text)">{{ number_format($medium->size / 1024, 1) }} KB</div>
                        </div>
                        @if ($medium->collection)
                            <div>
                                <span class="font-medium" style="color: var(--label-text)">Collection</span>
                                <div class="mt-1" style="color: var(--table-text)">{{ $medium->collection }}</div>
                            </div>
                        @endif
                        <div>
                            <span class="font-medium" style="color: var(--label-text)">Uploaded At</span>
                            <div class="mt-1" style="color: var(--table-text)">{{ $medium->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="collection" :value="__('Collection')" />
                        <select id="collection" name="collection" class="form-select mt-1 block w-full">
                            @foreach (config('media.collections', ['general' => 'General', 'services' => 'Services', 'portfolio' => 'Portfolio', 'blogs' => 'Blogs', 'promos' => 'Promos', 'seo' => 'Page SEO']) as $key => $label)
                                <option value="{{ $key }}" {{ old('collection', $medium->collection ?? 'general') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('collection')" />
                    </div>

                    <div>
                        <x-input-label for="alt_text" :value="__('Alt Text')" />
                        <x-text-input id="alt_text" name="alt_text" type="text" class="mt-1 block w-full" :value="old('alt_text', $medium->alt_text)" />
                        <p class="form-hint">Describes the image for accessibility and SEO. Screen readers and search engines use this text.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('alt_text')" />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('admin.media.index') }}">
                        <x-secondary-button type="button">{{ __('Back') }}</x-secondary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
