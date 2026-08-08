<x-admin.layouts.app>
    <x-slot name="title">Activity Logs</x-slot>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--heading-text)">Activity Logs</h1>
            <p class="text-sm mt-1" style="color: var(--muted-text)">
                Audit trail of admin actions on sensitive modules (blogs, promos, portfolio, team). Only Super Admin can view this.
            </p>
        </div>

        <div class="card overflow-hidden">
            <div class="border-b px-6 py-3" style="border-color: var(--card-header-border)">
                <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="flex flex-wrap items-center gap-3">
                    <select name="event" onchange="this.form.submit()" class="form-select text-xs py-1.5 px-3 rounded-md border"
                        style="border-color: var(--card-border); background-color: var(--input-bg); color: var(--input-text);">
                        <option value="">Semua Event</option>
                        @foreach ($events as $key => $label)
                            <option value="{{ $key }}" {{ request('event') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari deskripsi aktivitas..."
                        class="form-input text-xs py-1.5 px-3 rounded-md border w-64"
                        style="border-color: var(--card-border); background: var(--input-bg); color: var(--input-text);" />

                    <button type="submit" class="btn-secondary text-xs py-1.5 px-3">Cari</button>

                    @if (request('q') || request('event'))
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn-secondary text-xs py-1.5 px-2.5" title="Reset Filter">Reset</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-xs uppercase" style="border-color: var(--table-border); color: var(--muted-text); background-color: var(--table-header-bg)">
                            <th class="py-3 px-4 font-semibold">Waktu</th>
                            <th class="py-3 px-4 font-semibold">User</th>
                            <th class="py-3 px-4 font-semibold">Event</th>
                            <th class="py-3 px-4 font-semibold">Aktivitas</th>
                            <th class="py-3 px-4 font-semibold">Loggable</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color: var(--table-border)">
                        @forelse ($logs as $log)
                            <tr class="hover:opacity-90 transition">
                                <td class="py-3 px-4 text-sm whitespace-nowrap" style="color: var(--muted-text)">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                    <span class="block text-xs opacity-75">{{ $log->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm font-medium whitespace-nowrap" style="color: var(--heading-text)">
                                    {{ $log->causerLabel() }}
                                    @if ($log->user_id === null)
                                        <span class="text-xs opacity-70">(system)</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @php
                                        $badge = match ($log->event) {
                                            'created' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
                                            'updated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                            'restored' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                            'force_deleted' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                                        {{ $events[$log->event] ?? $log->event }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm" style="color: var(--table-text)">
                                    {{ $log->description }}
                                </td>
                                <td class="py-3 px-4 text-sm font-mono" style="color: var(--muted-text)">
                                    {{ $log->loggable ? class_basename($log->loggable_type).' #'.$log->loggable_id : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center">
                                    <p class="text-base font-medium" style="color: var(--heading-text)">Belum ada aktivitas tercatat</p>
                                    <p class="text-sm mt-1" style="color: var(--muted-text)">Perubahan pada konten akan tercatat di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="p-4 border-t" style="border-color: var(--table-border)">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
