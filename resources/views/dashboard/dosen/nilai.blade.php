<x-layouts.dashboard title="Kelola Nilai Mahasiswa">
    <div class="space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Kelola Nilai Mahasiswa</h1>
                <p class="mt-1 text-gray-500">Input dan evaluasi capaian nilai mahasiswa pada kelas yang Anda ampu.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('dosen.dashboard.download-nilai') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-emerald-700 transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Rekap Nilai (CSV)
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <p class="font-semibold mb-1">Terjadi kesalahan input:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Filter Kelas --}}
        @if(isset($allKelas) && $allKelas->isNotEmpty())
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-slate-700">Filter Kelas:</span>
                    <form method="GET" action="{{ route('dosen.nilai.index') }}" class="flex items-center gap-2">
                        <select name="kelas_id" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Kelas ({{ $allKelas->count() }}) --</option>
                            @foreach ($allKelas as $kOption)
                                <option value="{{ $kOption->id }}" @selected(($selectedKelas ?? 0) === $kOption->id)>
                                    {{ $kOption->nama }} - {{ $kOption->mataKuliah?->nama ?? 'Tanpa MK' }} ({{ $kOption->semester }} {{ $kOption->tahun_akademik }})
                                </option>
                            @endforeach
                        </select>
                        @if(($selectedKelas ?? 0) > 0)
                            <a href="{{ route('dosen.nilai.index') }}" class="text-xs text-blue-600 hover:text-blue-800 underline">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="text-xs text-gray-400">
                    Bobot Nilai: Kuis (10%), Tugas (20%), UTS (30%), UAS (40%)
                </div>
            </div>
        @endif

        @forelse ($kelas as $class)
            <section class="overflow-hidden rounded-xl bg-white shadow-sm border border-slate-100">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="font-bold text-slate-800 text-lg">
                                {{ $class->mataKuliah?->kode ?? '-' }} - {{ $class->mataKuliah?->nama ?? 'Tanpa Mata Kuliah' }}
                            </h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $class->nama }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Semester {{ $class->semester }} | Tahun Akademik {{ $class->tahun_akademik }} | Bobot: {{ $class->mataKuliah?->sks ?? 0 }} SKS
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-medium">
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
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-left text-slate-600 font-semibold">
                            <tr>
                                <th class="px-6 py-3.5">Mahasiswa</th>
                                <th class="px-3 py-3.5 text-center">Kuis (10%)</th>
                                <th class="px-3 py-3.5 text-center">Tugas (20%)</th>
                                <th class="px-3 py-3.5 text-center">UTS (30%)</th>
                                <th class="px-3 py-3.5 text-center">UAS (40%)</th>
                                <th class="px-6 py-3.5 text-center">Hasil Akhir</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($class->mahasiswas as $student)
                                @php
                                    $score = $class->nilais->firstWhere('mahasiswa_id', $student->id);
                                    $formId = 'score-form-'.$class->id.'-'.$student->id;
                                    $studentName = $student->nama ?? $student->user?->name ?? 'Mahasiswa';
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $studentName }}</div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $student->nim }}</div>
                                    </td>

                                    <td class="px-3 py-4 text-center">
                                        <input form="{{ $formId }}" class="w-20 rounded-lg border-slate-300 text-center text-sm focus:border-blue-500 focus:ring-blue-500 py-1.5" type="number" name="kuis" min="0" max="100" step="0.01" value="{{ $score?->kuis ?? 0 }}" required>
                                    </td>

                                    <td class="px-3 py-4 text-center">
                                        <input form="{{ $formId }}" class="w-20 rounded-lg border-slate-300 text-center text-sm focus:border-blue-500 focus:ring-blue-500 py-1.5" type="number" name="tugas" min="0" max="100" step="0.01" value="{{ $score?->tugas ?? 0 }}" required>
                                    </td>

                                    <td class="px-3 py-4 text-center">
                                        <input form="{{ $formId }}" class="w-20 rounded-lg border-slate-300 text-center text-sm focus:border-blue-500 focus:ring-blue-500 py-1.5" type="number" name="uts" min="0" max="100" step="0.01" value="{{ $score?->uts ?? 0 }}" required>
                                    </td>

                                    <td class="px-3 py-4 text-center">
                                        <input form="{{ $formId }}" class="w-20 rounded-lg border-slate-300 text-center text-sm focus:border-blue-500 focus:ring-blue-500 py-1.5" type="number" name="uas" min="0" max="100" step="0.01" value="{{ $score?->uas ?? 0 }}" required>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($score && $score->nilai_akhir !== null)
                                            <div class="font-bold text-slate-800 text-base">{{ number_format((float) $score->nilai_akhir, 2) }}</div>
                                            <div class="flex items-center justify-center gap-1.5 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-700">
                                                    {{ $score->grade }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $score->status === 'Lulus' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                                    {{ $score->status }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum dihitung</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <button form="{{ $formId }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-1.5 text-xs font-medium text-white hover:bg-blue-700 transition shadow-sm" type="submit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Simpan
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada mahasiswa yang terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @empty
            <div class="rounded-xl bg-white p-12 text-center text-gray-500 shadow-sm border border-slate-100">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="mt-4 text-base font-medium text-slate-700">Tidak ada kelas yang ditemukan.</p>
                <p class="text-sm text-gray-400 mt-1">Pastikan Anda telah ditugaskan mengampu kelas oleh admin.</p>
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>
