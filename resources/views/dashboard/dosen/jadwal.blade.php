<x-layouts.dashboard title="Jadwal Mengajar">
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-base font-bold text-deep-moss">Jadwal Perkuliahan Mengajar</h1>
                <p class="text-xs text-olive-char mt-0.5">Jadwal sesi kelas aktif yang Anda ampu setiap minggunya.</p>
            </div>

            <a href="{{ route('dosen.nilai.index') }}" class="btn-amber">
                Kelola Nilai
            </a>
        </div>

        <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
            <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
                <span class="font-bold text-deep-moss">Agenda Perkuliahan</span>
                <span class="font-mono text-sage-gray">{{ $jadwals->count() }} sesi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-2.5">Hari</th>
                            <th class="px-4 py-2.5">Waktu</th>
                            <th class="px-4 py-2.5">Mata Kuliah</th>
                            <th class="px-4 py-2.5">Kelas</th>
                            <th class="px-4 py-2.5">Ruangan</th>
                            <th class="px-4 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist">
                        @forelse($jadwals as $j)
                            @php
                                $dayPill = match($j->hari) {
                                    'Senin' => 'tag-pill-blue',
                                    'Selasa' => 'tag-pill-green',
                                    'Rabu' => 'tag-pill-amber',
                                    'Kamis' => 'tag-pill-orange',
                                    default => 'tag-pill-stone',
                                };
                            @endphp
                            <tr class="hover:bg-cream-paper transition">
                                <td class="px-4 py-3">
                                    <span class="tag-pill {{ $dayPill }} font-bold">
                                        {{ $j->hari }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono text-olive-char">
                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} WIB
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-deep-moss">{{ $j->mataKuliah?->nama ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-sage-gray">{{ $j->mataKuliah?->kode ?? '-' }} ({{ $j->mataKuliah?->sks ?? 0 }} SKS)</div>
                                </td>
                                <td class="px-4 py-3 font-mono font-bold text-deep-moss">
                                    {{ $j->kelas?->nama ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-olive-char font-mono">
                                    {{ $j->ruangan }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($j->kelas_id)
                                        <a href="{{ route('dosen.nilai.index', ['kelas_id' => $j->kelas_id]) }}" class="text-xs text-signal-blue hover:underline">
                                            Nilai &rarr;
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-xs text-sage-gray">
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
