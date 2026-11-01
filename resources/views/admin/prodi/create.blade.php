@extends('layouts.admin')

@section('title', 'Tambah Program Studi')
@section('header', 'Program Studi')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Tambah Program Studi</h1>
            <p class="text-xs text-slate-500 mt-0.5">Daftarkan program studi baru ke dalam master data</p>
        </div>
        <a href="{{ route('admin.prodi.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-xs border border-slate-200/80">
        <form action="{{ route('admin.prodi.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="kode_prodi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kode Program Studi <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="kode_prodi"
                       id="kode_prodi"
                       required
                       value="{{ old('kode_prodi') }}"
                       placeholder="Contoh: TI, SI, IF"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                @error('kode_prodi')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nama_prodi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Program Studi <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="nama_prodi"
                       id="nama_prodi"
                       required
                       value="{{ old('nama_prodi') }}"
                       placeholder="Contoh: Teknik Informatika"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                @error('nama_prodi')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.prodi.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs shadow-blue-500/20 transition">
                    Simpan Program Studi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
