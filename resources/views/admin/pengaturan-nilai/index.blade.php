@extends('layouts.admin')

@section('title', 'Pengaturan Nilai')
@section('header', 'Pengaturan Bobot & Skala Grade')
@section('window_file', 'pengaturan_nilai.sys')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-base font-bold text-deep-moss">Konfigurasi Formula Penilaian</h1>
        <p class="text-xs text-olive-char mt-0.5">Atur proporsi persentase komponen evaluasi dan batas minimal perolehan huruf mutu (grade).</p>
    </div>

    <form action="{{ route('admin.pengaturan-nilai.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Bobot Komponen Card --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
            <div class="flex items-center justify-between pb-3 border-b border-warm-mist mb-4">
                <div>
                    <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">1. Bobot Komponen Evaluasi</h2>
                    <p class="text-[11px] text-sage-gray">Total akumulasi persentase seluruh komponen wajib berjumlah tepat 100%.</p>
                </div>
                <span class="tag-pill tag-pill-amber text-xs font-mono font-bold">Total Wajib: 100%</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="posthog-label">Kuis (%)</label>
                    <input type="number" step="0.5" min="0" max="100" name="weights[kuis]" value="{{ old('weights.kuis', $weights['kuis'] ?? 10) }}"
                           class="posthog-input font-mono text-center font-bold" required>
                </div>
                <div>
                    <label class="posthog-label">Tugas (%)</label>
                    <input type="number" step="0.5" min="0" max="100" name="weights[tugas]" value="{{ old('weights.tugas', $weights['tugas'] ?? 20) }}"
                           class="posthog-input font-mono text-center font-bold" required>
                </div>
                <div>
                    <label class="posthog-label">UTS (%)</label>
                    <input type="number" step="0.5" min="0" max="100" name="weights[uts]" value="{{ old('weights.uts', $weights['uts'] ?? 30) }}"
                           class="posthog-input font-mono text-center font-bold" required>
                </div>
                <div>
                    <label class="posthog-label">UAS (%)</label>
                    <input type="number" step="0.5" min="0" max="100" name="weights[uas]" value="{{ old('weights.uas', $weights['uas'] ?? 40) }}"
                           class="posthog-input font-mono text-center font-bold" required>
                </div>
            </div>

            <div class="mt-4 p-3 bg-cream-paper border border-warm-mist rounded-[4px] flex items-center justify-between text-xs">
                <span class="text-olive-char font-mono">Rumus: Nilai Akhir = (Kuis &times; W1 + Tugas &times; W2 + UTS &times; W3 + UAS &times; W4) / 100</span>
                <span class="text-[11px] text-sage-gray">Hasil dibulatkan 2 desimal (half-up)</span>
            </div>
        </div>

        {{-- Skala Grade Card --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
            <div class="flex items-center justify-between pb-3 border-b border-warm-mist mb-4">
                <div>
                    <h2 class="text-xs font-bold text-deep-moss uppercase tracking-wider">2. Skala Huruf Mutu (Grade) & Kelulusan</h2>
                    <p class="text-[11px] text-sage-gray">Tentukan skor minimum untuk masing-masing grade dan flag status kelulusannya.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-warm-mist">
                    <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char">
                        <tr>
                            <th class="px-4 py-2 text-center w-20">Huruf Mutu</th>
                            <th class="px-4 py-2 w-48">Nilai Minimum (&ge;)</th>
                            <th class="px-4 py-2 text-center w-36">Status Kelulusan</th>
                            <th class="px-4 py-2 text-sage-gray">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist">
                        @foreach ($grades as $index => $item)
                            <tr class="hover:bg-cream-paper transition">
                                <td class="px-4 py-2.5 text-center font-bold font-mono text-deep-moss">
                                    <input type="hidden" name="grades[{{ $index }}][grade]" value="{{ $item['grade'] }}">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-[4px] border border-warm-mist bg-paper-white font-mono font-bold">
                                        {{ $item['grade'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sage-gray font-mono">&ge;</span>
                                        <input type="number" step="0.5" min="0" max="100" name="grades[{{ $index }}][min]"
                                               value="{{ old("grades.{$index}.min", $item['min']) }}"
                                               class="posthog-input font-mono w-24 text-center font-bold" required>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                        <input type="checkbox" name="grades[{{ $index }}][is_lulus]" value="1"
                                               class="rounded-[2px] border-warm-mist text-moss-green focus:ring-0"
                                               @checked(old("grades.{$index}.is_lulus", $item['is_lulus']))>
                                        <span class="text-xs font-semibold {{ $item['is_lulus'] ? 'text-moss-green' : 'text-flame-orange' }}">
                                            {{ $item['is_lulus'] ? 'Lulus' : 'Tidak Lulus' }}
                                        </span>
                                    </label>
                                </td>
                                <td class="px-4 py-2.5 text-sage-gray text-[11px]">
                                    @if ($item['grade'] === 'A') Sangat Baik (Predikat Pujian)
                                    @elseif ($item['grade'] === 'B') Baik (Kompeten)
                                    @elseif ($item['grade'] === 'C') Cukup (Batas Minimal Kelulusan)
                                    @elseif ($item['grade'] === 'D') Kurang (Harus Mengulang)
                                    @else Gagal / Tidak Lulus
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Opsi Hitung Ulang & Tombol Simpan --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <label class="flex items-center gap-2 cursor-pointer text-xs">
                <input type="checkbox" name="recalculate_now" value="1" class="rounded-[2px] border-warm-mist text-amber-glow focus:ring-0">
                <span class="text-deep-moss font-medium">Hitung ulang (*recalculate*) seluruh nilai snapshot mahasiswa sekarang</span>
            </label>

            <button type="submit" class="btn-amber">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Konfigurasi Nilai
            </button>
        </div>
    </form>
</div>
@endsection

