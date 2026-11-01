@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('header', 'Rombel Kelas')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Tambah Rombel Kelas</h1>
            <p class="text-xs text-slate-500 mt-0.5">Buka rombongan belajar baru dan tugaskan dosen pengampu</p>
        </div>
        <a href="{{ route('admin.kelas.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-xs border border-slate-200/80">
        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="mata_kuliah_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Mata Kuliah <span class="text-rose-500">*</span>
                </label>
                <select name="mata_kuliah_id"
                        id="mata_kuliah_id"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                    <option value="">Pilih Mata Kuliah</option>
                    @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" @selected(old('mata_kuliah_id') == $mk->id)>
                            {{ $mk->kode }} - {{ $mk->nama }} ({{ $mk->sks }} SKS)
                        </option>
                    @endforeach
                </select>
                @error('mata_kuliah_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="dosen_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Dosen Pengampu <span class="text-rose-500">*</span>
                </label>
                <select name="dosen_id"
                        id="dosen_id"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                    <option value="">Pilih Dosen Pengampu</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" @selected(old('dosen_id') == $dosen->id)>
                            {{ $dosen->nama }} (NIP: {{ $dosen->nip }})
                        </option>
                    @endforeach
                </select>
                @error('dosen_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama / Label Kelas <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="nama"
                       id="nama"
                       required
                       value="{{ old('nama') }}"
                       placeholder="Contoh: TI-3A, IF-01, Reguler A"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                @error('nama')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="semester" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Semester <span class="text-rose-500">*</span>
                    </label>
                    <select name="semester"
                            id="semester"
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                        <option value="Ganjil" @selected(old('semester') === 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected(old('semester') === 'Genap')>Genap</option>
                    </select>
                    @error('semester')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tahun_akademik" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tahun Akademik <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="tahun_akademik"
                           id="tahun_akademik"
                           required
                           value="{{ old('tahun_akademik', date('Y').'/'.(date('Y')+1)) }}"
                           placeholder="Contoh: 2026/2027"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono">
                    @error('tahun_akademik')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.kelas.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs shadow-blue-500/20 transition">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
