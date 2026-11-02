@extends('layouts.admin')

@section('title', 'Laporan & Ekspor Nilai')
@section('header', 'Laporan & Ekspor Nilai')
@section('window_file', 'laporan_nilai.csv')

@section('content')
<div class="space-y-4">
    {{-- Filter Panel --}}
    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 pb-3 border-b border-warm-mist">
            <div>
                <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">Saring & Ekspor Data Nilai</h2>
                <p class="text-[11px] text-sage-gray">Filter transkrip mahasiswa per Program Studi dan Rombel Kelas.</p>
            </div>

            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[11px] font-mono text-sage-gray mr-1">EXPORT:</span>
                <a href="{{ route('admin.laporan.export', request()->query()) }}" class="btn-mist">
                    <svg class="w-3.5 h-3.5 text-moss-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    CSV
                </a>
                <a href="{{ route('admin.laporan.export-excel', request()->query()) }}" class="btn-mist">
                    <svg class="w-3.5 h-3.5 text-signal-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel (.xls)
                </a>
                <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" class="btn-mist">
                    <svg class="w-3.5 h-3.5 text-flame-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 pt-3 items-end text-xs">
            <div class="md:col-span-5">
                <label class="posthog-label">Program Studi</label>
                <select name="prodi_id" class="posthog-select">
                    <option value="">Semua Program Studi</option>
                    @foreach ($prodis as $prodi)
                        <option value="{{ $prodi->id }}" @selected(request('prodi_id') == $prodi->id)>
                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-5">
                <label class="posthog-label">Kelas Perkuliahan</label>
                <select name="kelas_id" class="posthog-select">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>
                            {{ $k->mataKuliah?->nama ?? 'MK' }} - {{ $k->nama }} ({{ $k->semester }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex items-center gap-1.5">
                <button type="submit" class="btn-amber w-full">
                    Filter
                </button>
                @if(request()->hasAny(['prodi_id', 'kelas_id']))
                    <a href="{{ route('admin.laporan.index') }}" class="btn-mist" title="Reset filter">
                        &times;
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <span class="font-bold text-deep-moss">Transkrip Nilai Mahasiswa</span>
            <span class="font-mono text-sage-gray">{{ $nilais->total() }} entri</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Mahasiswa</th>
                        <th class="px-4 py-2.5">Program Studi</th>
                        <th class="px-4 py-2.5">Mata Kuliah & Kelas</th>
                        <th class="px-4 py-2.5 text-center">Nilai Akhir</th>
                        <th class="px-4 py-2.5 text-center">Grade</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse ($nilais as $nilai)
                        @php
                            $isLulus = $nilai->status instanceof \App\Enums\StatusNilai
                                ? $nilai->status === \App\Enums\StatusNilai::Lulus
                                : strtolower((string) ($nilai->status->value ?? $nilai->status ?? '')) === 'lulus';
                            $statusLabel = $nilai->status instanceof \App\Enums\StatusNilai
                                ? $nilai->status->label()
                                : (string) ($nilai->status->value ?? $nilai->status ?? '-');
                            $gradePill = match($nilai->grade) {
                                'A' => 'tag-pill-green',
                                'B' => 'tag-pill-blue',
                                'C' => 'tag-pill-amber',
                                'D' => 'tag-pill-orange',
                                default => 'tag-pill-stone',
                            };
                        @endphp
                        <tr class="hover:bg-cream-paper transition">
                            <td class="px-4 py-3">
                                <div class="font-bold text-deep-moss">
                                    {{ $nilai->mahasiswa?->nama ?? $nilai->mahasiswa?->user?->name ?? '-' }}
                                </div>
                                <div class="font-mono text-[11px] text-sage-gray">NIM: {{ $nilai->mahasiswa?->nim ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="tag-pill tag-pill-stone text-[10px]">
                                    {{ $nilai->mahasiswa?->prodi?->nama_prodi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-deep-moss">{{ $nilai->kelas?->mataKuliah?->nama ?? '-' }}</div>
                                <div class="text-[11px] text-sage-gray">
                                    Kelas <strong>{{ $nilai->kelas?->nama ?? '-' }}</strong> &bull; {{ $nilai->kelas?->semester }} {{ $nilai->kelas?->tahun_akademik }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center font-mono font-bold text-deep-moss text-sm">
                                {{ number_format($nilai->nilai_akhir ?? 0, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="tag-pill {{ $gradePill }} font-mono font-bold">
                                    {{ $nilai->grade ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="tag-pill {{ $isLulus ? 'tag-pill-green' : 'tag-pill-orange' }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-xs text-sage-gray">
                                Tidak ada data nilai yang sesuai kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($nilais->hasPages())
            <div class="px-4 py-2 border-t border-warm-mist bg-cream-paper">
                {{ $nilais->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
