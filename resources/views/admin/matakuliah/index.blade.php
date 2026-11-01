@extends('layouts.admin')

@section('title', 'Mata Kuliah')
@section('header', 'Mata Kuliah')
@section('window_file', 'matakuliah.sys')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Daftar Mata Kuliah</h1>
            <p class="text-xs text-olive-char mt-0.5">Kelola kurikulum pengajaran, beban SKS, dan prodi pengampu.</p>
        </div>
        <a href="{{ route('admin.matakuliah.create') }}" class="btn-amber">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Mata Kuliah
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <span class="font-bold text-deep-moss">Daftar Mata Kuliah</span>
            <span class="font-mono text-sage-gray">{{ $matakuliahs->count() }} mata kuliah</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Kode MK</th>
                        <th class="px-4 py-2.5">Nama Mata Kuliah</th>
                        <th class="px-4 py-2.5 text-center">Beban SKS</th>
                        <th class="px-4 py-2.5">Program Studi</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse($matakuliahs as $mk)
                        <tr class="hover:bg-cream-paper transition">
                            <td class="px-4 py-3 font-mono font-bold text-signal-blue">
                                {{ $mk->kode }}
                            </td>
                            <td class="px-4 py-3 font-bold text-deep-moss">
                                {{ $mk->nama }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="tag-pill tag-pill-stone font-mono font-bold">
                                    {{ $mk->sks }} SKS
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="tag-pill tag-pill-stone text-[10px]">
                                    {{ $mk->prodi->nama_prodi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.matakuliah.edit', $mk) }}" class="btn-mist">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.matakuliah.destroy', $mk) }}" method="POST" class="inline" onsubmit="return confirm('Hapus mata kuliah ini?')">
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
                                Belum ada mata kuliah yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
