<x-layouts.dashboard title="Tugas: {{ $tugas->judul }}">
    <div class="space-y-6">

        {{-- Header Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="font-mono text-xs text-signal-blue hover:underline">&larr; Dashboard</a>
                    <span class="text-olive-char text-xs">/</span>
                    <span class="font-mono text-xs font-bold text-deep-moss">assignment.details</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-6 bg-cream-paper">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="tag-pill tag-pill-amber text-xs font-mono font-bold">{{ $kelas->mataKuliah->kode }} - {{ $kelas->nama_kelas }}</span>
                            <span class="tag-pill tag-pill-blue text-xs">Pertemuan {{ $pertemuan->pertemuan_ke }}</span>
                            @if($isOverdue)
                                <span class="tag-pill tag-pill-red text-xs">Batas Waktu Berakhir</span>
                            @else
                                <span class="tag-pill tag-pill-green text-xs">Aktif</span>
                            @endif
                        </div>
                        <h1 class="text-2xl font-black text-deep-moss font-sans">{{ $tugas->judul }}</h1>
                        <p class="text-xs text-olive-char mt-1">
                            Dosen: <span class="font-semibold text-deep-moss">{{ $kelas->dosen->user->name }}</span> | 
                            Batas Pengumpulan: <span class="font-mono font-semibold {{ $isOverdue ? 'text-terracotta' : 'text-signal-blue' }}">{{ $tugas->batas_waktu->format('d M Y, H:i') }} WIB</span>
                        </p>
                    </div>

                    @if($tugas->lampiran_path)
                        <div>
                            <a href="{{ route('mahasiswa.tugas.download-lampiran', $tugas) }}" class="btn-primary inline-flex items-center gap-2 text-xs py-2 px-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh Lampiran Soal
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Alert Messages --}}
                @if (session('success'))
                    <div class="mt-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-sm text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-sm text-sm text-red-800">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kolom Kiri: Instruksi Tugas --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="window-card p-6 bg-white space-y-4">
                    <h2 class="text-lg font-bold text-deep-moss flex items-center gap-2 border-b border-warm-mist pb-3">
                        <svg class="w-5 h-5 text-signal-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Instruksi & Petunjuk Tugas
                    </h2>
                    <div class="prose max-w-none text-sm text-deep-moss whitespace-pre-line leading-relaxed">
                        {{ $tugas->instruksi }}
                    </div>

                    @if($tugas->allow_late)
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded text-xs text-amber-800">
                            <strong>Catatan:</strong> Pengumpulan tugas melewati batas waktu tetap diizinkan namun akan ditandai terlambat.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Pengumpulan Tugas & Status --}}
            <div class="space-y-6">
                <div class="window-card p-6 bg-white space-y-5">
                    <h2 class="text-base font-bold text-deep-moss border-b border-warm-mist pb-2">
                        Status Pengumpulan
                    </h2>

                    @if($submission)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-olive-char">Status:</span>
                                @if($submission->is_terlambat)
                                    <span class="tag-pill tag-pill-amber text-[10px]">Terkumpul (Terlambat)</span>
                                @else
                                    <span class="tag-pill tag-pill-green text-[10px]">Terkumpul Tepat Waktu</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-olive-char">Waktu Submit:</span>
                                <span class="font-mono text-deep-moss">{{ $submission->dikumpulkan_pada?->format('d/m/Y H:i') }} WIB</span>
                            </div>

                            @if($submission->catatan_mahasiswa)
                                <div class="text-xs border-t border-warm-mist pt-2">
                                    <span class="text-olive-char block mb-1">Catatan Anda:</span>
                                    <p class="text-deep-moss italic bg-soft-linen p-2 rounded">{{ $submission->catatan_mahasiswa }}</p>
                                </div>
                            @endif

                            <div class="pt-2">
                                <a href="{{ route('mahasiswa.tugas.download-jawaban', $tugas) }}" class="w-full inline-flex justify-center items-center gap-1.5 btn-secondary text-xs py-2">
                                    <svg class="w-4 h-4 text-signal-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Unduh File Jawaban Anda
                                </a>
                            </div>

                            {{-- Hasil Penilaian Dosen --}}
                            <div class="border-t border-warm-mist pt-4 mt-4">
                                <div class="text-xs font-bold text-deep-moss mb-2">Penilaian Dosen</div>
                                @if(!is_null($submission->nilai))
                                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-sm">
                                        <div class="text-xs text-emerald-700">Nilai:</div>
                                        <div class="text-2xl font-black font-mono text-emerald-800">{{ $submission->nilai }} <span class="text-xs font-normal">/ 100</span></div>
                                        @if($submission->umpan_balik)
                                            <div class="mt-2 text-xs text-emerald-900 border-t border-emerald-200 pt-2">
                                                <span class="font-semibold">Umpan Balik:</span> {{ $submission->umpan_balik }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-xs text-olive-char italic">Tugas belum dinilai oleh dosen pengampu.</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4 bg-soft-linen rounded">
                            <span class="text-xs text-olive-char">Belum ada pengumpulan untuk tugas ini.</span>
                        </div>
                    @endif

                    {{-- Form Unggah / Perbarui Tugas --}}
                    @if($canSubmit)
                        <div class="border-t border-warm-mist pt-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-deep-moss mb-3">
                                {{ $submission ? 'Perbarui Pengumpulan File' : 'Unggah Jawaban Tugas' }}
                            </h3>
                            <form action="{{ route('mahasiswa.tugas.submit', $tugas) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-deep-moss mb-1">File Tugas (PDF, ZIP, DOCX maks. 20MB)</label>
                                    <input type="file" name="file" required class="block w-full text-xs text-deep-moss file:mr-2 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-signal-blue file:text-white hover:file:opacity-90">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-deep-moss mb-1">Catatan Tambahan (Opsional)</label>
                                    <textarea name="catatan_mahasiswa" rows="2" class="w-full text-xs rounded border-warm-mist p-2" placeholder="Catatan untuk dosen...">{{ old('catatan_mahasiswa') }}</textarea>
                                </div>
                                <button type="submit" class="btn-primary w-full py-2 text-xs font-bold text-center">
                                    {{ $submission ? 'Kirim Pembaruan Tugas' : 'Kirim Tugas Sekarang' }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="p-3 bg-red-50 border border-red-200 rounded text-xs text-terracotta">
                            Batas waktu pengumpulan telah berakhir. Pengumpulan sudah ditutup.
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</x-layouts.dashboard>

