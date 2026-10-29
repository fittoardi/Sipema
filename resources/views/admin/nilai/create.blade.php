@extends('layouts.admin')

@section('title', 'Tambah Nilai')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Tambah Nilai</h1>
    <a href="{{ route('admin.nilai.index') }}" class="btn btn-outline">Kembali</a>
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
    <form action="{{ route('admin.nilai.store') }}" method="POST" class="p-4 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Kelas</label>
            <select name="kelas_id" class="w-full border border-gray-300 p-2 rounded" required>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->mataKuliah->nama ?? '' }} - {{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Mahasiswa</label>
            <select name="mahasiswa_id" class="w-full border border-gray-300 p-2 rounded" required>
                @foreach($mahasiswas as $mhs)
                    <option value="{{ $mhs->id }}">{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kuis (0-100)</label>
            <input type="number" step="0.01" min="0" max="100" name="kuis" value="{{ old('kuis', 0) }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Tugas (0-100)</label>
            <input type="number" step="0.01" min="0" max="100" name="tugas" value="{{ old('tugas', 0) }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">UTS (0-100)</label>
            <input type="number" step="0.01" min="0" max="100" name="uts" value="{{ old('uts', 0) }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">UAS (0-100)</label>
            <input type="number" step="0.01" min="0" max="100" name="uas" value="{{ old('uas', 0) }}" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection