@extends('layouts.admin')

@section('title', 'Tambah Program Studi')
@section('header', 'Program Studi')
@section('window_file', 'prodi_create.sys')

@section('content')
<div class="space-y-4 max-w-xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Tambah Program Studi</h1>
            <p class="text-xs text-olive-char mt-0.5">Daftarkan data jurusan akademik baru.</p>
        </div>
        <a href="{{ route('admin.prodi.index') }}" class="btn-mist">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <form action="{{ route('admin.prodi.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="kode_prodi" class="posthog-label">Kode Program Studi <span class="text-flame-orange">*</span></label>
                <input type="text" name="kode_prodi" id="kode_prodi" required value="{{ old('kode_prodi') }}"
                       placeholder="Contoh: TI, SI, IF" class="posthog-input font-mono">
                @error('kode_prodi')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nama_prodi" class="posthog-label">Nama Program Studi <span class="text-flame-orange">*</span></label>
                <input type="text" name="nama_prodi" id="nama_prodi" required value="{{ old('nama_prodi') }}"
                       placeholder="Contoh: Teknik Informatika" class="posthog-input">
                @error('nama_prodi')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-warm-mist flex items-center justify-end gap-2">
                <a href="{{ route('admin.prodi.index') }}" class="btn-mist">Batal</a>
                <button type="submit" class="btn-amber">Simpan Program Studi</button>
            </div>
        </form>
    </div>
</div>
@endsection
