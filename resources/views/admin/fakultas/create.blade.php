@extends('layouts.admin')

@section('title', 'Tambah Fakultas')
@section('header', 'Kategori Fakultas')
@section('window_file', 'fakultas_create.sys')

@section('content')
<div class="space-y-4 max-w-xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Tambah Fakultas Baru</h1>
            <p class="text-xs text-olive-char mt-0.5">Daftarkan fakultas baru dalam struktur organisasi akademik.</p>
        </div>
        <a href="{{ route('admin.fakultas.index') }}" class="btn-mist">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <form action="{{ route('admin.fakultas.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="kode" class="posthog-label">Kode Fakultas <span class="text-flame-orange">*</span></label>
                <input type="text" name="kode" id="kode" required value="{{ old('kode') }}"
                       placeholder="Contoh: FT, FTI, FEB" class="posthog-input font-mono uppercase">
                @error('kode')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nama" class="posthog-label">Nama Fakultas <span class="text-flame-orange">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama') }}"
                       placeholder="Contoh: Fakultas Teknologi Informasi" class="posthog-input">
                @error('nama')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="dekan" class="posthog-label">Nama Dekan / Pimpinan Fakultas</label>
                <input type="text" name="dekan" id="dekan" value="{{ old('dekan') }}"
                       placeholder="Contoh: Prof. Dr. Ir. Budi Santoso, M.Kom." class="posthog-input">
                @error('dekan')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-warm-mist flex items-center justify-end gap-2">
                <a href="{{ route('admin.fakultas.index') }}" class="btn-mist">Batal</a>
                <button type="submit" class="btn-amber">Simpan Fakultas</button>
            </div>
        </form>
    </div>
</div>
@endsection

