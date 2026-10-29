@extends('layouts.admin')

@section('title', 'Daftar Mahasiswa')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Daftar Mahasiswa</h1>
    <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">Tambah Mahasiswa</a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left p-4">NIM</th>
                <th class="text-left p-4">Nama</th>
                <th class="text-left p-4">Email</th>
                <th class="text-left p-4">Prodi</th>
                <th class="text-left p-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswas as $mhs)
            <tr class="border-b">
                <td class="p-4">{{ $mhs->nim }}</td>
                <td class="p-4">{{ $mhs->nama }}</td>
                <td class="p-4">{{ $mhs->email }}</td>
                <td class="p-4">{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                <td class="p-4">
                    <a href="{{ route('admin.mahasiswa.edit', $mhs) }}" class="btn btn-sm btn-outline">Edit</a>
                    <form action="{{ route('admin.mahasiswa.destroy', $mhs) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
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