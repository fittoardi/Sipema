@extends('layouts.admin')

@section('title', 'Daftar Kelas')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Daftar Kelas</h1>
    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">Tambah Kelas</a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left p-4">Mata Kuliah</th>
                <th class="text-left p-4">Nama Kelas</th>
                <th class="text-left p-4">Dosen</th>
                <th class="text-left p-4">Semester</th>
                <th class="text-left p-4">Tahun Akademik</th>
                <th class="text-left p-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $k)
            <tr class="border-b">
                <td class="p-4">{{ $k->mataKuliah->nama ?? '-' }}</td>
                <td class="p-4">{{ $k->nama }}</td>
                <td class="p-4">{{ $k->dosen->nama ?? '-' }}</td>
                <td class="p-4">{{ $k->semester }}</td>
                <td class="p-4">{{ $k->tahun_akademik }}</td>
                <td class="p-4">
                    <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-sm btn-outline">Edit</a>
                    <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection