<x-layouts.dashboard title="Kartu Rencana Studi (KRS)">
    <div class="space-y-6">

        {{-- Header Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">krs_online.academic</span>
                    <span class="tag-pill {{ $isKrsOpen ? 'tag-pill-green' : 'tag-pill-red' }} text-[10px]">
                        {{ $isKrsOpen ? 'Periode KRS Terbuka' : 'Periode KRS Ditutup' }}
                    </span>
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
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">Tahun Akademik: {{ $tahunAktif->tahun }} {{ $tahunAktif->semester->value }}</span>
                        <span class="text-xs text-olive-char font-mono">NIM: {{ $mahasiswa->nim }}</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-deep-moss font-sans">
                        Kartu Rencana Studi (KRS)
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-olive-char font-mono">
                        <span class="font-bold text-deep-moss">{{ $mahasiswa->nama }}</span>
                        <span>&bull;</span>
                        <span>{{ $mahasiswa->programStudi->nama ?? $mahasiswa->programStudi->nama_prodi }}</span>
                        <span>&bull;</span>
                        <span>Dosen Wali: <strong>{{ $mahasiswa->dosenWali->nama ?? 'Belum ditentukan' }}</strong></span>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    @if($krs->status->value === 'disetujui' || $krs->details->count() > 0)
                        <a href="{{ route('mahasiswa.krs.cetak-pdf') }}" target="_blank" class="btn-mist inline-flex items-center gap-2 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak KRS (PDF)
                        </a>
                    @endif

                    @if(($krs->status->value === 'draft' || $krs->status->value === 'revisi') && $krs->details->count() > 0 && $isKrsOpen)
                        <form method="POST" action="{{ route('mahasiswa.krs.submit') }}" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan rencana studi ini ke Dosen Wali?')">
                            @csrf
                            <button type="submit" class="btn-amber inline-flex items-center gap-2 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ajukan KRS ke Dosen Wali
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Catatan Revisi Alert --}}
        @if($krs->status->value === 'revisi' && $krs->catatan_wali)
            <div class="p-4 rounded-[4px] bg-amber-50 border border-amber-300 text-amber-950">
                <div class="flex items-start gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 mt-1 flex-shrink-0"></span>
                    <div>
                        <h4 class="font-bold text-xs font-mono uppercase tracking-wider text-amber-900">Perhatian: Dosen Wali Meminta Revisi KRS</h4>
                        <p class="text-xs mt-1">{{ $krs->catatan_wali }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Status & Summary Metrics --}}
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-3">
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Status KRS</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-[4px] text-xs font-mono font-bold uppercase
                        @if($krs->status->value === 'disetujui') bg-emerald-100 text-emerald-800 border border-emerald-300
                        @elseif($krs->status->value === 'diajukan') bg-blue-100 text-blue-800 border border-blue-300
                        @elseif($krs->status->value === 'revisi') bg-amber-100 text-amber-800 border border-amber-300
                        @else bg-zinc-100 text-zinc-800 border border-zinc-300 @endif">
                        {{ $krs->status->label() }}
                    </span>
                </div>
                <div class="text-[11px] text-ash-green font-mono mt-1">
                    @if($krs->status->value === 'disetujui')
                        Disetujui pada {{ $krs->disetujui_pada?->format('d/m/Y H:i') }}
                    @elseif($krs->status->value === 'diajukan')
                        Menunggu persetujuan Dosen Wali
                    @else
                        Silakan susun mata kuliah
                    @endif
                </div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Beban SKS Rencana</p>
                <div class="mt-2 text-2xl font-black text-deep-moss font-mono">
                    {{ $krs->total_sks }} <span class="text-xs font-normal text-olive-char">/ {{ $tahunAktif->maks_sks_default ?? 24 }} SKS</span>
                </div>
                <div class="w-full bg-soft-linen rounded-full h-1.5 mt-2 overflow-hidden border border-warm-mist">
                    @php $sksPercent = min(100, round(($krs->total_sks / max(1, ($tahunAktif->maks_sks_default ?? 24))) * 100)); @endphp
                    <div class="bg-signal-blue h-1.5 rounded-full" style="width: {{ $sksPercent }}%"></div>
                </div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Jumlah Kelas Terpilih</p>
                <div class="mt-2 text-2xl font-black text-deep-moss font-mono">
                    {{ $krs->details->count() }} <span class="text-xs font-normal text-olive-char">Mata Kuliah</span>
                </div>
                <div class="text-[11px] text-ash-green font-mono mt-1">
                    Kurikulum {{ $mahasiswa->angkatan }}
                </div>
            </div>
        </div>

        {{-- Table 1: Selected Courses (Rencana Studi) --}}
        <div class="window-card">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">selected_courses.db</span>
                    <span class="tag-pill tag-pill-amber text-[10px]">{{ $krs->details->count() }} Mata Kuliah</span>
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
                            <th class="px-4 py-2.5 text-right">Aksi</th>
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
                                <td class="px-4 py-3 font-mono font-semibold text-deep-moss">{{ $kls->nama_kelas }}</td>
                                <td class="px-4 py-3 text-xs">
                                    <div class="font-medium text-deep-moss">{{ $kls->hari->value ?? $kls->hari }}, {{ $kls->jam_rentang }}</div>
                                    <div class="text-[10px] text-olive-char font-mono">{{ $kls->ruangan->nama ?? 'Online / Tanpa Ruangan' }} ({{ $kls->tipe_kelas->value ?? $kls->tipe_kelas }})</div>
                                </td>
                                <td class="px-4 py-3 text-deep-moss">{{ $kls->dosen->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if(($krs->status->value === 'draft' || $krs->status->value === 'revisi') && $isKrsOpen)
                                        <form method="POST" action="{{ route('mahasiswa.krs.destroy', $kls->id) }}" onsubmit="return confirm('Hapus kelas ini dari rencana studi?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-mono text-xs px-2 py-1 rounded hover:bg-rose-50 border border-transparent hover:border-rose-200 transition">
                                                Batal Ambil
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-olive-char text-[11px] font-mono italic">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-olive-char font-mono text-xs">
                                    Belum ada mata kuliah yang dipilih. Silakan pilih kelas dari katalog di bawah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Table 2: Course Catalog for Current Semester --}}
        @if(($krs->status->value === 'draft' || $krs->status->value === 'revisi') && $isKrsOpen)
            <div class="window-card">
                <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-xs font-bold text-deep-moss">course_catalog.prodi</span>
                        <span class="tag-pill tag-pill-blue text-[10px]">{{ $availableClasses->count() }} Kelas Dibuka</span>
                    </div>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-pale-stone/50 border-b border-warm-mist font-mono uppercase text-olive-char text-[11px]">
                            <tr>
                                <th class="px-4 py-2.5">Kode & MK</th>
                                <th class="px-4 py-2.5 text-center">SKS</th>
                                <th class="px-4 py-2.5">Kelas</th>
                                <th class="px-4 py-2.5">Hari & Waktu</th>
                                <th class="px-4 py-2.5">Ruangan / Mode</th>
                                <th class="px-4 py-2.5">Dosen</th>
                                <th class="px-4 py-2.5 text-center">Kuota</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-warm-mist font-sans">
                            @foreach($availableClasses as $c)
                                @php
                                    $isAlreadyTaken = in_array($c->id, $selectedClassIds);
                                    $isFull = $c->kelas_mahasiswas_count >= $c->kapasitas;
                                    $sisaKuota = max(0, $c->kapasitas - $c->kelas_mahasiswas_count);
                                @endphp
                                <tr class="hover:bg-soft-linen/50 transition {{ $isAlreadyTaken ? 'bg-emerald-50/40' : '' }}">
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-deep-moss">{{ $c->mataKuliah->nama }}</div>
                                        <div class="text-[10px] font-mono text-olive-char">{{ $c->mataKuliah->kode }} &bull; Sem {{ $c->mataKuliah->semester_paket }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-bold">{{ $c->sks }}</td>
                                    <td class="px-4 py-3 font-mono font-semibold">{{ $c->nama_kelas }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-deep-moss">{{ $c->hari->value ?? $c->hari }}</div>
                                        <div class="text-[10px] font-mono text-olive-char">{{ $c->jam_rentang }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-deep-moss">{{ $c->ruangan->nama ?? 'Online' }}</div>
                                        <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded text-[9px] font-mono uppercase {{ $c->tipe_kelas->value === 'online' ? 'bg-purple-100 text-purple-800' : ($c->tipe_kelas->value === 'hybrid' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $c->tipe_kelas->value ?? $c->tipe_kelas }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-deep-moss">{{ $c->dosen->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center font-mono">
                                        <span class="{{ $isFull ? 'text-rose-600 font-bold' : 'text-emerald-700 font-medium' }}">
                                            {{ $c->kelas_mahasiswas_count }} / {{ $c->kapasitas }}
                                        </span>
                                        <div class="text-[10px] text-olive-char">Sisa: {{ $sisaKuota }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($isAlreadyTaken)
                                            <span class="tag-pill tag-pill-green text-[10px] font-mono">Terpilih</span>
                                        @elseif($isFull)
                                            <span class="text-rose-600 text-[10px] font-mono font-bold">Penuh</span>
                                        @else
                                            <form method="POST" action="{{ route('mahasiswa.krs.store') }}">
                                                @csrf
                                                <input type="hidden" name="kelas_id" value="{{ $c->id }}">
                                                <button type="submit" class="btn-amber text-xs py-1 px-2.5">
                                                    + Ambil
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</x-layouts.dashboard>

