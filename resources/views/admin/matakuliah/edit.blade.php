@extends('layouts.admin')

@section('title', 'Ubah Mata Kuliah')
@section('header', 'Mata Kuliah')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Ubah Mata Kuliah</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui rincian kode, nama mata kuliah, atau alokasi SKS</p>
        </div>
        <a href="{{ route('admin.matakuliah.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-xs border border-slate-200/80">
        <form action="{{ route('admin.matakuliah.update', $matakuliah) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="prodi_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Program Studi Pengampu <span class="text-rose-500">*</span>
                </label>
                <select name="prodi_id"
                        id="prodi_id"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" @selected($prodi->id == old('prodi_id', $matakuliah->prodi_id))>
                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="kode" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kode Mata Kuliah <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="kode"
                           id="kode"
                           required
                           value="{{ old('kode', $matakuliah->kode) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono">
                    @error('kode')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sks" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Beban SKS <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="sks"
                           id="sks"
                           required
                           min="1"
                           max="12"
                           value="{{ old('sks', $matakuliah->sks) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono">
                    @error('sks')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Mata Kuliah <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="nama"
                       id="nama"
                       required
                       value="{{ old('nama', $matakuliah->nama) }}"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                @error('nama')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.matakuliah.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs shadow-blue-500/20 transition">
                    Perbarui Mata Kuliah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
