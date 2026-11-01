@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')
@section('header', 'Data Mahasiswa')
@section('window_file', 'mahasiswa_create.sys')

@section('content')
<div class="space-y-4 max-w-xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Tambah Mahasiswa Baru</h1>
            <p class="text-xs text-olive-char mt-0.5">Daftarkan mahasiswa baru dan tautkan dengan akun login.</p>
        </div>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn-mist">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="user_id" class="posthog-label">Akun Pengguna (User Mahasiswa) <span class="text-flame-orange">*</span></label>
                <select name="user_id" id="user_id" required class="posthog-select">
                    <option value="">Pilih Akun Pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="nim" class="posthog-label">NIM <span class="text-flame-orange">*</span></label>
                    <input type="text" name="nim" id="nim" required value="{{ old('nim') }}"
                           placeholder="Contoh: 23110001" class="posthog-input font-mono">
                    @error('nim')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="angkatan" class="posthog-label">Tahun Angkatan <span class="text-flame-orange">*</span></label>
                    <input type="number" name="angkatan" id="angkatan" required min="2000" max="2099"
                           value="{{ old('angkatan', date('Y')) }}" class="posthog-input font-mono">
                    @error('angkatan')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="nama" class="posthog-label">Nama Lengkap Mahasiswa <span class="text-flame-orange">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama') }}"
                       placeholder="Contoh: Rian Anggoro" class="posthog-input">
                @error('nama')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="prodi_id" class="posthog-label">Program Studi <span class="text-flame-orange">*</span></label>
                <select name="prodi_id" id="prodi_id" required class="posthog-select">
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" @selected(old('prodi_id') == $prodi->id)>
                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-warm-mist flex items-center justify-end gap-2">
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn-mist">Batal</a>
                <button type="submit" class="btn-amber">Simpan Mahasiswa</button>
            </div>
        </form>
    </div>
</div>
@endsection
