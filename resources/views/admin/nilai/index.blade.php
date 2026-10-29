@extends('layouts.admin')

@section('title', 'Daftar Nilai')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1>Daftar Nilai</h1>
    <a href="{{ route('admin.nilai.create') }}" class="btn btn-primary">Tambah Nilai</a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b">
                <th class="text-left p-2">Mahasiswa</th>
                <th class="text-left p-2">Mata Kuliah</th>
                <th class="text-left p-2">Kelas</th>
                <th class="text-left p-2">Kuis</th>
                <th class="text-left p-2">Tugas</th>
                <th class="text-left p-2">UTS</th>
                <th class="text-left p-2">UAS</th>
                <th class="text-left p-2">Akhir</th>
                <th class="text-left p-2">Grade</th>
                <th class="text-left p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilais as $n)
            <tr class="border-b">
                <td class="p-2">{{ $n->mahasiswa->nama ?? '-' }}</td>
                <td class="p-2">{{ $n->kelas->mataKuliah->nama ?? '-' }}</td>
                <td class="p-2">{{ $n->kelas->nama ?? '-' }}</td>
                <td class="p-2">{{ $n->kuis ?? '-' }}</td>
                <td class="p-2">{{ $n->tugas ?? '-' }}</td>
                <td class="p-2">{{ $n->uts ?? '-' }}</td>
                <td class="p-2">{{ $n->uas ?? '-' }}</td>
                <td class="p-2">{{ number_format($n->nilai_akhir ?? 0, 2) }}</td>
                <td class="p-2">{{ $n->grade ?? '-' }}</td>
                <td class="p-2">
                    <a href="{{ route('admin.nilai.edit', $n) }}" class="btn btn-sm btn-outline">Edit</a>
                    <form action="{{ route('admin.nilai.destroy', $n) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
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