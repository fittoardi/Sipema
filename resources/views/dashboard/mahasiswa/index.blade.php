<x-layouts.dashboard title="Dashboard Mahasiswa">

<div class="space-y-8">
    {{-- Header Banner & Profile Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 p-8 text-white shadow-md">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-blue-600/90 border border-blue-400/30 flex items-center justify-center text-white text-2xl font-black shadow-inner shadow-blue-400/20">
                    {{ strtoupper(substr($mahasiswa->user->name ?? 'M', 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-400/20">
                            Mahasiswa Aktif
                        </span>
                        <span class="text-xs text-slate-400">Angkatan {{ $mahasiswa->angkatan }}</span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                        {{ $mahasiswa->user->name }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs text-slate-300">
                        <span class="font-mono bg-slate-800/80 px-2 py-0.5 rounded border border-slate-700">NIM: {{ $mahasiswa->nim }}</span>
                        <span>&bull;</span>
                        <span>{{ $mahasiswa->prodi->nama_prodi }}</span>
                        <span>&bull;</span>
                        <span class="text-slate-400">{{ $mahasiswa->user->email }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('mahasiswa.dashboard.download-nilai') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-semibold backdrop-blur-sm transition shadow-sm">
                    <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Unduh Transkrip (CSV)
                </a>
            </div>
        </div>

        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-80 h-80 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
    </div>

    {{-- Academic Metric Cards --}}
    <div class="grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Program Studi --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Program Studi</p>
                <h2 class="mt-2 text-xl font-extrabold text-slate-900 tracking-tight leading-tight line-clamp-1" title="{{ $mahasiswa->prodi->nama_prodi }}">
                    {{ $mahasiswa->prodi->nama_prodi }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Kode: {{ $mahasiswa->prodi->kode_prodi ?? '-' }}</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>

        {{-- Mata Kuliah --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Kuliah</p>
                <h2 class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $jumlahMataKuliah }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Kurikulum program studi</p>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>

        {{-- Nilai Diperoleh --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Diperoleh</p>
                <h2 class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $jumlahNilai }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Mata kuliah dinilai</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Rata-rata Skor --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata Skor</p>
                <h2 class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight font-mono">
                    {{ $rataNilai !== null ? number_format($rataNilai, 2) : '-' }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Skala kumulatif (0 - 100)</p>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            </div>
        </div>
    </div>

    {{-- Daftar Nilai Akademik Table Card --}}
    <div class="rounded-2xl bg-white shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Transkrip & Penilaian Akademik</h2>
                <p class="text-xs text-slate-500 mt-0.5">Rincian nilai kuis, tugas, ujian, dan status kelulusan</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                Total {{ $nilais->count() }} Mata Kuliah
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200/80 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Mata Kuliah</th>
                        <th class="px-6 py-3.5 text-center">Kuis (15%)</th>
                        <th class="px-6 py-3.5 text-center">Tugas (25%)</th>
                        <th class="px-6 py-3.5 text-center">UTS (25%)</th>
                        <th class="px-6 py-3.5 text-center">UAS (35%)</th>
                        <th class="px-6 py-3.5 text-center">Nilai Akhir</th>
                        <th class="px-6 py-3.5 text-center">Grade</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($nilais as $n)
                        @php
                            $gradeBadge = match($n->grade) {
                                'A' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'B' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'C' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'D' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'E' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                            $isLulus = strtolower($n->status) === 'lulus';
                        @endphp
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $n->kelas?->mataKuliah?->nama ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">
                                    {{ $n->kelas?->mataKuliah?->kode ?? '-' }} ({{ $n->kelas?->mataKuliah?->sks ?? 0 }} SKS) &bull; Kelas {{ $n->kelas?->nama ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-slate-600">{{ $n->kuis }}</td>
                            <td class="px-6 py-4 text-center font-mono text-slate-600">{{ $n->tugas }}</td>
                            <td class="px-6 py-4 text-center font-mono text-slate-600">{{ $n->uts }}</td>
                            <td class="px-6 py-4 text-center font-mono text-slate-600">{{ $n->uas }}</td>
                            <td class="px-6 py-4 text-center font-mono font-extrabold text-slate-900 text-base">
                                {{ number_format($n->nilai_akhir, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold border {{ $gradeBadge }}">
                                    {{ $n->grade }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full {{ $isLulus ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isLulus ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $n->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm font-medium text-slate-600">Belum ada penilaian yang terdata.</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Nilai akan muncul setelah dosen pengampu menginputkan hasil evaluasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-layouts.dashboard>
