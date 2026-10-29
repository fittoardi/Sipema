@extends('layouts.admin')

@section('title', 'Program Studi')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Program Studi</h1>
    <a href="{{ route('admin.prodi.create') }}" class="btn btn-primary">+ Tambah</a>
</div>

@if (session('success'))
    <div class="mb-4 alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prodis as $prodi)
                <tr>
                    <td>{{ $prodi->kode_prodi }}</td>
                    <td>{{ $prodi->nama_prodi }}</td>
                    <td class="text-right space-x-2">
                        <a href="{{ route('admin.prodi.edit', $prodi) }}" class="btn btn-sm btn-outline">Edit</a>
                        <form action="{{ route('admin.prodi.destroy', $prodi) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-muted">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
