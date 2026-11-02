<x-layouts.dashboard title="Mata Kuliah & LMS Saya">
    <div class="space-y-6">
        {{-- Window Titlebar Header --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">learning_courses.lms</span>
                    <span class="tag-pill tag-pill-blue text-[10px]">Mahasiswa E-Learning</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-5 bg-cream-paper flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">Angkatan {{ $mahasiswa->angkatan }}</span>
                        <span class="text-xs text-olive-char font-mono">NIM: {{ $mahasiswa->nim }}</span>
                        @if($mahasiswa->programStudi?->fakultas)
                            <span class="tag-pill tag-pill-stone text-[10px] font-bold">{{ $mahasiswa->programStudi->fakultas->kode }}</span>
                        @endif
                    </div>
                    <h1 class="text-xl font-black tracking-tight text-deep-moss font-sans">
                        Mata Kuliah & Ruang Belajar LMS
                    </h1>
                    <p class="text-xs text-olive-char mt-0.5 font-mono">
                        Akses modul materi perkuliahan 16 sesi, pengumpulan tugas, dan absensi selfie & GPS kelas aktif Anda.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('mahasiswa.tugas.index') }}" class="btn-mist text-xs">
                        Pusat Tugas
                    </a>
                    <a href="{{ route('mahasiswa.krs.index') }}" class="btn-amber text-xs">
                        KRS Online
                    </a>
                </div>
            </div>
        </div>

        {{-- Metric Overview --}}
        <div class="grid gap-3 grid-cols-2 sm:grid-cols-4">
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Kelas Aktif</p>
                <div class="mt-1 text-2xl font-black text-deep-moss font-mono">{{ $kelasList->count() }}</div>
                <div class="text-[10px] text-ash-green font-mono">Rombel semester ini</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Total Beban SKS</p>
                <div class="mt-1 text-2xl font-black text-deep-moss font-mono">{{ $kelasList->sum('sks') }} SKS</div>
                <div class="text-[10px] text-ash-green font-mono">Kredit perkuliahan</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Total Materi</p>
                <div class="mt-1 text-2xl font-black text-deep-moss font-mono">{{ $kelasList->sum('total_materi') }} Modul</div>
                <div class="text-[10px] text-ash-green font-mono">Slide, video, & materi</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Rata Kehadiran</p>
                @php
                    $avgHadir = $kelasList->isNotEmpty() ? round($kelasList->avg('persen_hadir')) : 0;
                @endphp
                <div class="mt-1 text-2xl font-black {{ $avgHadir >= 75 ? 'text-emerald-700' : 'text-flame-orange' }} font-mono">
                    {{ $avgHadir }}%
                </div>
                <div class="text-[10px] text-ash-green font-mono">Batas minimal UAS: 75%</div>
            </div>
        </div>

        {{-- Course Cards Grid --}}
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($kelasList as $k)
                <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4 flex flex-col justify-between shadow-2xs hover:border-signal-blue transition">
                    <div>
                        {{-- Top Badges --}}
                        <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-warm-mist">
                            <span class="font-mono font-bold text-signal-blue text-xs">
                                {{ $k->mataKuliah?->kode ?? '-' }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="tag-pill tag-pill-stone font-mono font-bold text-[10px]">
                                    Kelas {{ $k->nama_kelas }}
                                </span>
                                <span class="tag-pill text-[9px] {{ $k->tipe_kelas?->value === 'online' ? 'tag-pill-blue' : 'tag-pill-green' }}">
                                    {{ strtoupper($k->tipe_kelas?->value ?? 'OFFLINE') }}
                                </span>
                            </div>
                        </div>

                        {{-- Course Title & Instructor --}}
                        <h3 class="text-sm font-bold text-deep-moss mt-3 leading-snug">
                            {{ $k->mataKuliah?->nama ?? 'Tanpa Mata Kuliah' }}
                        </h3>
                        <p class="text-[11px] text-olive-char font-mono mt-1">
                            Dosen: {{ $k->dosen?->nama ?? ($k->dosen?->user?->name ?? 'Dosen Pengampu') }}
                        </p>

                        {{-- Schedule & Room Details --}}
                        <div class="mt-3 p-2.5 rounded-[4px] bg-cream-paper border border-warm-mist space-y-1.5 text-xs font-mono">
                            <div class="flex items-center justify-between text-olive-char">
                                <span class="text-sage-gray">Jadwal:</span>
                                <span class="font-bold text-deep-moss">{{ $k->hari?->value ?? $k->hari }}, {{ substr($k->jam_mulai, 0, 5) }} - {{ substr($k->jam_selesai, 0, 5) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-olive-char">
                                <span class="text-sage-gray">Ruangan:</span>
                                <span class="text-deep-moss">{{ $k->ruangan?->nama ?? ($k->tipe_kelas?->value === 'online' ? 'Daring / Zoom' : 'Ruang Kelas') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-olive-char">
                                <span class="text-sage-gray">Beban:</span>
                                <span class="font-bold text-deep-moss">{{ $k->sks }} SKS</span>
                            </div>
                        </div>

                        {{-- Attendance & Materials Progress --}}
                        <div class="mt-3 space-y-2">
                            <div>
                                <div class="flex items-center justify-between text-[11px] font-mono mb-1">
                                    <span class="text-sage-gray">Kehadiran ({{ $k->hadir_count }}/{{ $k->total_pertemuan }} Sesi)</span>
                                    <span class="font-bold {{ $k->persen_hadir >= 75 ? 'text-emerald-700' : 'text-flame-orange' }}">
                                        {{ $k->persen_hadir }}%
                                    </span>
                                </div>
                                <div class="w-full bg-soft-linen rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full {{ $k->persen_hadir >= 75 ? 'bg-emerald-600' : 'bg-flame-orange' }}" style="width: {{ min(100, $k->persen_hadir) }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between text-[11px] font-mono mb-1">
                                    <span class="text-sage-gray">Materi Dipelajari</span>
                                    <span class="font-bold text-signal-blue">{{ $k->persen_materi }}%</span>
                                </div>
                                <div class="w-full bg-soft-linen rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full bg-signal-blue" style="width: {{ min(100, $k->persen_materi) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Footer Actions --}}
                    <div class="mt-4 pt-3 border-t border-warm-mist flex items-center justify-between gap-2">
                        <a href="{{ route('mahasiswa.presensi.index') }}" class="text-[11px] font-mono text-sage-gray hover:text-deep-moss hover:underline">
                            Rekap Presensi
                        </a>
                        <a href="{{ route('mahasiswa.kelas.show', $k->id) }}" class="btn-amber text-xs py-1.5 px-3 inline-flex items-center gap-1.5">
                            Buka Ruang LMS &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-paper-white border border-warm-mist rounded-[4px] p-10 text-center text-sage-gray">
                    <svg class="w-12 h-12 mx-auto mb-3 text-warm-mist" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <h3 class="text-sm font-bold text-deep-moss">Belum Ada Mata Kuliah yang Diambil</h3>
                    <p class="text-xs text-olive-char mt-1 max-w-md mx-auto">
                        Anda belum terdaftar dalam kelas perkuliahan aktif. Silakan pilih mata kuliah pada masa pengisian KRS Online.
                    </p>
                    <a href="{{ route('mahasiswa.krs.index') }}" class="btn-amber text-xs mt-4 inline-flex">
                        Isi KRS Sekarang &rarr;
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>

