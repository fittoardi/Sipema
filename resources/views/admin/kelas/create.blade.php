@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('header', 'Rombel Kelas')
@section('window_file', 'kelas_create.sys')

@section('content')
<div class="space-y-4 max-w-xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Tambah Rombel Kelas</h1>
            <p class="text-xs text-olive-char mt-0.5">Buka rombongan belajar baru dan tugaskan dosen pengampu.</p>
        </div>
        <a href="{{ route('admin.kelas.index') }}" class="btn-mist">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="mata_kuliah_id" class="posthog-label">Mata Kuliah <span class="text-flame-orange">*</span></label>
                <select name="mata_kuliah_id" id="mata_kuliah_id" required class="posthog-select">
                    <option value="">Pilih Mata Kuliah</option>
                    @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" @selected(old('mata_kuliah_id') == $mk->id)>
                            {{ $mk->kode }} - {{ $mk->nama }} ({{ $mk->sks }} SKS)
                        </option>
                    @endforeach
                </select>
                @error('mata_kuliah_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="dosen_id" class="posthog-label">Dosen Pengampu <span class="text-flame-orange">*</span></label>
                <select name="dosen_id" id="dosen_id" required class="posthog-select">
                    <option value="">Pilih Dosen Pengampu</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" @selected(old('dosen_id') == $dosen->id)>
                            {{ $dosen->nama }} (NIP: {{ $dosen->nip }})
                        </option>
                    @endforeach
                </select>
                @error('dosen_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nama" class="posthog-label">Nama / Label Kelas <span class="text-flame-orange">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama') }}"
                       placeholder="Contoh: TI-3A, IF-01, Reguler A" class="posthog-input font-mono">
                @error('nama')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="semester" class="posthog-label">Semester <span class="text-flame-orange">*</span></label>
                    <select name="semester" id="semester" required class="posthog-select">
                        <option value="Ganjil" @selected(old('semester') === 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected(old('semester') === 'Genap')>Genap</option>
                    </select>
                    @error('semester')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tahun_akademik" class="posthog-label">Tahun Akademik <span class="text-flame-orange">*</span></label>
                    <input type="text" name="tahun_akademik" id="tahun_akademik" required
                           value="{{ old('tahun_akademik', date('Y').'/'.(date('Y')+1)) }}"
                           class="posthog-input font-mono">
                    @error('tahun_akademik')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-3 border-t border-warm-mist flex items-center justify-end gap-2">
                <a href="{{ route('admin.kelas.index') }}" class="btn-mist">Batal</a>
                <button type="submit" class="btn-amber">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>
@endsection
