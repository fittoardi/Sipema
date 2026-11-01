@extends('layouts.admin')

@section('title', 'Penilaian Akademik')
@section('header', 'Penilaian Akademik')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Data Penilaian Akademik</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data penilaian mahasiswa (kuis, tugas, UTS, UAS, dan grade akhir)</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.laporan.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan & Ekspor
            </a>
            <a href="{{ route('admin.nilai.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs shadow-blue-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Nilai
            </a>
        </div>
    </div>

    <div class="rounded-2xl bg-white shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total {{ $nilais->count() }} Data Nilai</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200/80 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Mahasiswa</th>
                        <th class="px-6 py-3.5">Mata Kuliah & Kelas</th>
                        <th class="px-4 py-3.5 text-center">Kuis</th>
                        <th class="px-4 py-3.5 text-center">Tugas</th>
                        <th class="px-4 py-3.5 text-center">UTS</th>
                        <th class="px-4 py-3.5 text-center">UAS</th>
                        <th class="px-4 py-3.5 text-center">Akhir</th>
                        <th class="px-4 py-3.5 text-center">Grade</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($nilais as $n)
                        @php
                            $gradeBadge = match($n->grade) {
                                'A' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'B' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'C' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'D' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'E' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $n->mahasiswa?->nama ?? $n->mahasiswa?->user?->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400 font-mono">NIM: {{ $n->mahasiswa?->nim ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $n->kelas?->mataKuliah?->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    Kelas <strong class="text-slate-600">{{ $n->kelas?->nama ?? '-' }}</strong> &bull; {{ $n->kelas?->semester }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center font-mono text-xs text-slate-600">{{ $n->kuis ?? '-' }}</td>
                            <td class="px-4 py-4 text-center font-mono text-xs text-slate-600">{{ $n->tugas ?? '-' }}</td>
                            <td class="px-4 py-4 text-center font-mono text-xs text-slate-600">{{ $n->uts ?? '-' }}</td>
                            <td class="px-4 py-4 text-center font-mono text-xs text-slate-600">{{ $n->uas ?? '-' }}</td>
                            <td class="px-4 py-4 text-center font-mono font-extrabold text-slate-900 text-sm">
                                {{ number_format($n->nilai_akhir ?? 0, 2) }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold border {{ $gradeBadge }}">
                                    {{ $n->grade ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.nilai.edit', $n) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.nilai.destroy', $n) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data nilai ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <p class="text-sm font-medium text-slate-600">Belum ada penilaian yang terdata.</p>
                                <p class="text-xs text-slate-400 mt-0.5">Klik tombol Tambah Nilai untuk menambahkan nilai mahasiswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
