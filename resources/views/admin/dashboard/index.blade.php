@extends('layouts.admin')

@section('title', 'Dashboard Utama')
@section('header', 'Overview Akademik')
@section('window_file', 'dashboard.mdx')

@section('content')
<div class="space-y-5">
    {{-- Top Desktop File Note / Hero Banner --}}
    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="tag-pill tag-pill-amber text-[10px] font-mono font-bold">STATUS: AKTIF</span>
                    <span class="text-xs font-mono text-sage-gray">Tahun Akademik Berjalan</span>
                </div>
                <h1 class="text-lg font-bold text-deep-moss">Sistem Pengelolaan Nilai Mahasiswa (SIPENMA)</h1>
                <p class="text-xs text-olive-char mt-0.5">Workspace terpusat untuk monitoring data akademik, rekapitulasi nilai, dan laporan institusi.</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('admin.pengaturan-nilai.index') }}" class="btn-mist">
                    <svg class="w-3.5 h-3.5 text-olive-char" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Formula Nilai
                </a>
                <a href="{{ route('admin.nilai.create') }}" class="btn-amber">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Input Nilai
                </a>
            </div>
        </div>
    </div>

    {{-- 4 Stat Metric Cards (Paper White with Hairline Borders) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
            <div class="flex items-center justify-between text-xs text-sage-gray font-medium">
                <span>Total Mahasiswa</span>
                <span class="font-mono text-[10px] text-olive-char">REG</span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold font-mono text-deep-moss">{{ number_format($totalMahasiswa) }}</span>
                <a href="{{ route('admin.mahasiswa.index') }}" class="text-xs text-signal-blue hover:underline">Detail &rarr;</a>
            </div>
        </div>

        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
            <div class="flex items-center justify-between text-xs text-sage-gray font-medium">
                <span>Dosen Pengajar</span>
                <span class="font-mono text-[10px] text-olive-char">FAC</span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold font-mono text-deep-moss">{{ number_format($totalDosen) }}</span>
                <a href="{{ route('admin.dosen.index') }}" class="text-xs text-signal-blue hover:underline">Detail &rarr;</a>
            </div>
        </div>

        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
            <div class="flex items-center justify-between text-xs text-sage-gray font-medium">
                <span>Program Studi</span>
                <span class="font-mono text-[10px] text-olive-char">DEPT</span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold font-mono text-deep-moss">{{ $prodis->count() }}</span>
                <a href="{{ route('admin.prodi.index') }}" class="text-xs text-signal-blue hover:underline">Detail &rarr;</a>
            </div>
        </div>

        @php
            $totalNilai = $distribusiGrade->sum('jumlah');
        @endphp
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
            <div class="flex items-center justify-between text-xs text-sage-gray font-medium">
                <span>Rekor Nilai Terinput</span>
                <span class="font-mono text-[10px] text-olive-char">EVAL</span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold font-mono text-deep-moss">{{ number_format($totalNilai) }}</span>
                <a href="{{ route('admin.laporan.index') }}" class="text-xs text-signal-blue hover:underline">Laporan &rarr;</a>
            </div>
        </div>
    </div>

    {{-- Main 2-Column Grid: Distribusi Grade & Aktivitas Semester --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Distribusi Grade Mutu --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-warm-mist mb-3">
                    <div>
                        <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">Sebaran Huruf Mutu (Grade)</h2>
                        <p class="text-[11px] text-sage-gray">Distribusi nilai evaluasi dari seluruh kelas</p>
                    </div>
                    <span class="font-mono text-xs font-bold text-olive-char">{{ $totalNilai }} Data</span>
                </div>

                <div class="space-y-3">
                    @forelse ($distribusiGrade as $item)
                        @php
                            $percentage = $totalNilai > 0 ? round(($item->jumlah / $totalNilai) * 100, 1) : 0;
                            $barBg = match($item->grade) {
                                'A' => 'bg-moss-green',
                                'B' => 'bg-signal-blue',
                                'C' => 'bg-marigold',
                                'D' => 'bg-flame-orange',
                                default => 'bg-ash-green',
                            };
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-[2px] font-mono font-bold text-[11px] border border-warm-mist bg-cream-paper">
                                        {{ $item->grade }}
                                    </span>
                                    <span class="font-semibold text-deep-moss">{{ $item->jumlah }} mhs</span>
                                    <span class="text-sage-gray text-[11px]">({{ $percentage }}%)</span>
                                </div>
                                <div class="text-[11px] text-olive-char">
                                    Rata-rata: <span class="font-mono font-semibold">{{ $item->rata_rata }}</span>
                                </div>
                            </div>
                            <div class="h-1.5 w-full bg-soft-linen rounded-full overflow-hidden">
                                <div class="h-full {{ $barBg }} transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-sage-gray">Belum ada data nilai terinput.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-warm-mist flex items-center justify-between text-[11px] text-sage-gray">
                <span>Dihitung secara otomatis di server</span>
                <a href="{{ route('admin.laporan.index') }}" class="text-signal-blue hover:underline">Lihat Laporan Lengkap &rarr;</a>
            </div>
        </div>

        {{-- Aktivitas Nilai per Semester --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-warm-mist mb-3">
                    <div>
                        <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">Aktivitas Evaluasi per Semester</h2>
                        <p class="text-[11px] text-sage-gray">Volume entri nilai menurut kurikulum</p>
                    </div>
                </div>

                <div class="overflow-hidden border border-warm-mist rounded-[4px]">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char">
                            <tr>
                                <th class="px-3 py-2">Tahun Akademik</th>
                                <th class="px-3 py-2">Semester</th>
                                <th class="px-3 py-2 text-right">Volume</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-warm-mist">
                            @forelse ($grafikNorm as $item)
                                <tr class="hover:bg-cream-paper transition">
                                    <td class="px-3 py-2 font-mono font-medium text-deep-moss">{{ $item->tahun }}</td>
                                    <td class="px-3 py-2">
                                        <span class="tag-pill {{ $item->semester === 'Ganjil' ? 'tag-pill-blue' : 'tag-pill-green' }} text-[10px]">
                                            {{ $item->semester }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-right font-mono font-bold text-deep-moss">
                                        {{ number_format($item->jumlah) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-xs text-sage-gray">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-warm-mist text-[11px] text-sage-gray text-right">
                <span>{{ $grafikNorm->count() }} Periode Semester Terdata</span>
            </div>
        </div>
    </div>

    {{-- Bottom Grid: Mahasiswa per Prodi & Riwayat Semester --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 bg-paper-white border border-warm-mist rounded-[4px] p-4">
            <div class="flex items-center justify-between pb-3 border-b border-warm-mist mb-3">
                <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">Sebaran Mahasiswa per Prodi</h2>
                <a href="{{ route('admin.prodi.create') }}" class="text-xs text-signal-blue hover:underline">+ Tambah Prodi</a>
            </div>

            <div class="overflow-hidden border border-warm-mist rounded-[4px]">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char">
                        <tr>
                            <th class="px-3 py-2">Kode</th>
                            <th class="px-3 py-2">Nama Program Studi</th>
                            <th class="px-3 py-2 text-right">Mahasiswa</th>
                            <th class="px-3 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist">
                        @forelse ($prodis as $prodi)
                            <tr class="hover:bg-cream-paper transition">
                                <td class="px-3 py-2 font-mono font-bold text-signal-blue">{{ $prodi->kode_prodi }}</td>
                                <td class="px-3 py-2 font-medium text-deep-moss">{{ $prodi->nama_prodi }}</td>
                                <td class="px-3 py-2 text-right font-mono font-bold">{{ $prodi->mahasiswas_count }}</td>
                                <td class="px-3 py-2 text-right">
                                    <a href="{{ route('admin.prodi.edit', $prodi) }}" class="text-xs text-signal-blue hover:underline">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-xs text-sage-gray">Belum ada program studi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
            <div class="pb-3 border-b border-warm-mist mb-3">
                <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">Semester Terakhir</h2>
            </div>

            <div class="space-y-2">
                @forelse ($lastSemester as $item)
                    <div class="p-2.5 bg-cream-paper border border-warm-mist rounded-[4px] flex items-center justify-between text-xs">
                        <div>
                            <div class="font-mono font-bold text-deep-moss">{{ $item->tahun }}</div>
                            <div class="text-[11px] text-sage-gray">Semester {{ $item->semester }}</div>
                        </div>
                        <span class="tag-pill tag-pill-stone text-[10px]">Aktif</span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-sage-gray">Belum ada riwayat semester.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
