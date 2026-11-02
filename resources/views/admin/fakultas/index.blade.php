@extends('layouts.admin')

@section('title', 'Kategori Fakultas')
@section('header', 'Kategori Fakultas')
@section('window_file', 'fakultas.sys')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Master Data Fakultas</h1>
            <p class="text-xs text-olive-char mt-0.5">Daftar kategori fakultas yang menaungi program studi di lingkungan kampus.</p>
        </div>
        <a href="{{ route('admin.fakultas.create') }}" class="btn-amber">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Fakultas
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <span class="font-bold text-deep-moss">Daftar Fakultas</span>
            <span class="font-mono text-sage-gray">{{ $fakultas->count() }} entri</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Kode</th>
                        <th class="px-4 py-2.5">Nama Fakultas</th>
                        <th class="px-4 py-2.5">Dekan</th>
                        <th class="px-4 py-2.5 text-center">Jumlah Prodi</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse($fakultas as $item)
                        <tr class="hover:bg-cream-paper transition">
                            <td class="px-4 py-3 font-mono font-bold text-signal-blue">
                                {{ $item->kode }}
                            </td>
                            <td class="px-4 py-3 font-medium text-deep-moss">
                                {{ $item->nama }}
                            </td>
                            <td class="px-4 py-3 text-olive-char">
                                {{ $item->dekan ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-semibold bg-amber-glow/20 text-deep-moss border border-burnished-gold/30">
                                    {{ $item->program_studi_count }} Prodi
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.fakultas.edit', $item) }}" class="btn-mist">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.fakultas.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus fakultas ini?')">
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
                                Belum ada fakultas yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

