@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Total Mahasiswa</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMahasiswa }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Total Dosen</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalDosen }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Program Studi</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $prodis->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-gray-600 text-sm font-medium">Nilai Terinput</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $distribusiGrade->sum('jumlah') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Distribusi Grade</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Grade</th>
                            <th class="px-4 py-2 text-center">Jumlah</th>
                            <th class="px-4 py-2 text-center">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($distribusiGrade as $item)
                            <tr>
                                <td class="px-4 py-2 font-semibold">{{ $item->grade }}</td>
                                <td class="px-4 py-2 text-center">{{ $item->jumlah }}</td>
                                <td class="px-4 py-2 text-center">{{ $item->rata_rata }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Grafik Nilai ( semester )</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Tahun</th>
                            <th class="px-4 py-2 text-center">Semester</th>
                            <th class="px-4 py-2 text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($grafikNorm as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->tahun }}</td>
                                <td class="px-4 py-2 text-center">{{ $item->semester }}</td>
                                <td class="px-4 py-2 text-center">{{ $item->jumlah }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Nilai Terakhir (6 Semester Terakhir)</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Tahun</th>
                        <th class="px-4 py-2 text-center">Semester</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($lastSemester as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->tahun }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->semester }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
