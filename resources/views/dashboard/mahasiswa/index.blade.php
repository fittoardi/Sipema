<x-layouts.dashboard title="Dashboard Mahasiswa">
    <div class="space-y-6">
        {{-- Student Profile Window Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">student_profile.vcard</span>
                    <span class="tag-pill tag-pill-blue text-[10px]">Mahasiswa Aktif</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-6 bg-cream-paper flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-[4px] bg-paper-white border border-warm-mist flex items-center justify-center text-deep-moss text-2xl font-black font-mono shadow-none">
                        {{ strtoupper(substr($mahasiswa->user->name ?? 'M', 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="tag-pill tag-pill-amber text-[10px] font-mono">Angkatan {{ $mahasiswa->angkatan }}</span>
                            <span class="text-xs text-olive-char font-mono">NIM: {{ $mahasiswa->nim }}</span>
                        </div>
                        <h1 class="text-2xl font-black tracking-tight text-deep-moss font-sans">
                            {{ $mahasiswa->user->name }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-olive-char font-mono">
                            @if($mahasiswa->programStudi?->fakultas)
                                <span class="tag-pill tag-pill-stone text-[10px] font-bold">{{ $mahasiswa->programStudi->fakultas->nama }} ({{ $mahasiswa->programStudi->fakultas->kode }})</span>
                                <span>&bull;</span>
                            @endif
                            <span class="font-bold text-deep-moss">{{ $mahasiswa->prodi->nama_prodi }}</span>
                            <span>&bull;</span>
                            <span>{{ $mahasiswa->user->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('mahasiswa.jadwal') }}" class="btn-mist inline-flex items-center gap-2 text-xs">
                        <svg class="w-3.5 h-3.5 text-signal-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Jadwal & Kalender
                    </a>
                    <a href="{{ route('mahasiswa.dashboard.download-nilai') }}" class="btn-amber inline-flex items-center gap-2 text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Unduh Transkrip (CSV)
                    </a>
                </div>
            </div>
        </div>

        {{-- Academic Metric Cards --}}
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Program Studi & Fakultas --}}
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Fakultas & Prodi</p>
                <div class="mt-2 text-base font-black text-deep-moss line-clamp-1" title="{{ $mahasiswa->prodi->nama_prodi }}">
                    {{ $mahasiswa->prodi->nama_prodi }}
                </div>
                <div class="text-[11px] text-ash-green font-mono mt-1">
                    {{ $mahasiswa->programStudi?->fakultas?->nama ?? 'Kode: ' . ($mahasiswa->prodi->kode_prodi ?? '-') }}
                </div>
            </div>

            {{-- Mata Kuliah --}}
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Mata Kuliah</p>
                <div class="mt-2 text-2xl font-black text-deep-moss font-mono">
                    {{ $jumlahMataKuliah }}
                </div>
                <div class="text-[11px] text-ash-green font-mono mt-1">Kurikulum aktif</div>
            </div>

            {{-- Nilai Diperoleh --}}
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Nilai Diperoleh</p>
                <div class="mt-2 text-2xl font-black text-deep-moss font-mono">
                    {{ $jumlahNilai }}
                </div>
                <div class="text-[11px] text-ash-green font-mono mt-1">Mata kuliah dinilai</div>
            </div>

            {{-- Rata-rata Skor --}}
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Rata-rata Skor</p>
                <div class="mt-2 text-2xl font-black text-deep-moss font-mono">
                    {{ $rataNilai !== null ? number_format($rataNilai, 2) : '-' }}
                </div>
                <div class="text-[11px] text-ash-green font-mono mt-1">Skala kumulatif (0 - 100)</div>
            </div>
        </div>

        {{-- Jadwal Kuliah Hari Ini & Kalender Ringkas --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4 shadow-2xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-3 border-b border-warm-mist">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-signal-blue"></span>
                    <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">Perkuliahan Hari Ini ({{ $todayHari }}, {{ date('d M Y') }})</h2>
                    <span class="tag-pill tag-pill-blue text-[10px] font-mono">{{ $jadwalHariIni->count() }} Sesi Terjadwal</span>
                </div>
                <a href="{{ route('mahasiswa.jadwal') }}" class="text-xs text-signal-blue hover:underline font-mono font-bold inline-flex items-center gap-1">
                    Buka Kalender & Jadwal Lengkap &rarr;
                </a>
            </div>

            <div class="mt-3">
                @if($jadwalHariIni->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($jadwalHariIni as $jk)
                            @php
                                $pertemuanBuka = $jk->pertemuan->firstWhere('is_presensi_open', true);
                            @endphp
                            <div class="p-3 rounded-[4px] border border-warm-mist bg-cream-paper hover:bg-white transition flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between text-[11px] font-mono mb-1">
                                        <span class="font-bold text-deep-moss">{{ substr($jk->jam_mulai, 0, 5) }} - {{ substr($jk->jam_selesai, 0, 5) }} WIB</span>
                                        <span class="tag-pill text-[9px] {{ $jk->tipe_kelas?->value === 'online' ? 'tag-pill-blue' : 'tag-pill-green' }}">
                                            {{ strtoupper($jk->tipe_kelas?->value ?? 'OFFLINE') }}
                                        </span>
                                    </div>
                                    <h3 class="text-xs font-bold text-deep-moss">{{ $jk->mataKuliah?->nama ?? 'Mata Kuliah' }}</h3>
                                    <p class="text-[11px] text-olive-char font-mono mt-0.5">
                                        Kelas {{ $jk->nama_kelas }} &bull; {{ $jk->ruangan?->nama ?? 'Online / Zoom' }}
                                    </p>
                                    <p class="text-[10px] text-sage-gray mt-1 truncate">
                                        Dosen: {{ $jk->dosen?->nama ?? '-' }}
                                    </p>
                                </div>

                                <div class="mt-2.5 pt-2 border-t border-warm-mist flex items-center justify-between">
                                    <span class="text-[10px] font-mono text-sage-gray">{{ $jk->mataKuliah?->sks ?? 3 }} SKS</span>
                                    @if($pertemuanBuka)
                                        <a href="{{ route('mahasiswa.presensi.show', $pertemuanBuka->id) }}" class="btn-amber text-[10px] py-1 px-2 font-bold inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                            Absen Sekarang
                                        </a>
                                    @else
                                        <span class="text-[10px] font-mono text-sage-gray">Terjadwal</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-6 text-center text-sage-gray bg-cream-paper/50 rounded-[4px] border border-dashed border-warm-mist">
                        <svg class="w-6 h-6 mx-auto mb-1 text-sage-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-bold text-olive-char">Tidak ada jadwal kuliah hari ini ({{ $todayHari }}).</p>
                        <p class="text-[11px] text-sage-gray mt-0.5">Nikmati waktu belajar mandiri atau persiapkan materi untuk sesi berikutnya.</p>
                        <a href="{{ route('mahasiswa.jadwal') }}" class="text-[11px] font-mono font-bold text-signal-blue hover:underline mt-2 inline-block">
                            Lihat Jadwal Hari Lainnya &rarr;
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Daftar Nilai Akademik Table Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">academic_transcript.dat</span>
                    <span class="text-xs text-olive-char">({{ $nilais->count() }} Mata Kuliah)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-3 bg-cream-paper border-b border-warm-mist flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">Transkrip & Penilaian Akademik</h2>
                    <p class="text-[11px] text-olive-char">Rincian evaluasi hasil belajar kuis, tugas, UTS, UAS, dan status kelulusan</p>
                </div>
                <div class="text-[11px] font-mono text-olive-char bg-paper-white px-2 py-0.5 rounded-[4px] border border-warm-mist">
                    Bobot komponen diatur dinamis oleh Kurikulum
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-warm-mist text-xs">
                    <thead class="bg-soft-linen text-left text-deep-moss font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Mata Kuliah</th>
                            <th class="px-3 py-3 text-center">Kuis</th>
                            <th class="px-3 py-3 text-center">Tugas</th>
                            <th class="px-3 py-3 text-center">UTS</th>
                            <th class="px-3 py-3 text-center">UAS</th>
                            <th class="px-4 py-3 text-center">Nilai Akhir</th>
                            <th class="px-3 py-3 text-center">Grade</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist bg-paper-white">
                        @forelse ($nilais as $n)
                            @php
                                $statusStr = $n->status instanceof \App\Enums\StatusNilai ? $n->status->value : (string) ($n->status ?? '');
                                $isLulus = strtolower($statusStr) === 'lulus';
                            @endphp
                            <tr class="hover:bg-cream-paper transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-deep-moss">
                                        {{ $n->kelas?->mataKuliah?->nama ?? '-' }}
                                    </div>
                                    <div class="text-[11px] text-olive-char font-mono mt-0.5">
                                        {{ $n->kelas?->mataKuliah?->kode ?? '-' }} ({{ $n->kelas?->mataKuliah?->sks ?? 0 }} SKS) &bull; Kelas {{ $n->kelas?->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center font-mono text-deep-moss">{{ $n->kuis }}</td>
                                <td class="px-3 py-3 text-center font-mono text-deep-moss">{{ $n->tugas }}</td>
                                <td class="px-3 py-3 text-center font-mono text-deep-moss">{{ $n->uts }}</td>
                                <td class="px-3 py-3 text-center font-mono text-deep-moss">{{ $n->uas }}</td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-deep-moss text-sm">
                                    {{ number_format((float) ($n->nilai_akhir ?? 0), 2) }}
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="tag-pill font-mono font-bold text-xs bg-soft-linen text-deep-moss border border-warm-mist px-2.5">
                                        {{ $n->grade ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="tag-pill text-[11px] {{ $isLulus ? 'tag-pill-green' : 'tag-pill-orange' }}">
                                        {{ $n->status instanceof \App\Enums\StatusNilai ? $n->status->label() : (string) ($n->status ?? '-') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-olive-char">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-ash-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-xs font-bold text-deep-moss">Belum ada penilaian yang terdata.</p>
                                    <p class="text-[11px] text-olive-char mt-0.5">Nilai akan muncul setelah dosen pengampu menginputkan hasil evaluasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
