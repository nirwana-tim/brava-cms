@props(['maxWidth' => 'max-w-6xl'])

<div x-data
    x-show="$store.imageEditor.isOpen"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    style="background-color: rgba(0,0,0,0.65)"
    @click.self="$store.imageEditor.close()">

    <div x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="card {{ $maxWidth }} w-full max-h-[92vh] flex flex-col shadow-2xl rounded-2xl">

        <div class="card-header flex items-center justify-between gap-3 shrink-0 px-5">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg" style="background-color: var(--card-header-bg, rgba(0,0,0,0.05))">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--heading-text)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-semibold leading-tight" style="color: var(--heading-text)">Edit Gambar</h3>
                    <p class="text-xs" style="color: var(--muted-text)">Pilih area, putar, atau balik, lalu simpan untuk di-upload</p>
                </div>
            </div>
            <button type="button" @click="$store.imageEditor.close()" class="p-2 rounded-lg hover:opacity-70 transition" style="color: var(--muted-text)" title="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="card-body flex-1 overflow-y-auto px-5">
            <div class="grid lg:grid-cols-[1fr_300px] gap-5">
                <div class="h-[50vh] lg:h-[62vh] rounded-xl overflow-hidden border-2 border-dashed"
                    style="border-color: var(--table-border); background-color: var(--input-bg)">
                    <cropper-canvas id="media-editor-canvas" background class="w-full h-full">
                        <cropper-image id="media-editor-image"
                            :src="$store.imageEditor.imageUrl"
                            alt="Preview"
                            class="w-full h-full"
                            rotatable scalable translatable></cropper-image>
                        <cropper-shade hidden></cropper-shade>
                        <cropper-handle action="select" plain></cropper-handle>
                        <cropper-selection id="media-editor-selection"
                            initial-coverage="0.8"
                            movable resizable outlined>
                            <cropper-grid role="grid" bordered covered></cropper-grid>
                            <cropper-crosshair centered></cropper-crosshair>
                            <cropper-handle action="move" theme-color="rgba(255,255,255,0.35)"></cropper-handle>
                            <cropper-handle action="n-resize"></cropper-handle>
                            <cropper-handle action="e-resize"></cropper-handle>
                            <cropper-handle action="s-resize"></cropper-handle>
                            <cropper-handle action="w-resize"></cropper-handle>
                            <cropper-handle action="ne-resize"></cropper-handle>
                            <cropper-handle action="nw-resize"></cropper-handle>
                            <cropper-handle action="se-resize"></cropper-handle>
                            <cropper-handle action="sw-resize"></cropper-handle>
                        </cropper-selection>
                    </cropper-canvas>
                </div>

                <aside class="flex flex-col gap-5">
                    <div class="rounded-xl border p-4" style="border-color: var(--table-border); background-color: var(--card-bg)">
                        <p class="text-[11px] font-semibold uppercase tracking-wider mb-2.5" style="color: var(--muted-text)">Rasio</p>
                        <div class="grid grid-cols-2 gap-1.5">
                            @foreach ([['Bebas', 0], ['1:1', 1], ['4:3', 4 / 3], ['3:2', 3 / 2], ['16:9', 16 / 9]] as [$label, $value])
                                <button type="button"
                                    @click="$store.imageEditor.setRatio({{ $value }})"
                                    :class="$store.imageEditor.ratio === {{ $value }} ? 'bg-[var(--btn-primary-bg)] text-white shadow-sm' : 'text-[var(--table-text)] hover:bg-gray-100 dark:hover:bg-gray-700'"
                                    class="px-2 py-1.5 rounded-lg text-xs font-medium transition">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-xl border p-4" style="border-color: var(--table-border); background-color: var(--card-bg)">
                        <p class="text-[11px] font-semibold uppercase tracking-wider mb-2.5" style="color: var(--muted-text)">Putar & Balik</p>
                        <div class="grid grid-cols-4 gap-1.5">
                            <button type="button" @click="$store.imageEditor.rotate(-1)" title="Putar ke kiri"
                                class="inline-flex items-center justify-center aspect-square rounded-lg text-xs font-medium transition hover:bg-gray-100 dark:hover:bg-gray-700"
                                style="color: var(--table-text)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
                                </svg>
                            </button>
                            <button type="button" @click="$store.imageEditor.rotate(1)" title="Putar ke kanan"
                                class="inline-flex items-center justify-center aspect-square rounded-lg text-xs font-medium transition hover:bg-gray-100 dark:hover:bg-gray-700"
                                style="color: var(--table-text)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            <button type="button" @click="$store.imageEditor.flip('h')" title="Balik horizontal (mirror)"
                                class="inline-flex items-center justify-center aspect-square rounded-lg text-xs font-medium transition hover:bg-gray-100 dark:hover:bg-gray-700"
                                style="color: var(--table-text)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h8m-4 4l-4-4 4-4"/>
                                </svg>
                            </button>
                            <button type="button" @click="$store.imageEditor.flip('v')" title="Balik vertikal (flip)"
                                class="inline-flex items-center justify-center aspect-square rounded-lg text-xs font-medium transition hover:bg-gray-100 dark:hover:bg-gray-700"
                                style="color: var(--table-text)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v8m-4-4l4 4 4-4"/>
                                </svg>
                            </button>
                        </div>
                        <button type="button" @click="$store.imageEditor.resetTransform()"
                            class="mt-3 inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium transition hover:bg-gray-100 dark:hover:bg-gray-700"
                            style="color: var(--table-text)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4.6 9A8 8 0 0119 5.6M19.4 15A8 8 0 015 18.4"/>
                            </svg>
                            Reset
                        </button>
                    </div>

                    <div class="rounded-xl border p-4" style="border-color: var(--table-border); background-color: var(--card-bg)">
                        <p class="text-[11px] font-semibold uppercase tracking-wider mb-2.5" style="color: var(--muted-text)">Alt Text</p>
                        <x-text-input id="media-editor-alt" type="text" class="block w-full"
                            x-model="$store.imageEditor.alt" placeholder="Deskripsi gambar untuk aksesibilitas & SEO" />
                    </div>
                </aside>
            </div>
        </div>

        <div class="card-header flex items-center justify-end gap-2 shrink-0 px-5">
            <button type="button" @click="$store.imageEditor.close()"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition hover:bg-gray-100 dark:hover:bg-gray-700"
                style="color: var(--table-text)">
                Batalkan
            </button>
            <button type="button" @click="$store.imageEditor.save()" :disabled="$store.imageEditor.saving"
                class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white bg-[var(--btn-primary-bg)] hover:bg-[var(--btn-primary-hover)] disabled:opacity-60 transition shadow-sm">
                <template x-if="$store.imageEditor.saving">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                </template>
                <svg x-show="!$store.imageEditor.saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span x-text="$store.imageEditor.saving ? 'Memproses...' : 'Simpan & Upload'"></span>
            </button>
        </div>
    </div>
</div>
