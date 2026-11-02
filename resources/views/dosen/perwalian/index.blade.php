<x-layouts.dashboard title="Perwalian KRS Mahasiswa Bimbingan">
    <div class="space-y-6">

        {{-- Header Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">advisor_roster.db</span>
                    <span class="tag-pill tag-pill-blue text-[10px]">Tahun: {{ $tahunAktif->tahun }} {{ $tahunAktif->semester->value }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-6 bg-cream-paper flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">Dosen Pembimbing Akademik</span>
                        <span class="text-xs text-olive-char font-mono">NIDN: {{ $dosen->nidn }}</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-deep-moss font-sans">
                        Perwalian & Rencana Studi Mahasiswa
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-olive-char font-mono">
                        <span class="font-bold text-deep-moss">{{ $dosen->nama }}</span>
                        <span>&bull;</span>
                        <span>Total Mahasiswa Bimbingan: <strong>{{ $totalAdvisees }}</strong></span>
                    </div>
                </div>

                {{-- Status Filter Buttons --}}
                <div class="flex items-center gap-2 flex-wrap text-xs">
                    <a href="{{ route('dosen.perwalian.index') }}" class="px-3 py-1.5 rounded-[4px] border font-mono transition {{ empty($currentFilter) ? 'bg-deep-moss text-paper-white border-deep-moss font-bold' : 'bg-paper-white text-olive-char border-warm-mist hover:bg-pale-stone' }}">
                        Semua ({{ $totalAdvisees }})
                    </a>
                    <a href="{{ route('dosen.perwalian.index', ['status' => 'diajukan']) }}" class="px-3 py-1.5 rounded-[4px] border font-mono transition {{ $currentFilter === 'diajukan' ? 'bg-signal-blue text-paper-white border-signal-blue font-bold' : 'bg-paper-white text-olive-char border-warm-mist hover:bg-pale-stone' }}">
                        Menunggu Persetujuan ({{ $pendingCount }})
                    </a>
                    <a href="{{ route('dosen.perwalian.index', ['status' => 'disetujui']) }}" class="px-3 py-1.5 rounded-[4px] border font-mono transition {{ $currentFilter === 'disetujui' ? 'bg-moss-green text-paper-white border-moss-green font-bold' : 'bg-paper-white text-olive-char border-warm-mist hover:bg-pale-stone' }}">
                        Disetujui ({{ $approvedCount }})
                    </a>
                </div>
            </div>
        </div>

        {{-- Advisee List Table --}}
        <div class="window-card">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">advisee_table.view</span>
                    <span class="tag-pill tag-pill-amber text-[10px]">{{ $advisees->total() }} Mahasiswa</span>
                </div>
            </div>

            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-pale-stone/50 border-b border-warm-mist font-mono uppercase text-olive-char text-[11px]">
                        <tr>
                            <th class="px-4 py-2.5">No</th>
                            <th class="px-4 py-2.5">NIM & Mahasiswa</th>
                            <th class="px-4 py-2.5">Program Studi</th>
                            <th class="px-4 py-2.5 text-center">Angkatan</th>
                            <th class="px-4 py-2.5 text-center">Total SKS</th>
                            <th class="px-4 py-2.5 text-center">Status KRS</th>
                            <th class="px-4 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist font-sans">
                        @forelse($advisees as $idx => $mhs)
                            @php
                                $krs = $mhs->krs->first();
                                $status = $krs?->status->value ?? 'belum_krs';
                            @endphp
                            <tr class="hover:bg-soft-linen/50 transition">
                                <td class="px-4 py-3 font-mono text-olive-char">{{ $advisees->firstItem() + $idx }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-deep-moss">{{ $mhs->nama }}</div>
                                    <div class="text-[10px] font-mono text-olive-char">NIM: {{ $mhs->nim }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-deep-moss text-xs">{{ $mhs->programStudi->nama ?? $mhs->programStudi->nama_prodi }}</div>
                                    @if($mhs->programStudi?->fakultas)
                                        <div class="mt-0.5">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold border {{ $mhs->fakultas_badge_color }}">
                                                {{ $mhs->programStudi->fakultas->kode }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center font-mono">{{ $mhs->angkatan }}</td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-deep-moss">
                                    {{ $krs ? $krs->total_sks : 0 }} SKS
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($krs)
                                        <span class="px-2 py-0.5 rounded-[4px] text-[10px] font-mono font-bold uppercase
                                            @if($status === 'disetujui') bg-emerald-100 text-emerald-800 border border-emerald-300
                                            @elseif($status === 'diajukan') bg-blue-100 text-blue-800 border border-blue-300 animate-pulse
                                            @elseif($status === 'revisi') bg-amber-100 text-amber-800 border border-amber-300
                                            @else bg-zinc-100 text-zinc-800 border border-zinc-300 @endif">
                                            {{ $krs->status->label() }}
                                        </span>
                                    @else
                                        <span class="text-zinc-400 font-mono text-[10px] italic">Belum mengisi</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('dosen.perwalian.show', $mhs->id) }}" class="btn-amber text-xs py-1 px-2.5">
                                        Periksa KRS &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-olive-char font-mono text-xs">
                                    Tidak ada data mahasiswa bimbingan yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($advisees->hasPages())
                <div class="p-4 border-t border-warm-mist bg-soft-linen">
                    {{ $advisees->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.dashboard>

