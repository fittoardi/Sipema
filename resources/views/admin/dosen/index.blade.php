@extends('layouts.admin')

@section('title', 'Daftar Dosen')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Daftar Dosen</h1>
    <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary">Tambah Dosen</a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left p-4">NIP</th>
                <th class="text-left p-4">Nama</th>
                <th class="text-left p-4">Email</th>
                <th class="text-left p-4">Prodi</th>
                <th class="text-left p-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dosens as $dosen)
            <tr class="border-b">
                <td class="p-4">{{ $dosen->nip }}</td>
                <td class="p-4">{{ $dosen->nama }}</td>
                <td class="p-4">{{ $dosen->email }}</td>
                <td class="p-4">{{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                <td class="p-4">
                    <a href="{{ route('admin.dosen.edit', $dosen) }}" class="btn btn-sm btn-outline">Edit</a>
                    <form action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
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