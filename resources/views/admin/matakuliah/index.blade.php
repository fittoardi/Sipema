@extends('layouts.admin')

@section('title', 'Daftar Mata Kuliah')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Daftar Mata Kuliah</h1>
    <a href="{{ route('admin.matakuliah.create') }}" class="btn btn-primary">Tambah Mata Kuliah</a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left p-4">Kode</th>
                <th class="text-left p-4">Nama</th>
                <th class="text-left p-4">SKS</th>
                <th class="text-left p-4">Prodi</th>
                <th class="text-left p-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matakuliahs as $mk)
            <tr class="border-b">
                <td class="p-4">{{ $mk->kode }}</td>
                <td class="p-4">{{ $mk->nama }}</td>
                <td class="p-4">{{ $mk->sks }}</td>
                <td class="p-4">{{ $mk->prodi->nama_prodi ?? '-' }}</td>
                <td class="p-4">
                    <a href="{{ route('admin.matakuliah.edit', $mk) }}" class="btn btn-sm btn-outline">Edit</a>
                    <form action="{{ route('admin.matakuliah.destroy', $mk) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
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