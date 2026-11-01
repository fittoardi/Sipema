<x-layouts.dashboard title="Kelola Nilai Mahasiswa">
    <div class="space-y-6">
        {{-- Window Header Banner --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-warm-mist">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="tag-pill tag-pill-amber">grade_management.exe</span>
                    <span class="text-xs text-olive-char font-mono">SIPEMA OS / Dosen Workspace</span>
                </div>
                <h1 class="text-2xl font-black text-deep-moss tracking-tight font-sans">Kelola Nilai Mahasiswa</h1>
                <p class="text-xs text-olive-char mt-0.5">Input dan evaluasi capaian nilai kuis, tugas, UTS, dan UAS mahasiswa pada kelas yang diampu.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('dosen.dashboard.download-nilai') }}" class="btn-amber inline-flex items-center gap-2 text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Rekap Nilai (CSV)
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('status'))
            <div class="rounded-[4px] border border-moss-green bg-moss-green/10 p-3 text-xs text-deep-moss flex items-center gap-2">
                <svg class="w-4 h-4 text-moss-green shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-medium">{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-[4px] border border-flame-orange bg-flame-orange/10 p-3 text-xs text-deep-moss">
                <p class="font-bold mb-1 text-flame-orange">Terjadi kesalahan input:</p>
                <ul class="list-disc list-inside space-y-0.5 text-olive-char">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Filter Kelas --}}
        @if(isset($allKelas) && $allKelas->isNotEmpty())
            <div class="window-card p-3 flex flex-wrap items-center justify-between gap-3 bg-cream-paper">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-deep-moss uppercase tracking-wider">Filter Kelas:</span>
                    <form method="GET" action="{{ route('dosen.nilai.index') }}" class="flex items-center gap-2">
                        <select name="kelas_id" onchange="this.form.submit()" class="posthog-input text-xs py-1 px-2.5 bg-paper-white">
                            <option value="">-- Semua Kelas ({{ $allKelas->count() }}) --</option>
                            @foreach ($allKelas as $kOption)
                                <option value="{{ $kOption->id }}" @selected(($selectedKelas ?? 0) === $kOption->id)>
                                    {{ $kOption->nama }} - {{ $kOption->mataKuliah?->nama ?? 'Tanpa MK' }} ({{ $kOption->semester }} {{ $kOption->tahun_akademik }})
                                </option>
                            @endforeach
                        </select>
                        @if(($selectedKelas ?? 0) > 0)
                            <a href="{{ route('dosen.nilai.index') }}" class="text-xs text-signal-blue hover:underline font-medium">Reset Filter</a>
                        @endif
                    </form>
                </div>
                <div class="text-[11px] text-olive-char font-mono bg-soft-linen px-2.5 py-1 rounded-[4px] border border-warm-mist">
                    Sistem Penilaian Dinamis: Kuis, Tugas, UTS, UAS terbobot otomatis
                </div>
            </div>
        @endif

        @forelse ($kelas as $class)
            <div class="window-card overflow-hidden">
                {{-- Window Title Bar --}}
                <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-xs font-bold text-deep-moss">class_scores_{{ Str::slug($class->nama) }}.db</span>
                        <span class="tag-pill tag-pill-amber text-[10px]">{{ $class->nama }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="window-control window-control-red"></div>
                        <div class="window-control window-control-yellow"></div>
                        <div class="window-control window-control-green"></div>
                    </div>
                </div>

                {{-- Class Meta Header --}}
                <div class="p-4 bg-cream-paper border-b border-warm-mist flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-deep-moss text-base">
                            {{ $class->mataKuliah?->kode ?? '-' }} &mdash; {{ $class->mataKuliah?->nama ?? 'Tanpa Mata Kuliah' }}
                        </h2>
                        <p class="text-xs text-olive-char mt-0.5 font-mono">
                            Semester {{ $class->semester }} | TA {{ $class->tahun_akademik }} | Bobot: {{ $class->mataKuliah?->sks ?? 0 }} SKS
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="tag-pill tag-pill-blue text-xs font-mono">
                            {{ $class->mahasiswas->count() }} Mahasiswa
                        </span>
                    </div>
                </div>

                {{-- Hidden Forms per Student --}}
                @foreach ($class->mahasiswas as $student)
                    <form id="score-form-{{ $class->id }}-{{ $student->id }}" method="POST" action="{{ route('dosen.nilai.store') }}">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $class->id }}">
                        <input type="hidden" name="mahasiswa_id" value="{{ $student->id }}">
                    </form>
                @endforeach

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-warm-mist text-xs">
                        <thead class="bg-soft-linen text-left text-deep-moss font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Mahasiswa</th>
                                <th class="px-3 py-3 text-center">Kuis</th>
                                <th class="px-3 py-3 text-center">Tugas</th>
                                <th class="px-3 py-3 text-center">UTS</th>
                                <th class="px-3 py-3 text-center">UAS</th>
                                <th class="px-4 py-3 text-center">Nilai Akhir</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-warm-mist bg-paper-white">
                            @forelse ($class->mahasiswas as $student)
                                @php
                                    $score = $class->nilais->firstWhere('mahasiswa_id', $student->id);
                                    $formId = 'score-form-'.$class->id.'-'.$student->id;
                                    $studentName = $student->nama ?? $student->user?->name ?? 'Mahasiswa';
                                @endphp
                                <tr class="hover:bg-cream-paper transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-deep-moss">{{ $studentName }}</div>
                                        <div class="text-[11px] text-olive-char font-mono">NIM: {{ $student->nim }}</div>
                                    </td>

                                    <td class="px-3 py-3 text-center">
                                        <input form="{{ $formId }}" class="posthog-input w-20 text-center font-mono py-1 text-xs" type="number" name="kuis" min="0" max="100" step="0.01" value="{{ $score?->kuis ?? 0 }}" required>
                                    </td>

                                    <td class="px-3 py-3 text-center">
                                        <input form="{{ $formId }}" class="posthog-input w-20 text-center font-mono py-1 text-xs" type="number" name="tugas" min="0" max="100" step="0.01" value="{{ $score?->tugas ?? 0 }}" required>
                                    </td>

                                    <td class="px-3 py-3 text-center">
                                        <input form="{{ $formId }}" class="posthog-input w-20 text-center font-mono py-1 text-xs" type="number" name="uts" min="0" max="100" step="0.01" value="{{ $score?->uts ?? 0 }}" required>
                                    </td>

                                    <td class="px-3 py-3 text-center">
                                        <input form="{{ $formId }}" class="posthog-input w-20 text-center font-mono py-1 text-xs" type="number" name="uas" min="0" max="100" step="0.01" value="{{ $score?->uas ?? 0 }}" required>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        @if($score && $score->nilai_akhir !== null)
                                            <div class="font-mono font-bold text-deep-moss text-sm">{{ number_format((float) $score->nilai_akhir, 2) }}</div>
                                            <div class="flex items-center justify-center gap-1.5 mt-0.5">
                                                <span class="tag-pill font-mono font-bold text-[10px] bg-soft-linen text-deep-moss border border-warm-mist">
                                                    {{ $score->grade }}
                                                </span>
                                                <span class="tag-pill text-[10px] {{ strtolower($score->status) === 'lulus' ? 'tag-pill-moss' : 'tag-pill-flame' }}">
                                                    {{ $score->status }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-ash-green font-mono italic">Belum dihitung</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-right">
                                        <button form="{{ $formId }}" class="btn-amber inline-flex items-center gap-1 text-xs py-1 px-2.5" type="submit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Simpan
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-olive-char text-xs">
                                        Belum ada mahasiswa yang terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="window-card p-12 text-center text-olive-char bg-cream-paper">
                <svg class="mx-auto h-10 w-10 text-ash-green mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-sm font-bold text-deep-moss">Tidak ada kelas yang ditemukan.</p>
                <p class="text-xs text-olive-char mt-1">Pastikan Anda telah ditugaskan mengampu kelas oleh admin pada semester aktif.</p>
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>
