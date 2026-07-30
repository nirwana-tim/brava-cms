<x-admin.layouts.app>
    <x-slot name="title">{{ __('Promo & Penawaran Spesial') }}</x-slot>

    <div class="card">
        <div class="card-header flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold" style="color: var(--heading-text)">Promo & Voucher</h2>
                <p class="text-xs" style="color: var(--muted-text)">Kelola banner promo utama (Highlight) dan daftar penawaran spesial.</p>
            </div>
            <a href="{{ route('admin.promos.create') }}">
                <x-primary-button>{{ __('New Promo') }}</x-primary-button>
            </a>
        </div>

        <div class="border-t" style="border-color: var(--card-border);">
            <div class="px-6 py-3">
                <form method="GET" action="{{ route('admin.promos.index') }}" class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-2">
                        <select name="status" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border" style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="coming_soon" {{ request('status') === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari promo..." class="form-input text-xs py-1.5 px-3 rounded-md border w-56" style="border-color: var(--card-border); background: var(--input-bg); color: var(--input-text);" />
                        <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Cari</button>
                        @if (request('search') || request('status'))
                            <a href="{{ route('admin.promos.index') }}" class="btn-secondary text-xs py-1.5 px-2.5" title="Reset Filter">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
        <div class="admin-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Promo Info</th>
                            <th>Discount</th>
                            <th>Valid Until</th>
                            <th>Highlight</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($promos as $promo)
                            <tr>
                                <td>
                                    <div class="flex flex-col">
                                        @if ($promo->badge_text)
                                            <span class="text-[10px] font-bold uppercase tracking-wider mb-0.5" style="color: var(--btn-primary-bg);">
                                                {{ $promo->badge_text }}
                                            </span>
                                        @endif
                                        <span class="font-medium" style="color: var(--table-text)">
                                            {{ Str::limit($promo->title, 50) }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-semibold text-sm" style="color: var(--heading-text)">
                                        {{ $promo->discount_info ?: '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($promo->valid_until)
                                        <span class="text-xs {{ $promo->is_expired ? 'text-red-500 font-semibold' : '' }}" style="{{ ! $promo->is_expired ? 'color: var(--muted-text)' : '' }}">
                                            {{ $promo->valid_until->format('d M Y') }}
                                            @if ($promo->is_expired)
                                                (Expired)
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-xs" style="color: var(--muted-text)">Tanpa batas</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($promo->is_highlighted)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full" style="background: rgba(234, 179, 8, 0.15); color: #ca8a04;">
                                            HERO BANNER
                                        </span>
                                    @else
                                        @if ($promo->is_active && ! $promo->is_expired)
                                            <form action="{{ route('admin.promos.highlight', $promo) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs font-medium underline hover:opacity-80" style="color: var(--muted-text)" title="Jadikan Hero Banner utama">
                                                    Jadikan Highlight
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs italic" style="color: var(--muted-text)">-</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if (! $promo->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-inactive">Inactive</span>
                                    @elseif ($promo->is_coming_soon)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" style="background: rgba(234, 179, 8, 0.15); color: #ca8a04;">Coming Soon</span>
                                    @elseif ($promo->is_expired)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" style="background: rgba(239, 68, 68, 0.15); color: #dc2626;">Expired</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge-active">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.promos.edit', $promo) }}" class="inline-flex items-center px-3 py-1.5 btn-edit rounded-md text-xs font-medium">Edit</a>
                                        <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 btn-delete rounded-md text-xs font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-table-empty">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p>Belum ada promo atau voucher.</p>
                                    <a href="{{ route('admin.promos.create') }}">Buat promo pertama</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($promos->hasPages())
                <div class="mt-4">
                    {{ $promos->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
