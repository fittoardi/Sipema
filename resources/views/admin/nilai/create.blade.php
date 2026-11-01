@extends('layouts.admin')

@section('title', 'Tambah Nilai')
@section('header', 'Penilaian Akademik')
@section('window_file', 'nilai_create.sys')

@section('content')
<div class="space-y-4 max-w-xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Tambah Penilaian Mahasiswa</h1>
            <p class="text-xs text-olive-char mt-0.5">Input nilai evaluasi (Kuis, Tugas, UTS, UAS). Nilai akhir dihitung otomatis di server.</p>
        </div>
        <a href="{{ route('admin.nilai.index') }}" class="btn-mist">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <form action="{{ route('admin.nilai.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="kelas_id" class="posthog-label">Kelas Perkuliahan <span class="text-flame-orange">*</span></label>
                <select name="kelas_id" id="kelas_id" required class="posthog-select">
                    <option value="">Pilih Rombel Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" @selected(old('kelas_id') == $k->id)>
                            {{ $k->mataKuliah?->nama ?? 'MK' }} - Kelas {{ $k->nama }} ({{ $k->semester }} {{ $k->tahun_akademik }})
                        </option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="mahasiswa_id" class="posthog-label">Mahasiswa <span class="text-flame-orange">*</span></label>
                <select name="mahasiswa_id" id="mahasiswa_id" required class="posthog-select">
                    <option value="">Pilih Mahasiswa</option>
                    @foreach($mahasiswas as $mhs)
                        <option value="{{ $mhs->id }}" @selected(old('mahasiswa_id') == $mhs->id)>
                            {{ $mhs->nim }} - {{ $mhs->nama }}
                        </option>
                    @endforeach
                </select>
                @error('mahasiswa_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div>
                    <label for="kuis" class="posthog-label">Kuis</label>
                    <input type="number" step="0.01" min="0" max="100" name="kuis" id="kuis"
                           value="{{ old('kuis', 0) }}" class="posthog-input font-mono text-center">
                    @error('kuis')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tugas" class="posthog-label">Tugas</label>
                    <input type="number" step="0.01" min="0" max="100" name="tugas" id="tugas"
                           value="{{ old('tugas', 0) }}" class="posthog-input font-mono text-center">
                    @error('tugas')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="uts" class="posthog-label">UTS</label>
                    <input type="number" step="0.01" min="0" max="100" name="uts" id="uts"
                           value="{{ old('uts', 0) }}" class="posthog-input font-mono text-center">
                    @error('uts')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="uas" class="posthog-label">UAS</label>
                    <input type="number" step="0.01" min="0" max="100" name="uas" id="uas"
                           value="{{ old('uas', 0) }}" class="posthog-input font-mono text-center">
                    @error('uas')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="p-3 bg-cream-paper border border-warm-mist rounded-[4px] text-[11px] text-sage-gray">
                Kalkulasi nilai akhir, grade huruf mutu, dan kelulusan akan dihitung otomatis di server berdasarkan konfigurasi formula yang aktif.
            </div>

            <div class="pt-3 border-t border-warm-mist flex items-center justify-end gap-2">
                <a href="{{ route('admin.nilai.index') }}" class="btn-mist">Batal</a>
                <button type="submit" class="btn-amber">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>
@endsection
