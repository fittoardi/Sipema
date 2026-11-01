@extends('layouts.admin')

@section('title', 'Laporan & Ekspor Nilai')
@section('header', 'Laporan & Ekspor Nilai')

@section('content')
<div class="space-y-6">
    {{-- Filter & Export Card --}}
    <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-800">Filter Data Nilai</h3>
                <p class="text-xs text-slate-500 mt-0.5">Saring data penilaian mahasiswa berdasarkan program studi dan rombel kelas</p>
            </div>

            {{-- Quick Export Buttons --}}
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold text-slate-400 mr-1 uppercase tracking-wider">Ekspor:</span>
                <a href="{{ route('admin.laporan.export', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-semibold text-xs shadow-2xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    CSV
                </a>
                <a href="{{ route('admin.laporan.export-excel', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-teal-50 text-teal-700 border border-teal-200 hover:bg-teal-100 font-semibold text-xs shadow-2xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel (.xls)
                </a>
                <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 font-semibold text-xs shadow-2xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 pt-5 items-end">
            <div class="md:col-span-5">
                <label for="prodi_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    Program Studi
                </label>
                <select name="prodi_id" id="prodi_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Semua Program Studi</option>
                    @foreach ($prodis as $prodi)
                        <option value="{{ $prodi->id }}" @selected(request('prodi_id') == $prodi->id)>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-5">
                <label for="kelas_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    Kelas Perkuliahan
                </label>
                <select name="kelas_id" id="kelas_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>
                            {{ $k->mataKuliah?->nama ?? 'MK' }} - {{ $k->nama }} ({{ $k->semester }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-xs shadow-blue-500/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['prodi_id', 'kelas_id']))
                    <a href="{{ route('admin.laporan.index') }}" class="p-2.5 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-100 transition" title="Reset filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table Card --}}
    <div class="rounded-2xl bg-white shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Daftar Transkrip Nilai</h3>
                <p class="text-xs text-slate-500 mt-0.5">Menampilkan total {{ $nilais->total() }} record data nilai</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200/80 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Mahasiswa</th>
                        <th class="px-6 py-3.5">Program Studi</th>
                        <th class="px-6 py-3.5">Mata Kuliah & Kelas</th>
                        <th class="px-6 py-3.5 text-center">Nilai Akhir</th>
                        <th class="px-6 py-3.5 text-center">Grade</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($nilais as $nilai)
                        @php
                            $gradeBadge = match($nilai->grade) {
                                'A' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'B' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'C' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'D' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'E' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                            $isLulus = strtoupper($nilai->status) === 'LULUS';
                        @endphp
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $nilai->mahasiswa?->nama ?? $nilai->mahasiswa?->user?->name ?? '-' }}
                                </div>
                                <div class="font-mono text-xs text-slate-500 mt-0.5">
                                    NIM: {{ $nilai->mahasiswa?->nim ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $nilai->mahasiswa?->prodi?->nama_prodi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $nilai->kelas?->mataKuliah?->nama ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <span>Kelas: <strong class="text-slate-700">{{ $nilai->kelas?->nama ?? '-' }}</strong></span>
                                    <span>&bull;</span>
                                    <span>{{ $nilai->kelas?->semester }} {{ $nilai->kelas?->tahun_akademik }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-base font-extrabold text-slate-900 font-mono">
                                    {{ number_format($nilai->nilai_akhir, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold border {{ $gradeBadge }}">
                                    {{ $nilai->grade }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full {{ $isLulus ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isLulus ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $nilai->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm font-medium text-slate-600">Tidak ada data nilai yang sesuai kriteria filter.</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Silakan sesuaikan pilihan Program Studi atau Kelas di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($nilais->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $nilais->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
