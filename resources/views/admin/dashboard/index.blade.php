@extends('layouts.admin')

@section('title', 'Dashboard Utama')
@section('header', 'Dashboard Utama')

@section('content')
<div class="space-y-8">
    {{-- Header Banner / Welcome --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 p-8 text-white shadow-md">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold uppercase tracking-wider mb-3 backdrop-blur-sm border border-blue-400/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Tahun Akademik Aktif
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                Selamat Datang di SIPEMA Admin
            </h1>
            <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                Kelola data akademik, pantau distribusi penilaian mahasiswa, dan konfigurasi master data universitas secara terpusat dan akurat.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-80 h-80 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
    </div>

    {{-- KPI Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Mahasiswa --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Mahasiswa</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalMahasiswa) }}</p>
                <span class="text-xs font-medium text-slate-500">terdaftar</span>
            </div>
            <div class="mt-3 text-xs text-slate-500 flex items-center gap-1">
                <a href="{{ route('admin.mahasiswa.index') }}" class="font-medium text-blue-600 hover:text-blue-700">Lihat data &rarr;</a>
            </div>
        </div>

        {{-- Total Dosen --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dosen Pengajar</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalDosen) }}</p>
                <span class="text-xs font-medium text-slate-500">dosen aktif</span>
            </div>
            <div class="mt-3 text-xs text-slate-500 flex items-center gap-1">
                <a href="{{ route('admin.dosen.index') }}" class="font-medium text-indigo-600 hover:text-indigo-700">Lihat data &rarr;</a>
            </div>
        </div>

        {{-- Program Studi --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Program Studi</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $prodis->count() }}</p>
                <span class="text-xs font-medium text-slate-500">fakultas/jurusan</span>
            </div>
            <div class="mt-3 text-xs text-slate-500 flex items-center gap-1">
                <a href="{{ route('admin.prodi.index') }}" class="font-medium text-emerald-600 hover:text-emerald-700">Kelola prodi &rarr;</a>
            </div>
        </div>

        {{-- Nilai Terinput --}}
        @php
            $totalNilai = $distribusiGrade->sum('jumlah');
        @endphp
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Terinput</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalNilai) }}</p>
                <span class="text-xs font-medium text-slate-500">record nilai</span>
            </div>
            <div class="mt-3 text-xs text-slate-500 flex items-center gap-1">
                <a href="{{ route('admin.nilai.index') }}" class="font-medium text-amber-600 hover:text-amber-700">Daftar nilai &rarr;</a>
            </div>
        </div>
    </div>

    {{-- Main Grid: Distribusi Grade & Statistik Semester --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Distribusi Grade Mahasiswa --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Distribusi Grade Mahasiswa</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Sebaran perolehan huruf mutu dan rata-rata skor akhir</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600">
                        {{ $totalNilai }} Total
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse ($distribusiGrade as $item)
                        @php
                            $percentage = $totalNilai > 0 ? round(($item->jumlah / $totalNilai) * 100, 1) : 0;
                            $badgeColor = match($item->grade) {
                                'A' => 'bg-emerald-500',
                                'B' => 'bg-blue-500',
                                'C' => 'bg-amber-500',
                                'D' => 'bg-orange-500',
                                'E' => 'bg-rose-500',
                                default => 'bg-slate-400',
                            };
                            $badgeText = match($item->grade) {
                                'A' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                'B' => 'text-blue-700 bg-blue-50 border-blue-200',
                                'C' => 'text-amber-700 bg-amber-50 border-amber-200',
                                'D' => 'text-orange-700 bg-orange-50 border-orange-200',
                                'E' => 'text-rose-700 bg-rose-50 border-rose-200',
                                default => 'text-slate-700 bg-slate-50 border-slate-200',
                            };
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg font-bold text-xs border {{ $badgeText }}">
                                        {{ $item->grade }}
                                    </span>
                                    <span class="font-semibold text-slate-700">{{ $item->jumlah }} Mahasiswa</span>
                                    <span class="text-xs text-slate-400">({{ $percentage }}%)</span>
                                </div>
                                <div class="text-xs text-slate-500">
                                    Rata-rata: <span class="font-semibold text-slate-700">{{ $item->rata_rata }}</span>
                                </div>
                            </div>
                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $badgeColor }} rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-slate-400">
                            Belum ada data penilaian yang tercatat.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Perhitungan kumulatif seluruh program studi</span>
                <a href="{{ route('admin.laporan.index') }}" class="font-semibold text-blue-600 hover:text-blue-700">Lihat Laporan Lengkap &rarr;</a>
            </div>
        </div>

        {{-- Rekapitulasi per Semester --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Aktivitas Penilaian per Semester</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Volume penilaian berdasarkan kurikulum dan periode akademik</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200/80">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 border-b border-slate-200/80 text-xs font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Tahun Akademik</th>
                                <th class="px-4 py-3">Semester</th>
                                <th class="px-4 py-3 text-right">Volume Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($grafikNorm as $item)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $item->tahun }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $item->semester === 'Ganjil' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                            {{ $item->semester }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-700">
                                        {{ number_format($item->jumlah) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-sm text-slate-400">
                                        Belum ada rekaman nilai per semester.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Dihitung berdasarkan kelas aktif dan nilai final</span>
                <span class="font-medium text-slate-600">{{ $grafikNorm->count() }} Periode</span>
            </div>
        </div>
    </div>

    {{-- Bottom Grid: Mahasiswa per Prodi & Riwayat Semester --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Program Studi Distribution --}}
        <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Distribusi Mahasiswa per Prodi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Jumlah mahasiswa aktif di tiap program studi</p>
                </div>
                <a href="{{ route('admin.prodi.create') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700">
                    + Tambah Prodi
                </a>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200/80">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200/80 text-xs font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Program Studi</th>
                            <th class="px-4 py-3 text-right">Mahasiswa Terdaftar</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($prodis as $prodi)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs font-bold text-blue-600">{{ $prodi->kode_prodi }}</td>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $prodi->nama_prodi }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="font-bold text-slate-900">{{ $prodi->mahasiswas_count }}</span>
                                    <span class="text-xs text-slate-400">mhs</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.prodi.edit', $prodi) }}" class="text-xs font-medium text-slate-600 hover:text-blue-600 transition">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-400">
                                    Belum ada data program studi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 6 Semester Terakhir --}}
        <div class="rounded-2xl bg-white p-6 shadow-xs border border-slate-200/80">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Semester Terakhir</h3>
                    <p class="text-xs text-slate-500 mt-0.5">6 semester terbaru tercatat</p>
                </div>
            </div>

            <div class="space-y-2.5">
                @forelse ($lastSemester as $item)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                {{ substr($item->semester, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $item->tahun }}</p>
                                <p class="text-[11px] text-slate-500">Semester {{ $item->semester }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-white text-slate-700 border border-slate-200 shadow-2xs">
                            Aktif
                        </span>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-slate-400">
                        Belum ada riwayat semester.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
