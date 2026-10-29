@extends('layouts.admin')

@section('title', 'Tambah Program Studi')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Tambah Program Studi</h1>
    <a href="{{ route('admin.prodi.index') }}" class="btn btn-outline">Kembali</a>
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
    <form action="{{ route('admin.prodi.store') }}" method="POST" class="p-4 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Kode Prodi</label>
            <input type="text" name="kode_prodi" required value="{{ old('kode_prodi') }}"
                class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nama Program Studi</label>
            <input type="text" name="nama_prodi" required value="{{ old('nama_prodi') }}"
                class="w-full border border-gray-300 p-2 rounded">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection