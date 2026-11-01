@extends('layouts.admin')

@section('title', 'Penilaian Akademik')
@section('header', 'Penilaian Akademik')
@section('window_file', 'nilai.sys')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Data Penilaian Akademik</h1>
            <p class="text-xs text-olive-char mt-0.5">Kelola data penilaian mahasiswa (Kuis, Tugas, UTS, UAS, dan kalkulasi akhir).</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pengaturan-nilai.index') }}" class="btn-mist">
                Formula Bobot
            </a>
            <a href="{{ route('admin.nilai.create') }}" class="btn-amber">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Nilai
            </a>
        </div>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <span class="font-bold text-deep-moss">Daftar Nilai Mahasiswa</span>
            <span class="font-mono text-sage-gray">{{ $nilais->count() }} entri</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Mahasiswa</th>
                        <th class="px-4 py-2.5">Mata Kuliah & Kelas</th>
                        <th class="px-3 py-2.5 text-center">Kuis</th>
                        <th class="px-3 py-2.5 text-center">Tugas</th>
                        <th class="px-3 py-2.5 text-center">UTS</th>
                        <th class="px-3 py-2.5 text-center">UAS</th>
                        <th class="px-3 py-2.5 text-center">Akhir</th>
                        <th class="px-3 py-2.5 text-center">Grade</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse($nilais as $n)
                        @php
                            $gradePill = match($n->grade) {
                                'A' => 'tag-pill-green',
                                'B' => 'tag-pill-blue',
                                'C' => 'tag-pill-amber',
                                'D' => 'tag-pill-orange',
                                default => 'tag-pill-stone',
                            };
                        @endphp
                        <tr class="hover:bg-cream-paper transition">
                            <td class="px-4 py-3">
                                <div class="font-bold text-deep-moss">{{ $n->mahasiswa?->nama ?? $n->mahasiswa?->user?->name ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-sage-gray">NIM: {{ $n->mahasiswa?->nim ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-deep-moss">{{ $n->kelas?->mataKuliah?->nama ?? '-' }}</div>
                                <div class="text-[11px] text-sage-gray">
                                    Kelas <strong>{{ $n->kelas?->nama ?? '-' }}</strong> &bull; {{ $n->kelas?->semester }}
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center font-mono text-sage-gray">{{ $n->kuis ?? '-' }}</td>
                            <td class="px-3 py-3 text-center font-mono text-sage-gray">{{ $n->tugas ?? '-' }}</td>
                            <td class="px-3 py-3 text-center font-mono text-sage-gray">{{ $n->uts ?? '-' }}</td>
                            <td class="px-3 py-3 text-center font-mono text-sage-gray">{{ $n->uas ?? '-' }}</td>
                            <td class="px-3 py-3 text-center font-mono font-bold text-deep-moss text-xs">
                                {{ number_format($n->nilai_akhir ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="tag-pill {{ $gradePill }} font-mono font-bold">
                                    {{ $n->grade ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.nilai.edit', $n) }}" class="btn-mist">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.nilai.destroy', $n) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data nilai ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-mist">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-xs text-sage-gray">
                                Belum ada penilaian yang terdata.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
