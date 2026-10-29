@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Nilai</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="prodi_id" class="block text-sm font-medium text-gray-700 mb-1">Filter Prodi</label>
                <select name="prodi_id" id="prodi_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Prodi</option>
                    @foreach ($prodis as $prodi)
                        <option value="{{ $prodi->id }}" @if (request('prodi_id') == $prodi->id) selected @endif>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
                <select name="kelas_id" id="kelas_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @if (request('kelas_id') == $k->id) selected @endif>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-medium">
                    Filter
                </button>
                <a href="{{ route('admin.laporan.export', request()->query()) }}" class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700 font-medium text-sm text-center">
                    CSV
                </a>
                <a href="{{ route('admin.laporan.export-excel', request()->query()) }}" class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 font-medium text-sm text-center">
                    Excel
                </a>
                <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" class="bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700 font-medium text-sm text-center">
                    PDF
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">NIM</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama Mahasiswa</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Prodi</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Mata Kuliah</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Kelas</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Nilai Akhir</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Grade</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($nilais as $nilai)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $nilai->mahasiswa?->nim ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $nilai->mahasiswa?->user?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $nilai->mahasiswa?->prodi?->nama_prodi ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $nilai->kelas?->mataKuliah?->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $nilai->kelas?->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $nilai->nilai_akhir }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center font-semibold">{{ $nilai->grade }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full @if ($nilai->status === 'LULUS') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $nilai->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada data nilai</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="bg-white px-6 py-4 border-t">
            {{ $nilais->links() }}
        </div>
    </div>
</div>
@endsection
