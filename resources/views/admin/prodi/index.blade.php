@extends('layouts.admin')

@section('title', 'Program Studi')
@section('header', 'Program Studi')
@section('window_file', 'prodi.sys')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Master Data Program Studi</h1>
            <p class="text-xs text-olive-char mt-0.5">Daftar fakultas dan program studi akademik yang tersedia.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.fakultas.index') }}" class="btn-mist">
                Kelola Fakultas
            </a>
            <a href="{{ route('admin.prodi.create') }}" class="btn-amber">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Program Studi
            </a>
        </div>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <span class="font-bold text-deep-moss">Daftar Program Studi</span>
            <span class="font-mono text-sage-gray">{{ $prodis->count() }} entri</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Kode Prodi</th>
                        <th class="px-4 py-2.5">Nama Program Studi</th>
                        <th class="px-4 py-2.5">Fakultas</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse($prodis as $prodi)
                        <tr class="hover:bg-cream-paper transition">
                            <td class="px-4 py-3 font-mono font-bold text-signal-blue">
                                {{ $prodi->kode_prodi }}
                            </td>
                            <td class="px-4 py-3 font-medium text-deep-moss">
                                {{ $prodi->nama_prodi }}
                            </td>
                            <td class="px-4 py-3 text-olive-char">
                                @if($prodi->fakultas)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-soft-linen text-deep-moss border border-warm-mist">
                                        {{ $prodi->fakultas->nama }} ({{ $prodi->fakultas->kode }})
                                    </span>
                                @else
                                    <span class="text-sage-gray italic">Belum diatur</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.prodi.edit', $prodi) }}" class="btn-mist">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.prodi.destroy', $prodi) }}" method="POST" class="inline" onsubmit="return confirm('Hapus program studi ini?')">
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
                            <td colspan="4" class="px-4 py-8 text-center text-xs text-sage-gray">
                                Belum ada program studi yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
