@extends('layouts.admin')

@section('title', 'Ubah Dosen')
@section('header', 'Dosen Pengajar')
@section('window_file', 'dosen_edit.sys')

@section('content')
<div class="space-y-4 max-w-xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Ubah Data Dosen</h1>
            <p class="text-xs text-olive-char mt-0.5">Perbarui profil dosen atau program studi homebase.</p>
        </div>
        <a href="{{ route('admin.dosen.index') }}" class="btn-mist">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <form action="{{ route('admin.dosen.update', $dosen) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="user_id" class="posthog-label">Akun Pengguna (User Dosen) <span class="text-flame-orange">*</span></label>
                <select name="user_id" id="user_id" required class="posthog-select">
                    <option value="">Pilih Akun Pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected($user->id == old('user_id', $dosen->user_id))>
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
                    <label for="nip" class="posthog-label">NIP <span class="text-flame-orange">*</span></label>
                    <input type="text" name="nip" id="nip" required value="{{ old('nip', $dosen->nip) }}"
                           class="posthog-input font-mono">
                    @error('nip')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nidn" class="posthog-label">NIDN <span class="text-flame-orange">*</span></label>
                    <input type="text" name="nidn" id="nidn" required value="{{ old('nidn', $dosen->nidn) }}"
                           class="posthog-input font-mono">
                    @error('nidn')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="nama" class="posthog-label">Nama Lengkap beserta Gelar <span class="text-flame-orange">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama', $dosen->nama) }}"
                       class="posthog-input">
                @error('nama')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="posthog-label">Alamat Email <span class="text-flame-orange">*</span></label>
                <input type="email" name="email" id="email" required value="{{ old('email', $dosen->email) }}"
                       class="posthog-input">
                @error('email')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="prodi_id" class="posthog-label">Homebase Program Studi <span class="text-flame-orange">*</span></label>
                <select name="prodi_id" id="prodi_id" required class="posthog-select">
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" @selected($prodi->id == old('prodi_id', $dosen->prodi_id))>
                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-warm-mist flex items-center justify-end gap-2">
                <a href="{{ route('admin.dosen.index') }}" class="btn-mist">Batal</a>
                <button type="submit" class="btn-amber">Perbarui Dosen</button>
            </div>
        </form>
    </div>
</div>
@endsection
