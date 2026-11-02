<x-layouts.dashboard title="Ruang Belajar LMS - {{ $kelas->mataKuliah?->nama }}">
    <div class="space-y-6">
        {{-- Class Info Header Window Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <a href="{{ route('mahasiswa.kelas.index') }}" class="font-mono text-xs text-signal-blue hover:underline">&larr; Mata Kuliah Saya</a>
                    <span class="text-olive-char text-xs">/</span>
                    <span class="font-mono text-xs font-bold text-deep-moss">lms_classroom.sys</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-6 bg-cream-paper flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">{{ $kelas->mataKuliah?->kode }} &bull; Kelas {{ $kelas->nama_kelas }}</span>
                        <span class="tag-pill text-[9px] {{ $kelas->tipe_kelas?->value === 'online' ? 'tag-pill-blue' : 'tag-pill-green' }}">
                            {{ strtoupper($kelas->tipe_kelas?->value ?? 'OFFLINE') }}
                        </span>
                        <span class="tag-pill tag-pill-stone text-[10px] font-mono">{{ $kelas->sks }} SKS</span>
                    </div>

                    <h1 class="text-2xl font-black tracking-tight text-deep-moss font-sans">
                        {{ $kelas->mataKuliah?->nama }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-olive-char font-mono">
                        <span class="font-bold text-deep-moss">{{ $kelas->hari?->value ?? $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }} WIB</span>
                        <span>&bull;</span>
                        <span>{{ $kelas->ruangan?->nama ?? 'Online / Zoom' }}</span>
                        <span>&bull;</span>
                        <span>Dosen: {{ $kelas->dosen?->nama ?? ($kelas->dosen?->user?->name ?? 'Dosen Pengampu') }}</span>
                    </div>
                </div>

                {{-- Attendance Badge Summary --}}
                <div class="p-3.5 rounded-[4px] bg-paper-white border border-warm-mist flex items-center gap-4 text-xs font-mono shadow-2xs">
                    <div>
                        <div class="text-[10px] text-sage-gray uppercase">Kehadiran Kelas</div>
                        <div class="text-lg font-bold {{ $persenKehadiran >= 75 ? 'text-emerald-700' : 'text-flame-orange' }}">
                            {{ $persenKehadiran }}%
                        </div>
                        <div class="text-[10px] text-olive-char">{{ $totalHadir }} / {{ $totalSesi }} Sesi Hadir</div>
                    </div>
                    <div class="h-8 w-px bg-warm-mist"></div>
                    <div>
                        <div class="text-[10px] text-sage-gray uppercase">Syarat UAS</div>
                        <div class="text-xs font-bold {{ $persenKehadiran >= 75 ? 'text-emerald-700' : 'text-flame-orange' }}">
                            {{ $persenKehadiran >= 75 ? 'Memenuhi Syarat' : 'Peringatan < 75%' }}
                        </div>
                        <div class="text-[10px] text-sage-gray">Min. 75% hadir</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 16 Learning Sessions Timeline --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-deep-moss uppercase tracking-wider font-mono">Daftar Pertemuan (Sesi 1 — 16)</h2>
                    <p class="text-xs text-olive-char mt-0.5">Pelajari materi, selesaikan tugas, dan lakukan presensi live foto selfie & GPS.</p>
                </div>
                <span class="text-xs font-mono text-sage-gray">{{ $pertemuans->count() }} Sesi Terjadwal</span>
            </div>

            <div class="space-y-3">
                @forelse($pertemuans as $p)
                    @php
                        $presensi = $p->presensis->first();
                        $statusPresensi = $presensi ? strtolower($presensi->status?->value ?? (string) $presensi->status) : null;
                    @endphp
                    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden shadow-2xs">
                        {{-- Session Card Header --}}
                        <div class="px-4 py-3 bg-cream-paper border-b border-warm-mist flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-[4px] bg-deep-moss text-paper-white font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ $p->pertemuan_ke }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-deep-moss text-sm">
                                            {{ $p->judul ?? 'Pertemuan Sesi ke-' . $p->pertemuan_ke }}
                                        </h3>
                                        <span class="tag-pill text-[9px] {{ $p->mode?->value === 'online' ? 'tag-pill-blue' : 'tag-pill-green' }}">
                                            {{ strtoupper($p->mode?->value ?? 'OFFLINE') }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] font-mono text-sage-gray mt-0.5">
                                        {{ $p->tanggal ? $p->tanggal->format('d M Y') : 'Jadwal Reguler' }} &bull;
                                        {{ $p->ruangan?->nama ?? ($p->mode?->value === 'online' ? 'Online Meeting' : 'Ruang Kelas') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Attendance Status / Action --}}
                            <div class="flex items-center gap-2">
                                @if($p->is_presensi_open)
                                    <a href="{{ route('mahasiswa.presensi.show', $p->id) }}"
                                       class="btn-amber text-xs py-1.5 px-3 inline-flex items-center gap-1.5 font-bold animate-pulse">
                                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                                        Absen Selfie & GPS &rarr;
                                    </a>
                                @elseif($statusPresensi)
                                    @php
                                        $pillClass = match($statusPresensi) {
                                            'hadir' => 'tag-pill-green',
                                            'izin' => 'tag-pill-blue',
                                            'sakit' => 'tag-pill-amber',
                                            default => 'tag-pill-orange',
                                        };
                                    @endphp
                                    <span class="tag-pill {{ $pillClass }} font-bold text-xs uppercase px-2.5 py-1">
                                        Status: {{ $statusPresensi }}
                                    </span>
                                @else
                                    <span class="text-[11px] font-mono text-sage-gray bg-soft-linen px-2 py-1 rounded border border-warm-mist">
                                        Presensi Belum Dibuka
                                    </span>
                                @endif

                                @if($p->tautan_meeting)
                                    <a href="{{ $p->tautan_meeting }}" target="_blank" class="btn-mist text-xs py-1.5 px-2.5 inline-flex items-center gap-1 text-signal-blue" title="Buka Zoom / Google Meet">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        Meeting
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Session Content: Materials & Tasks --}}
                        <div class="p-4 space-y-4">
                            {{-- Materi Section --}}
                            <div>
                                <h4 class="text-xs font-bold text-olive-char uppercase tracking-wider font-mono mb-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-signal-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Materi Pembelajaran ({{ $p->materis->count() }})
                                </h4>

                                @if($p->materis->isNotEmpty())
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                        @foreach($p->materis as $m)
                                            @php
                                                $isSelesai = $m->progresMateri->isNotEmpty();
                                            @endphp
                                            <div class="p-3 rounded-[4px] border {{ $isSelesai ? 'border-emerald-300 bg-emerald-50/30' : 'border-warm-mist bg-paper-white' }} flex items-start justify-between gap-3 text-xs">
                                                <div class="flex items-start gap-2.5 min-w-0">
                                                    <span class="mt-0.5 w-6 h-6 rounded flex items-center justify-center font-bold text-[10px] {{ $m->tipe === 'file' ? 'bg-blue-100 text-signal-blue' : ($m->tipe === 'video' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700') }}">
                                                        {{ strtoupper(substr($m->tipe, 0, 3)) }}
                                                    </span>
                                                    <div class="min-w-0">
                                                        <div class="font-bold text-deep-moss text-xs truncate" title="{{ $m->judul }}">{{ $m->judul }}</div>
                                                        <div class="text-[10px] text-sage-gray font-mono mt-0.5">Tipe: <span class="capitalize">{{ $m->tipe }}</span></div>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                                    @if($m->file_path || $m->konten_atau_url)
                                                        <a href="{{ route('mahasiswa.materi.download', $m->id) }}"
                                                           target="{{ $m->tipe === 'file' ? '_self' : '_blank' }}"
                                                           class="btn-mist text-[11px] py-1 px-2 text-signal-blue inline-flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                            Buka
                                                        </a>
                                                    @endif

                                                    <form method="POST" action="{{ route('mahasiswa.materi.toggle', $m->id) }}">
                                                        @csrf
                                                        <button type="submit"
                                                                class="px-2 py-1 rounded text-[10px] font-mono transition border {{ $isSelesai ? 'bg-emerald-600 text-white border-emerald-600 font-bold' : 'bg-paper-white text-olive-char border-warm-mist hover:bg-pale-stone' }}"
                                                                title="{{ $isSelesai ? 'Klik untuk batal selesai' : 'Klik tandai selesai belajar' }}">
                                                            {{ $isSelesai ? '✓ Selesai' : 'Tandai Selesai' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[11px] text-sage-gray italic">Belum ada materi ajar yang diunggah dosen untuk sesi ini.</p>
                                @endif
                            </div>

                            {{-- Tugas Section --}}
                            @if($p->tugas->isNotEmpty())
                                <div class="pt-3 border-t border-warm-mist/60">
                                    <h4 class="text-xs font-bold text-olive-char uppercase tracking-wider font-mono mb-2 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-flame-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Penugasan Sesi ({{ $p->tugas->count() }})
                                    </h4>

                                    <div class="space-y-2">
                                        @foreach($p->tugas as $t)
                                            @php
                                                $submission = $t->pengumpulans->first();
                                                $isSubmitted = $submission !== null;
                                                $isGraded = $submission && $submission->nilai !== null;
                                            @endphp
                                            <div class="p-3 rounded-[4px] border border-warm-mist bg-cream-paper flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs">
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-deep-moss text-xs">{{ $t->judul }}</span>
                                                        @if($isGraded)
                                                            <span class="tag-pill tag-pill-green font-mono font-bold text-[10px]">Nilai: {{ number_format($submission->nilai, 1) }}</span>
                                                        @elseif($isSubmitted)
                                                            <span class="tag-pill tag-pill-blue font-mono text-[10px]">Menunggu Penilaian</span>
                                                        @else
                                                            <span class="tag-pill tag-pill-amber font-mono text-[10px]">Perlu Dikerjakan</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-[11px] font-mono text-sage-gray mt-0.5">
                                                        Tenggat: <span class="font-bold text-deep-moss">{{ $t->batas_waktu->format('d M Y, H:i') }} WIB</span>
                                                    </div>
                                                </div>

                                                <a href="{{ route('mahasiswa.tugas.show', $t->id) }}" class="btn-amber text-xs py-1.5 px-3 self-start sm:self-auto inline-flex items-center gap-1">
                                                    {{ $isSubmitted ? 'Lihat Pengumpulan &rarr;' : 'Kerjakan Tugas &rarr;' }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-8 text-center text-sage-gray">
                        <p class="text-xs font-bold text-deep-moss">Sesi perkuliahan belum dibuat oleh dosen pengampu.</p>
                        <p class="text-[11px] text-olive-char mt-1">Dosen akan menyusun jadwal sesi 1-16 sepanjang semester berjalan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.dashboard>

