<x-layouts.dashboard title="Pusat Tugas & Penugasan Mahasiswa">
    <div class="space-y-6">
        {{-- Window Titlebar Header --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">assignments_center.task</span>
                    <span class="tag-pill tag-pill-blue text-[10px]">Mahasiswa Portal</span>
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
                    </div>
                    <h1 class="text-xl font-black tracking-tight text-deep-moss font-sans">
                        Pusat Tugas &amp; Evaluasi Perkuliahan
                    </h1>
                    <p class="text-xs text-olive-char mt-0.5 font-mono">
                        Pantau seluruh tenggat tugas (*deadline*), unggah submission, dan cek evaluasi nilai &amp; umpan balik dosen.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('mahasiswa.kelas.index') }}" class="btn-mist text-xs">
                        Mata Kuliah Saya
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn-amber text-xs">
                        Dashboard Nilai
                    </a>
                </div>
            </div>
        </div>

        {{-- Metric Overview --}}
        <div class="grid gap-3 grid-cols-2 sm:grid-cols-4">
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Total Tugas</p>
                <div class="mt-1 text-2xl font-black text-deep-moss font-mono">{{ $totalAll }}</div>
                <div class="text-[10px] text-ash-green font-mono">Seluruh mata kuliah</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Perlu Dikerjakan</p>
                <div class="mt-1 text-2xl font-black {{ $totalPending > 0 ? 'text-flame-orange' : 'text-deep-moss' }} font-mono">
                    {{ $totalPending }}
                </div>
                <div class="text-[10px] text-ash-green font-mono">Belum dikumpulkan</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Menunggu Nilai</p>
                <div class="mt-1 text-2xl font-black text-signal-blue font-mono">{{ $totalSubmitted }}</div>
                <div class="text-[10px] text-ash-green font-mono">Sudah diunggah</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Selesai Dinilai</p>
                <div class="mt-1 text-2xl font-black text-emerald-700 font-mono">{{ $totalGraded }}</div>
                <div class="text-[10px] text-ash-green font-mono">Skor &amp; umpan balik</div>
            </div>
        </div>

        {{-- Filter Tabs Bar --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-3 shadow-2xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs">
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                    <a href="{{ route('mahasiswa.tugas.index') }}"
                       class="px-3 py-1.5 rounded-[4px] font-mono text-xs transition whitespace-nowrap {{ $filterStatus === 'semua' ? 'bg-deep-moss text-paper-white font-bold' : 'bg-cream-paper text-olive-char border border-warm-mist hover:bg-pale-stone' }}">
                        Semua Tugas ({{ $totalAll }})
                    </a>
                    <a href="{{ route('mahasiswa.tugas.index', ['status' => 'pending']) }}"
                       class="px-3 py-1.5 rounded-[4px] font-mono text-xs transition whitespace-nowrap {{ $filterStatus === 'pending' ? 'bg-flame-orange text-paper-white font-bold' : 'bg-cream-paper text-olive-char border border-warm-mist hover:bg-pale-stone' }}">
                        Perlu Dikerjakan ({{ $totalPending }})
                    </a>
                    <a href="{{ route('mahasiswa.tugas.index', ['status' => 'dikumpulkan']) }}"
                       class="px-3 py-1.5 rounded-[4px] font-mono text-xs transition whitespace-nowrap {{ $filterStatus === 'dikumpulkan' ? 'bg-signal-blue text-paper-white font-bold' : 'bg-cream-paper text-olive-char border border-warm-mist hover:bg-pale-stone' }}">
                        Menunggu Penilaian ({{ $totalSubmitted }})
                    </a>
                    <a href="{{ route('mahasiswa.tugas.index', ['status' => 'dinilai']) }}"
                       class="px-3 py-1.5 rounded-[4px] font-mono text-xs transition whitespace-nowrap {{ $filterStatus === 'dinilai' ? 'bg-emerald-700 text-paper-white font-bold' : 'bg-cream-paper text-olive-char border border-warm-mist hover:bg-pale-stone' }}">
                        Selesai Dinilai ({{ $totalGraded }})
                    </a>
                </div>
            </div>
        </div>

        {{-- Assignments List --}}
        <div class="space-y-3">
            @forelse($tugasList as $t)
                <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-2xs hover:border-signal-blue transition">
                    <div class="space-y-1.5 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2 text-xs font-mono">
                            <span class="font-bold text-signal-blue">{{ $t->pertemuan?->kelas?->mataKuliah?->kode ?? 'MK' }}</span>
                            <span class="text-sage-gray">&bull;</span>
                            <span class="text-deep-moss font-semibold">{{ $t->pertemuan?->kelas?->mataKuliah?->nama ?? 'Mata Kuliah' }}</span>
                            <span class="text-sage-gray">&bull;</span>
                            <span class="text-olive-char">Sesi Ke-{{ $t->pertemuan?->pertemuan_ke ?? '-' }}</span>
                            <span class="tag-pill {{ $t->status_badge }} text-[10px] font-bold">
                                {{ $t->status_label }}
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-deep-moss leading-snug">
                            {{ $t->judul }}
                        </h3>

                        @if($t->instruksi)
                            <p class="text-xs text-olive-char line-clamp-2">
                                {{ Str::limit(strip_tags($t->instruksi), 160) }}
                            </p>
                        @endif

                        <div class="flex flex-wrap items-center gap-3 pt-1 text-[11px] font-mono text-olive-char">
                            <div>
                                <span class="text-sage-gray">Tenggat Waktu:</span>
                                <span class="font-bold {{ $t->is_overdue && ! $t->is_submitted ? 'text-flame-orange' : 'text-deep-moss' }}">
                                    {{ $t->batas_waktu->format('d M Y, H:i') }} WIB ({{ $t->countdown_text }})
                                </span>
                            </div>
                            @if($t->is_graded)
                                <div class="text-emerald-700 font-bold">
                                    Nilai: {{ number_format($t->submission->nilai, 1) }} / 100
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0 self-start md:self-auto">
                        <a href="{{ route('mahasiswa.tugas.show', $t->id) }}" class="btn-amber text-xs py-2 px-3.5 inline-flex items-center gap-1.5">
                            {{ $t->is_submitted ? 'Lihat Jawaban & Feedback &rarr;' : 'Unggah Jawaban &rarr;' }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-paper-white border border-warm-mist rounded-[4px] p-10 text-center text-sage-gray">
                    <svg class="w-10 h-10 mx-auto mb-2 text-warm-mist" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-sm font-bold text-deep-moss">Tidak Ada Tugas Ditemukan</h3>
                    <p class="text-xs text-olive-char mt-0.5">Semua tugas pada kategori ini telah selesai atau belum ada penugasan baru.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>

