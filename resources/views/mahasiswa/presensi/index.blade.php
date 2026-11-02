<x-layouts.dashboard title="Rekapitulasi Kehadiran Kuliah">
    <div class="space-y-6">
        {{-- Window Titlebar Header --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">attendance_history.rec</span>
                    <span class="tag-pill tag-pill-blue text-[10px]">Verifikasi Selfie &amp; GPS</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-5 bg-cream-paper flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">Angkatan {{ $mahasiswa->angkatan }}</span>
                        <span class="text-xs text-olive-char font-mono">NIM: {{ $mahasiswa->nim }}</span>
                        @if($mahasiswa->programStudi?->fakultas)
                            <span class="tag-pill tag-pill-stone text-[10px] font-bold">{{ $mahasiswa->programStudi->fakultas->kode }}</span>
                        @endif
                    </div>
                    <h1 class="text-xl font-black tracking-tight text-deep-moss font-sans">
                        Rekapitulasi Kehadiran &amp; Presensi Mahasiswa
                    </h1>
                    <p class="text-xs text-olive-char mt-0.5 font-mono">
                        Catatan riwayat absensi live WebRTC selfie, koordinat GPS geofencing, dan persentase kelayakan ujian akhir semester.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('mahasiswa.jadwal') }}" class="btn-mist text-xs">
                        Jadwal &amp; Kalender
                    </a>
                    <a href="{{ route('mahasiswa.kelas.index') }}" class="btn-amber text-xs">
                        Mata Kuliah Saya
                    </a>
                </div>
            </div>
        </div>

        {{-- Metric Overview Cards --}}
        <div class="grid gap-3 grid-cols-2 sm:grid-cols-5">
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Rata Kehadiran</p>
                <div class="mt-1 text-2xl font-black {{ $overallPercentage >= 75 ? 'text-emerald-700' : 'text-flame-orange' }} font-mono">
                    {{ $overallPercentage }}%
                </div>
                <div class="text-[10px] text-ash-green font-mono">{{ $overallPercentage >= 75 ? 'Memenuhi Syarat' : 'Peringatan < 75%' }}</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Hadir</p>
                <div class="mt-1 text-2xl font-black text-emerald-700 font-mono">{{ $totalHadir }}</div>
                <div class="text-[10px] text-ash-green font-mono">Sesi terverifikasi</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Izin</p>
                <div class="mt-1 text-2xl font-black text-signal-blue font-mono">{{ $totalIzin }}</div>
                <div class="text-[10px] text-ash-green font-mono">Surat keterangan</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Sakit</p>
                <div class="mt-1 text-2xl font-black text-amber-700 font-mono">{{ $totalSakit }}</div>
                <div class="text-[10px] text-ash-green font-mono">Dengan surat dokter</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Alpha / Tanpa Ket.</p>
                <div class="mt-1 text-2xl font-black {{ $totalAlpha > 0 ? 'text-flame-orange' : 'text-deep-moss' }} font-mono">
                    {{ $totalAlpha }}
                </div>
                <div class="text-[10px] text-ash-green font-mono">Tidak hadir</div>
            </div>
        </div>

        {{-- Per-Course Attendance Table --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden shadow-2xs">
            <div class="px-4 py-3 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
                <div>
                    <h2 class="font-bold text-deep-moss uppercase tracking-wider text-xs">Rekapitulasi Kehadiran per Mata Kuliah</h2>
                    <p class="text-[11px] text-olive-char">Persentase kehadiran dan status syarat mengikuti Ujian Akhir Semester (UAS min. 75%).</p>
                </div>
                <span class="font-mono text-sage-gray">{{ $rekapPerKelas->count() }} Mata Kuliah</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-soft-linen border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-2.5">Mata Kuliah</th>
                            <th class="px-3 py-2.5 text-center">Kelas</th>
                            <th class="px-3 py-2.5 text-center">Hadir</th>
                            <th class="px-3 py-2.5 text-center">Izin</th>
                            <th class="px-3 py-2.5 text-center">Sakit</th>
                            <th class="px-3 py-2.5 text-center">Alpha</th>
                            <th class="px-4 py-2.5 text-center">Persentase</th>
                            <th class="px-4 py-2.5 text-center">Syarat UAS</th>
                            <th class="px-4 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist">
                        @forelse($rekapPerKelas as $item)
                            <tr class="hover:bg-cream-paper transition">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-deep-moss">{{ $item->mataKuliah?->nama ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-sage-gray mt-0.5">
                                        {{ $item->mataKuliah?->kode ?? '-' }} &bull; Dosen: {{ $item->dosen?->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center font-mono font-bold text-deep-moss">
                                    {{ $item->kelas?->nama_kelas ?? '-' }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono font-bold text-emerald-700">
                                    {{ $item->hadir }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono text-signal-blue">
                                    {{ $item->izin }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono text-amber-700">
                                    {{ $item->sakit }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono {{ $item->alpha > 0 ? 'text-flame-orange font-bold' : 'text-sage-gray' }}">
                                    {{ $item->alpha }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="font-mono font-bold {{ $item->persentase >= 75 ? 'text-emerald-700' : 'text-flame-orange' }}">
                                        {{ $item->persentase }}%
                                    </div>
                                    <div class="w-20 mx-auto bg-soft-linen rounded-full h-1.5 mt-1 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $item->persentase >= 75 ? 'bg-emerald-600' : 'bg-flame-orange' }}" style="width: {{ min(100, $item->persentase) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="tag-pill {{ $item->is_eligible_uas ? 'tag-pill-green' : 'tag-pill-orange' }} text-[10px] font-bold">
                                        {{ $item->is_eligible_uas ? 'Memenuhi Syarat' : 'Peringatan < 75%' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('mahasiswa.kelas.show', $item->kelas->id) }}" class="btn-mist text-xs py-1 px-2.5 text-signal-blue">
                                        Ruang LMS &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-sage-gray text-xs">
                                    Belum ada data perkuliahan yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Riwayat Presensi Terbaru Table --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden shadow-2xs">
            <div class="px-4 py-3 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
                <div>
                    <h2 class="font-bold text-deep-moss uppercase tracking-wider text-xs">Riwayat Presensi Terbaru</h2>
                    <p class="text-[11px] text-olive-char">Bukti kehadiran live camera selfie dan pencatatan titik koordinat GPS.</p>
                </div>
                <span class="font-mono text-sage-gray">15 Log Terakhir</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-soft-linen border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-2.5">Waktu Presensi</th>
                            <th class="px-4 py-2.5">Mata Kuliah &amp; Sesi</th>
                            <th class="px-3 py-2.5 text-center">Status</th>
                            <th class="px-4 py-2.5">Verifikasi Lokasi &amp; Jarak GPS</th>
                            <th class="px-4 py-2.5 text-center">Metode</th>
                            <th class="px-4 py-2.5 text-center">Bukti Selfie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist">
                        @forelse($riwayatPresensi as $rp)
                            @php
                                $statusVal = strtolower($rp->status?->value ?? (string) $rp->status);
                                $statusBadge = match($statusVal) {
                                    'hadir' => 'tag-pill-green',
                                    'izin' => 'tag-pill-blue',
                                    'sakit' => 'tag-pill-amber',
                                    default => 'tag-pill-orange',
                                };
                            @endphp
                            <tr class="hover:bg-cream-paper transition">
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-olive-char text-[11px]">
                                    <div class="font-bold text-deep-moss">{{ $rp->dicatat_pada ? $rp->dicatat_pada->format('d M Y') : '-' }}</div>
                                    <div class="text-sage-gray">{{ $rp->dicatat_pada ? $rp->dicatat_pada->format('H:i') . ' WIB' : '-' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-deep-moss">{{ $rp->pertemuan?->kelas?->mataKuliah?->nama ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-sage-gray mt-0.5">
                                        Sesi Ke-{{ $rp->pertemuan?->pertemuan_ke ?? '-' }} &bull; Kelas {{ $rp->pertemuan?->kelas?->nama_kelas ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="tag-pill {{ $statusBadge }} font-bold text-xs uppercase">
                                        {{ $statusVal }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono text-[11px]">
                                    @if($rp->jarak_meter !== null)
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold {{ $rp->is_dalam_radius ? 'text-emerald-700' : 'text-flame-orange' }}">
                                                {{ round($rp->jarak_meter) }} meter
                                            </span>
                                            <span class="text-sage-gray">({{ $rp->is_dalam_radius ? 'Dalam Radius' : 'Di Luar Radius' }})</span>
                                        </div>
                                        @if($rp->latitude && $rp->longitude)
                                            <a href="https://www.google.com/maps?q={{ $rp->latitude }},{{ $rp->longitude }}" target="_blank" class="text-signal-blue hover:underline text-[10px] inline-flex items-center gap-1 mt-0.5">
                                                Cek Google Maps &rarr;
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-sage-gray">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center font-mono text-[11px]">
                                    <span class="tag-pill tag-pill-stone text-[10px]">
                                        {{ $rp->metode?->value ?? ($rp->metode ?? 'Mandiri') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($rp->foto_selfie_path)
                                        <a href="{{ route('foto-presensi.show', $rp->id) }}" target="_blank" class="inline-block p-1 rounded border border-warm-mist hover:border-signal-blue transition">
                                            <img src="{{ route('foto-presensi.show', $rp->id) }}" alt="Selfie" class="w-8 h-8 rounded object-cover">
                                        </a>
                                    @else
                                        <span class="text-sage-gray font-mono text-[10px]">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sage-gray text-xs">
                                    Belum ada catatan riwayat absensi yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

