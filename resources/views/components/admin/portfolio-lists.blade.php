@props(['specifications' => [], 'features' => []])

@if (! empty($specifications))
    <div class="mb-6">
        <p class="section-title">Spesifikasi Produk</p>
        <div class="mt-2 overflow-x-auto rounded-lg border" style="border-color: var(--table-border)">
            <table class="min-w-full divide-y" style="border-color: var(--table-border)">
                <tbody class="divide-y" style="border-color: var(--table-border)">
                    @foreach ($specifications as $spec)
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium" style="color: var(--label-text); background: var(--card-header-bg)">{{ $spec['key'] }}</td>
                            <td class="px-4 py-2 text-sm" style="color: var(--table-text)">{{ $spec['value'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@if (! empty($features))
    <div class="mb-6">
        <p class="section-title">Fitur Produk</p>
        <ul class="mt-2 space-y-1.5" style="color: var(--table-text)">
            @foreach ($features as $feature)
                <li class="flex items-start gap-2 text-sm">
                    <span class="mt-0.5" style="color: var(--muted-text)">•</span>
                    {{ $feature }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
