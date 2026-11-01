<x-layouts.dashboard title="Penilaian Tugas: {{ $tugas->judul }}">
    <div class="space-y-6">

        {{-- Header Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <a href="{{ route('dosen.pertemuan.show', [$kelas, $pertemuan]) }}" class="font-mono text-xs text-signal-blue hover:underline">&larr; Sesi Pertemuan {{ $pertemuan->pertemuan_ke }}</a>
                    <span class="text-olive-char text-xs">/</span>
                    <span class="font-mono text-xs font-bold text-deep-moss">assignment_grading.view</span>
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
                            <span class="tag-pill tag-pill-purple text-xs">Tugas Terstruktur</span>
                        </div>
                        <h1 class="text-2xl font-black text-deep-moss font-sans">{{ $tugas->judul }}</h1>
                        <p class="text-xs text-olive-char mt-1">
                            Batas Waktu: <span class="font-mono font-semibold text-deep-moss">{{ $tugas->batas_waktu->format('d M Y, H:i') }} WIB</span> | 
                            Keterlambatan: <span class="font-semibold">{{ $tugas->allow_late ? 'Diizinkan' : 'Ditolak' }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <form action="{{ route('dosen.tugas.sync-all', $kelas) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyinkronkan seluruh nilai rata-rata tugas ke rekap nilai akhir kelas?')">
                            @csrf
                            <button type="submit" class="btn-primary inline-flex items-center gap-1.5 text-xs py-2 px-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Sinkronkan Rata-Rata Tugas ke Nilai Akhir
                            </button>
                        </form>
                    </div>
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

                {{-- Summary Stats --}}
                @php
                    $totalEnrolled = $enrolledStudents->count();
                    $totalSubmitted = $submissions->count();
                    $totalGraded = $submissions->whereNotNull('nilai')->count();
                    $avgGrade = $totalGraded > 0 ? round($submissions->whereNotNull('nilai')->avg('nilai'), 1) : '-';
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="p-3 bg-white border border-warm-mist rounded">
                        <span class="text-[10px] uppercase font-mono text-olive-char block">Mahasiswa Terdaftar</span>
                        <span class="text-xl font-bold font-mono text-deep-moss">{{ $totalEnrolled }}</span>
                    </div>
                    <div class="p-3 bg-white border border-warm-mist rounded">
                        <span class="text-[10px] uppercase font-mono text-olive-char block">Sudah Mengumpulkan</span>
                        <span class="text-xl font-bold font-mono text-signal-blue">{{ $totalSubmitted }}</span>
                    </div>
                    <div class="p-3 bg-white border border-warm-mist rounded">
                        <span class="text-[10px] uppercase font-mono text-olive-char block">Sudah Dinilai</span>
                        <span class="text-xl font-bold font-mono text-emerald-700">{{ $totalGraded }}</span>
                    </div>
                    <div class="p-3 bg-white border border-warm-mist rounded">
                        <span class="text-[10px] uppercase font-mono text-olive-char block">Rata-Rata Nilai</span>
                        <span class="text-xl font-bold font-mono text-terracotta">{{ $avgGrade }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table List Submission Mahasiswa --}}
        <div class="window-card overflow-hidden bg-white">
            <div class="px-6 py-4 border-b border-warm-mist flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-deep-moss">
                    Daftar Pengumpulan Tugas Mahasiswa
                </h2>
                <span class="text-xs text-olive-char">Total: {{ $totalEnrolled }} Mahasiswa</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-warm-mist bg-soft-linen text-olive-char font-mono">
                            <th class="p-3">#</th>
                            <th class="p-3">Mahasiswa</th>
                            <th class="p-3">Status Pengumpulan</th>
                            <th class="p-3">File Jawaban</th>
                            <th class="p-3">Catatan Mahasiswa</th>
                            <th class="p-3 w-72">Penilaian & Umpan Balik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist">
                        @forelse($enrolledStudents as $index => $km)
                            @php
                                $mhs = $km->mahasiswa;
                                $sub = $submissions->get($mhs->id);
                            @endphp
                            <tr class="hover:bg-cream-paper/50">
                                <td class="p-3 font-mono text-olive-char">{{ $index + 1 }}</td>
                                <td class="p-3">
                                    <div class="font-semibold text-deep-moss">{{ $mhs->user->name }}</div>
                                    <div class="font-mono text-[10px] text-olive-char">{{ $mhs->nim }}</div>
                                </td>
                                <td class="p-3">
                                    @if($sub)
                                        @if($sub->is_terlambat)
                                            <span class="tag-pill tag-pill-amber text-[10px]">Terlambat</span>
                                        @else
                                            <span class="tag-pill tag-pill-green text-[10px]">Tepat Waktu</span>
                                        @endif
                                        <div class="font-mono text-[10px] text-olive-char mt-1">
                                            {{ $sub->dikumpulkan_pada?->format('d/m/Y H:i') }}
                                        </div>
                                    @else
                                        <span class="tag-pill tag-pill-red text-[10px]">Belum Mengumpulkan</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if($sub && $sub->file_path)
                                        <a href="{{ route('dosen.tugas.download-submission', [$kelas, $pertemuan, $tugas, $sub]) }}" class="inline-flex items-center gap-1 text-signal-blue hover:underline font-semibold text-[11px]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Unduh Jawaban
                                        </a>
                                    @else
                                        <span class="text-olive-char italic">-</span>
                                    @endif
                                </td>
                                <td class="p-3 max-w-xs truncate text-deep-moss">
                                    {{ $sub?->catatan_mahasiswa ?? '-' }}
                                </td>
                                <td class="p-3">
                                    @if($sub)
                                        <form action="{{ route('dosen.tugas.grade', [$kelas, $pertemuan, $tugas, $sub]) }}" method="POST" class="space-y-2">
                                            @csrf
                                            <div class="flex items-center gap-2">
                                                <label class="text-[10px] font-mono font-bold text-deep-moss">NILAI:</label>
                                                <input type="number" step="0.01" min="0" max="100" name="nilai" value="{{ old('nilai', $sub->nilai) }}" required class="w-20 text-xs font-mono font-bold p-1 rounded border-warm-mist">
                                                <button type="submit" class="btn-primary py-1 px-2 text-[10px]">
                                                    Simpan
                                                </button>
                                            </div>
                                            <div>
                                                <input type="text" name="umpan_balik" value="{{ old('umpan_balik', $sub->umpan_balik) }}" placeholder="Umpan balik dosen..." class="w-full text-xs p-1 rounded border-warm-mist">
                                            </div>
                                        </form>
                                    @else
                                        <span class="text-olive-char italic text-[11px]">Menunggu pengumpulan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-olive-char italic">
                                    Tidak ada mahasiswa terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.dashboard>

