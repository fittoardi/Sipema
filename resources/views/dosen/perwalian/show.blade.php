<x-layouts.dashboard title="Review KRS Mahasiswa">
    <div class="space-y-6">

        {{-- Navigation & Header Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <a href="{{ route('dosen.perwalian.index') }}" class="font-mono text-xs text-signal-blue hover:underline">&larr; Kembali ke Daftar Bimbingan</a>
                    <span class="text-olive-char text-xs">/</span>
                    <span class="font-mono text-xs font-bold text-deep-moss">review_krs.view</span>
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
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">Mahasiswa Bimbingan</span>
                        <span class="text-xs text-olive-char font-mono">NIM: {{ $mahasiswa->nim }}</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-deep-moss font-sans">
                        {{ $mahasiswa->nama }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-olive-char font-mono">
                        <span class="font-bold text-deep-moss">{{ $mahasiswa->programStudi->nama ?? $mahasiswa->programStudi->nama_prodi }}</span>
                        <span>&bull;</span>
                        <span>Angkatan {{ $mahasiswa->angkatan }} (Semester {{ $mahasiswa->semester }})</span>
                        <span>&bull;</span>
                        <span>Tahun Akademik: <strong>{{ $tahunAktif->tahun }} {{ $tahunAktif->semester->value }}</strong></span>
                    </div>
                </div>

                <div>
                    @if($krs)
                        <span class="px-3 py-1.5 rounded-[4px] text-xs font-mono font-bold uppercase
                            @if($krs->status->value === 'disetujui') bg-emerald-100 text-emerald-800 border border-emerald-300
                            @elseif($krs->status->value === 'diajukan') bg-blue-100 text-blue-800 border border-blue-300
                            @elseif($krs->status->value === 'revisi') bg-amber-100 text-amber-800 border border-amber-300
                            @else bg-zinc-100 text-zinc-800 border border-zinc-300 @endif">
                            Status: {{ $krs->status->label() }}
                        </span>
                    @else
                        <span class="tag-pill tag-pill-red text-xs">Belum Menyusun KRS</span>
                    @endif
                </div>
            </div>
        </div>

        @if($krs)
            {{-- Course List Table --}}
            <div class="window-card">
                <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-xs font-bold text-deep-moss">planned_courses.table</span>
                        <span class="tag-pill tag-pill-amber text-[10px]">{{ $krs->total_sks }} SKS Direncanakan</span>
                    </div>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-pale-stone/50 border-b border-warm-mist font-mono uppercase text-olive-char text-[11px]">
                            <tr>
                                <th class="px-4 py-2.5">No</th>
                                <th class="px-4 py-2.5">Kode & Mata Kuliah</th>
                                <th class="px-4 py-2.5 text-center">SKS</th>
                                <th class="px-4 py-2.5">Kelas</th>
                                <th class="px-4 py-2.5">Jadwal & Ruangan</th>
                                <th class="px-4 py-2.5">Dosen Pengampu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-warm-mist font-sans">
                            @forelse($krs->details as $idx => $detail)
                                @php $kls = $detail->kelas; @endphp
                                <tr class="hover:bg-soft-linen/50 transition">
                                    <td class="px-4 py-3 font-mono text-olive-char">{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-deep-moss">{{ $kls->mataKuliah->nama }}</div>
                                        <div class="text-[10px] font-mono text-olive-char">{{ $kls->mataKuliah->kode }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-bold">{{ $kls->sks }}</td>
                                    <td class="px-4 py-3 font-mono font-semibold">{{ $kls->nama_kelas }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-deep-moss">{{ $kls->hari->value ?? $kls->hari }}, {{ $kls->jam_rentang }}</div>
                                        <div class="text-[10px] text-olive-char font-mono">{{ $kls->ruangan->nama ?? 'Online' }} ({{ $kls->tipe_kelas->value ?? $kls->tipe_kelas }})</div>
                                    </td>
                                    <td class="px-4 py-3 text-deep-moss">{{ $kls->dosen->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-olive-char font-mono text-xs">
                                        Mahasiswa belum memilih mata kuliah pada KRS ini.
                                    </td>
                                </tr>
                            @endforelse
                            <tr class="bg-pale-stone/30 font-mono font-bold text-deep-moss">
                                <td colspan="2" class="px-4 py-3 text-right uppercase">Total Beban SKS:</td>
                                <td class="px-4 py-3 text-center text-sm text-signal-blue">{{ $krs->total_sks }}</td>
                                <td colspan="3" class="px-4 py-3 text-olive-char font-normal">Maksimal SKS: {{ $tahunAktif->maks_sks_default ?? 24 }} SKS</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Advisor Action Box --}}
            <div class="grid gap-6 grid-cols-1 md:grid-cols-2">
                {{-- Approve Panel --}}
                <div class="window-card p-6 bg-cream-paper border border-warm-mist">
                    <h3 class="font-bold text-sm text-deep-moss font-sans">Setujui Kartu Rencana Studi</h3>
                    <p class="text-xs text-olive-char mt-1">
                        Menyetujui KRS ini akan mengunci pilihan mata kuliah dan secara otomatis mendaftarkan mahasiswa ke dalam kelas terkait.
                    </p>

                    <form method="POST" action="{{ route('dosen.perwalian.approve', $krs->id) }}" class="mt-4" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui KRS ini?')">
                        @csrf
                        <button type="submit" class="btn-amber text-xs py-2 px-4 w-full flex items-center justify-center gap-2" {{ $krs->status->value === 'disetujui' ? 'disabled' : '' }}>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $krs->status->value === 'disetujui' ? 'KRS Sudah Disetujui' : 'Setujui Rencana Studi' }}
                        </button>
                    </form>

                    @if($krs->status->value === 'disetujui')
                        <div class="mt-3 text-[11px] text-emerald-800 font-mono">
                            Disetujui oleh: {{ $krs->disetujuiOleh->name ?? 'Dosen Wali' }} pada {{ $krs->disetujui_pada?->format('d/m/Y H:i') }}
                        </div>
                    @endif
                </div>

                {{-- Request Revision Panel --}}
                <div class="window-card p-6 bg-cream-paper border border-warm-mist">
                    <h3 class="font-bold text-sm text-deep-moss font-sans">Minta Revisi ke Mahasiswa</h3>
                    <p class="text-xs text-olive-char mt-1">
                        Berikan instruksi revisi perwalian jika ada mata kuliah yang bentrok, prasyarat belum terpenuhi, atau kelebihan beban.
                    </p>

                    <form method="POST" action="{{ route('dosen.perwalian.revisi', $krs->id) }}" class="mt-4">
                        @csrf
                        <div>
                            <textarea name="catatan" rows="3" class="w-full text-xs p-2.5 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow" placeholder="Tuliskan catatan revisi untuk mahasiswa di sini..." required>{{ $krs->catatan_wali }}</textarea>
                        </div>
                        <button type="submit" class="btn-mist text-xs py-2 px-4 mt-2 w-full text-rose-700 hover:text-rose-900 border-rose-300">
                            Kirim Permintaan Revisi
                        </button>
                    </form>
                </div>
            </div>
        @endif

    </div>
</x-layouts.dashboard>

