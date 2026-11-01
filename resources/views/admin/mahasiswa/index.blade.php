@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('header', 'Data Mahasiswa')
@section('window_file', 'mahasiswa.sys')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Daftar Mahasiswa</h1>
            <p class="text-xs text-olive-char mt-0.5">Kelola data mahasiswa aktif, NIM, angkatan, dan program studi.</p>
        </div>
        <a href="{{ route('admin.mahasiswa.create') }}" class="btn-amber">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Mahasiswa
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <span class="font-bold text-deep-moss">Daftar Mahasiswa</span>
            <span class="font-mono text-sage-gray">{{ $mahasiswas->count() }} mahasiswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">NIM</th>
                        <th class="px-4 py-2.5">Nama Mahasiswa</th>
                        <th class="px-4 py-2.5">Program Studi</th>
                        <th class="px-4 py-2.5 text-center">Angkatan</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse($mahasiswas as $mhs)
                        <tr class="hover:bg-cream-paper transition">
                            <td class="px-4 py-3 font-mono font-bold text-deep-moss">
                                {{ $mhs->nim }}
                            </td>
                            <td class="px-4 py-3 font-bold text-deep-moss">
                                <div>{{ $mhs->nama }}</div>
                                <div class="text-[11px] font-mono text-sage-gray font-normal">{{ $mhs->user?->email ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="tag-pill tag-pill-stone text-[10px]">
                                    {{ $mhs->prodi->nama_prodi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center font-mono font-bold">
                                {{ $mhs->angkatan }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.mahasiswa.edit', $mhs) }}" class="btn-mist">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.mahasiswa.destroy', $mhs) }}" method="POST" class="inline" onsubmit="return confirm('Hapus mahasiswa ini?')">
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
                                Belum ada data mahasiswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
