@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Tambah Mata Kuliah</h1>
    <a href="{{ route('admin.matakuliah.index') }}" class="btn btn-outline">Kembali</a>
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
    <form action="{{ route('admin.matakuliah.store') }}" method="POST" class="p-4 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Prodi</label>
            <select name="prodi_id" class="w-full border border-gray-300 p-2 rounded" required>
                @foreach($prodis as $prodi)
                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kode</label>
            <input type="text" name="kode" required value="{{ old('kode') }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="nama" required value="{{ old('nama') }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">SKS</label>
            <input type="number" name="sks" min="1" required value="{{ old('sks', 3) }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection