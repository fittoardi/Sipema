@extends('layouts.admin')

@section('title', 'Dosen Pengajar')
@section('header', 'Dosen Pengajar')
@section('window_file', 'dosen.sys')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Daftar Dosen Pengajar</h1>
            <p class="text-xs text-olive-char mt-0.5">Kelola data tenaga pendidik, NIP, NIDN, dan alokasi homebase.</p>
        </div>
        <a href="{{ route('admin.dosen.create') }}" class="btn-amber">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Dosen
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <span class="font-bold text-deep-moss">Daftar Dosen</span>
            <span class="font-mono text-sage-gray">{{ $dosens->count() }} staf pengajar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">NIP & NIDN</th>
                        <th class="px-4 py-2.5">Nama Dosen</th>
                        <th class="px-4 py-2.5">Email</th>
                        <th class="px-4 py-2.5">Homebase Prodi</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse($dosens as $dosen)
                        <tr class="hover:bg-cream-paper transition">
                            <td class="px-4 py-3 font-mono">
                                <div class="font-bold text-deep-moss">{{ $dosen->nip }}</div>
                                <div class="text-[11px] text-sage-gray">NIDN: {{ $dosen->nidn ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 font-bold text-deep-moss">
                                {{ $dosen->nama }}
                            </td>
                            <td class="px-4 py-3 font-mono text-sage-gray">
                                {{ $dosen->email }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="tag-pill tag-pill-stone text-[10px]">
                                    {{ $dosen->prodi->nama_prodi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.dosen.edit', $dosen) }}" class="btn-mist">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data dosen ini?')">
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
                                Belum ada dosen yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
