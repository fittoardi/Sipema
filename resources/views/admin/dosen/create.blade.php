@extends('layouts.admin')

@section('title', 'Tambah Dosen')
@section('header', 'Dosen Pengajar')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Tambah Dosen Pengajar</h1>
            <p class="text-xs text-slate-500 mt-0.5">Daftarkan data dosen dan tetapkan akun pengguna serta homebase</p>
        </div>
        <a href="{{ route('admin.dosen.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-xs border border-slate-200/80">
        <form action="{{ route('admin.dosen.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="user_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Akun Pengguna (User Akun Dosen) <span class="text-rose-500">*</span>
                </label>
                <select name="user_id"
                        id="user_id"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                    <option value="">Pilih Akun Pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        NIP <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="nip"
                           id="nip"
                           required
                           value="{{ old('nip') }}"
                           placeholder="Contoh: 198001012010121001"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono">
                    @error('nip')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nidn" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        NIDN <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="nidn"
                           id="nidn"
                           required
                           value="{{ old('nidn') }}"
                           placeholder="Contoh: 0012345678"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono">
                    @error('nidn')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Lengkap beserta Gelar <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="nama"
                       id="nama"
                       required
                       value="{{ old('nama') }}"
                       placeholder="Contoh: Dr. Budi Santoso, M.Kom."
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                @error('nama')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       required
                       value="{{ old('email') }}"
                       placeholder="Contoh: budi.santoso@kampus.ac.id"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                @error('email')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="prodi_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Program Studi (Homebase) <span class="text-rose-500">*</span>
                </label>
                <select name="prodi_id"
                        id="prodi_id"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" @selected(old('prodi_id') == $prodi->id)>
                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.dosen.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs shadow-blue-500/20 transition">
                    Simpan Dosen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
