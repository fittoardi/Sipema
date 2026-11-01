<x-layouts.dashboard title="Jadwal Mengajar">
    <div class="space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Jadwal Mengajar</h1>
                <p class="mt-1 text-gray-500">Jadwal sesi perkuliahan aktif yang Anda ampu setiap minggunya.</p>
            </div>

            <a href="{{ route('dosen.nilai.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-blue-700 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Kelola Nilai
            </a>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm border border-slate-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600 font-semibold">
                        <tr>
                            <th class="px-6 py-3.5">Hari</th>
                            <th class="px-6 py-3.5">Waktu</th>
                            <th class="px-6 py-3.5">Mata Kuliah</th>
                            <th class="px-6 py-3.5">Kelas</th>
                            <th class="px-6 py-3.5">Ruangan</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($jadwals as $j)
                            @php
                                $dayBadgeColor = match($j->hari) {
                                    'Senin' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Selasa' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Rabu' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'Kamis' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Jumat' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border {{ $dayBadgeColor }}">
                                        {{ $j->hari }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-700">
                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} WIB
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $j->mataKuliah?->nama ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $j->mataKuliah?->kode ?? '-' }} ({{ $j->mataKuliah?->sks ?? 0 }} SKS)</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">
                                        {{ $j->kelas?->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center gap-1.5 text-slate-700">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $j->ruangan }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($j->kelas_id)
                                        <a href="{{ route('dosen.nilai.index', ['kelas_id' => $j->kelas_id]) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800">
                                            Nilai &rarr;
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada jadwal mengajar yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

