@extends('layouts.admin')

@section('title', 'Ubah Mata Kuliah')
@section('header', 'Mata Kuliah')
@section('window_file', 'matakuliah_edit.sys')

@section('content')
<div class="space-y-4 max-w-xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Ubah Mata Kuliah</h1>
            <p class="text-xs text-olive-char mt-0.5">Perbarui rincian kode, nama, atau bobot SKS.</p>
        </div>
        <a href="{{ route('admin.matakuliah.index') }}" class="btn-mist">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <form action="{{ route('admin.matakuliah.update', $matakuliah) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="prodi_id" class="posthog-label">Program Studi Pengampu <span class="text-flame-orange">*</span></label>
                <select name="prodi_id" id="prodi_id" required class="posthog-select">
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" @selected($prodi->id == old('prodi_id', $matakuliah->prodi_id))>
                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="md:col-span-2">
                    <label for="kode" class="posthog-label">Kode Mata Kuliah <span class="text-flame-orange">*</span></label>
                    <input type="text" name="kode" id="kode" required value="{{ old('kode', $matakuliah->kode) }}"
                           class="posthog-input font-mono">
                    @error('kode')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sks" class="posthog-label">Beban SKS <span class="text-flame-orange">*</span></label>
                    <input type="number" name="sks" id="sks" required min="1" max="12"
                           value="{{ old('sks', $matakuliah->sks) }}" class="posthog-input font-mono text-center">
                    @error('sks')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="nama" class="posthog-label">Nama Mata Kuliah <span class="text-flame-orange">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama', $matakuliah->nama) }}"
                       class="posthog-input">
                @error('nama')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-warm-mist flex items-center justify-end gap-2">
                <a href="{{ route('admin.matakuliah.index') }}" class="btn-mist">Batal</a>
                <button type="submit" class="btn-amber">Perbarui Mata Kuliah</button>
            </div>
        </form>
    </div>
</div>
@endsection
