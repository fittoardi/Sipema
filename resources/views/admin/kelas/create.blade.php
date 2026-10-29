@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Tambah Kelas</h1>
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline">Kembali</a>
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
    <form action="{{ route('admin.kelas.store') }}" method="POST" class="p-4 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Mata Kuliah</label>
            <select name="mata_kuliah_id" class="w-full border border-gray-300 p-2 rounded" required>
                @foreach($mataKuliahs as $mk)
                    <option value="{{ $mk->id }}">{{ $mk->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Dosen</label>
            <select name="dosen_id" class="w-full border border-gray-300 p-2 rounded" required>
                @foreach($dosens as $dosen)
                    <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nama Kelas</label>
            <input type="text" name="nama" required value="{{ old('nama') }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Semester</label>
            <select name="semester" class="w-full border border-gray-300 p-2 rounded" required>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Tahun Akademik</label>
            <input type="text" name="tahun_akademik" required value="{{ old('tahun_akademik', '2026/2027') }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection