@extends('layouts.admin')

@section('title', 'Ubah Mahasiswa')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Ubah Mahasiswa</h1>
    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline">Kembali</a>
</div>

@if ($errors->any())
    <div class="mb-4 alert alert-danger">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" method="POST" class="p-4 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">NIM</label>
            <input type="text" name="nim" required value="{{ old('nim', $mahasiswa->nim) }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="nama" required value="{{ old('nama', $mahasiswa->nama) }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" required value="{{ old('email', $mahasiswa->email) }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Prodi</label>
            <select name="prodi_id" class="w-full border border-gray-300 p-2 rounded" required>
                @foreach($prodis as $prodi)
                    <option value="{{ $prodi->id }}" @selected($prodi->id == old('prodi_id', $mahasiswa->prodi_id))>
                        {{ $prodi->nama_prodi }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection