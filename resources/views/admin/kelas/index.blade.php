@extends('layouts.admin')

@section('title', 'Rombel Kelas')
@section('header', 'Rombel Kelas')
@section('window_file', 'kelas.sys')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Daftar Rombongan Belajar (Kelas)</h1>
            <p class="text-xs text-olive-char mt-0.5">Kelola kelas perkuliahan, penugasan dosen pengampu, dan periode semester.</p>
        </div>
        <a href="{{ route('admin.kelas.create') }}" class="btn-amber">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kelas
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <span class="font-bold text-deep-moss">Daftar Rombel Kelas</span>
            <span class="font-mono text-sage-gray">{{ $kelas->count() }} kelas aktif</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Mata Kuliah</th>
                        <th class="px-4 py-2.5">Rombel Kelas</th>
                        <th class="px-4 py-2.5">Dosen Pengampu</th>
                        <th class="px-4 py-2.5 text-center">Semester & TA</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse($kelas as $k)
                        <tr class="hover:bg-cream-paper transition">
                            <td class="px-4 py-3">
                                <div class="font-bold text-deep-moss">{{ $k->mataKuliah?->nama ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-sage-gray">{{ $k->mataKuliah?->kode ?? '-' }} ({{ $k->mataKuliah?->sks ?? 0 }} SKS)</div>
                            </td>
                            <td class="px-4 py-3 font-mono font-bold text-signal-blue">
                                {{ $k->nama }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-deep-moss">{{ $k->dosen?->nama ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-sage-gray">NIP: {{ $k->dosen?->nip ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="tag-pill {{ $k->semester === 'Ganjil' ? 'tag-pill-blue' : 'tag-pill-green' }} text-[10px]">
                                    {{ $k->semester }}
                                </span>
                                <div class="text-[11px] font-mono text-sage-gray mt-0.5">{{ $k->tahun_akademik }}</div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.kelas.edit', $k) }}" class="btn-mist">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rombel kelas ini?')">
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
                            <td colspan="5" class="px-4 py-8 text-center text-xs text-sage-gray">
                                Belum ada kelas perkuliahan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
