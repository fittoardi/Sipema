<x-layouts.dashboard title="Daftar Pertemuan Perkuliahan">
    <div class="space-y-6">

        {{-- Class Info Header --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <a href="{{ route('dosen.courses') }}" class="font-mono text-xs text-signal-blue hover:underline">&larr; Kembali ke Mata Kuliah</a>
                    <span class="text-olive-char text-xs">/</span>
                    <span class="font-mono text-xs font-bold text-deep-moss">session_timeline.lms</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-6 bg-cream-paper flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">{{ $kelas->mataKuliah->kode }} &bull; {{ $kelas->nama_kelas }}</span>
                        <span class="text-xs text-olive-char font-mono">{{ $kelas->tipe_kelas->value ?? $kelas->tipe_kelas }}</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-deep-moss font-sans">
                        {{ $kelas->mataKuliah->nama }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-olive-char font-mono">
                        <span class="font-bold text-deep-moss">{{ $kelas->hari->value ?? $kelas->hari }}, {{ $kelas->jam_rentang }}</span>
                        <span>&bull;</span>
                        <span>{{ $kelas->ruangan->nama ?? 'Online' }}</span>
                        <span>&bull;</span>
                        <span>Tahun: {{ $kelas->tahunAkademik->tahun ?? '-' }} {{ $kelas->tahunAkademik->semester->value ?? '' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-[11px] font-mono text-olive-char">Kapasitas Kelas</div>
                        <div class="text-xl font-bold font-mono text-deep-moss">{{ $kelas->kelasMahasiswa()->count() }} / {{ $kelas->kapasitas }} Mhs</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 16 Meetings Grid / List --}}
        <div class="window-card">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">sessions_list.db</span>
                    <span class="tag-pill tag-pill-blue text-[10px]">16 Sesi Pembelajaran</span>
                </div>
            </div>

            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-pale-stone/50 border-b border-warm-mist font-mono uppercase text-olive-char text-[11px]">
                        <tr>
                            <th class="px-4 py-2.5 text-center w-12">Sesi</th>
                            <th class="px-4 py-2.5">Judul Pertemuan & Topik</th>
                            <th class="px-4 py-2.5">Tanggal</th>
                            <th class="px-4 py-2.5">Mode & Ruangan</th>
                            <th class="px-4 py-2.5 text-center">Materi</th>
                            <th class="px-4 py-2.5 text-center">Tugas</th>
                            <th class="px-4 py-2.5 text-center">Presensi</th>
                            <th class="px-4 py-2.5 text-right">Kelola</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist font-sans">
                        @foreach($pertemuans as $p)
                            <tr class="hover:bg-soft-linen/50 transition {{ $p->is_presensi_open ? 'bg-amber-50/40' : '' }}">
                                <td class="px-4 py-3 text-center font-mono font-bold text-deep-moss">
                                    {{ $p->pertemuan_ke }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-deep-moss">{{ $p->judul }}</div>
                                    @if($p->berita_acara)
                                        <div class="text-[10px] text-olive-char line-clamp-1 italic mt-0.5">BAP: {{ $p->berita_acara }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-mono text-olive-char">
                                    {{ $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-mono uppercase {{ $p->mode->value === 'online' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $p->mode->value ?? $p->mode }}
                                    </span>
                                    <div class="text-[10px] font-mono text-olive-char mt-0.5">{{ $p->ruangan->nama ?? ($p->mode->value === 'online' ? 'Google Meet' : 'Ruang Kelas') }}</div>
                                </td>
                                <td class="px-4 py-3 text-center font-mono">
                                    <span class="tag-pill tag-pill-stone text-[10px]">{{ $p->materis_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center font-mono">
                                    <span class="tag-pill tag-pill-stone text-[10px]">{{ $p->tugas_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($p->is_presensi_open)
                                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300 font-mono font-bold text-[10px] animate-pulse">
                                            DIBUKA ({{ $p->kode_presensi ?? 'No PIN' }})
                                        </span>
                                    @else
                                        <span class="text-olive-char font-mono text-[10px]">Tutup ({{ $p->presensis_count }} hadir)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('dosen.pertemuan.show', [$kelas->id, $p->id]) }}" class="btn-amber text-[11px] py-1 px-2.5">
                                            Detail & Materi
                                        </a>
                                        <a href="{{ route('dosen.presensi.index', [$kelas->id, $p->id]) }}" class="btn-mist text-[11px] py-1 px-2">
                                            Presensi ({{ $p->presensis_count }})
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.dashboard>

