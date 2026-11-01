<x-layouts.dashboard title="Presensi Sesi {{ $pertemuan->pertemuan_ke }} - {{ $kelas->mataKuliah->nama }}">
    <div class="space-y-6">

        {{-- Header Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <a href="{{ route('dosen.pertemuan.show', [$kelas->id, $pertemuan->id]) }}" class="font-mono text-xs text-signal-blue hover:underline">&larr; Kembali ke Detail Sesi</a>
                    <span class="text-olive-char text-xs">/</span>
                    <span class="font-mono text-xs font-bold text-deep-moss">attendance_gallery.db</span>
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
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">Sesi {{ $pertemuan->pertemuan_ke }} &bull; {{ $pertemuan->mode->value ?? $pertemuan->mode }}</span>
                        <span class="text-xs text-olive-char font-mono">Tanggal: {{ $pertemuan->tanggal ? \Carbon\Carbon::parse($pertemuan->tanggal)->format('d F Y') : '-' }}</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-deep-moss font-sans">
                        Rekap & Verifikasi Presensi Mahasiswa
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-olive-char font-mono">
                        <span class="font-bold text-deep-moss">{{ $kelas->mataKuliah->nama }} ({{ $kelas->nama_kelas }})</span>
                        <span>&bull;</span>
                        <span>Lokasi: {{ $pertemuan->ruangan->nama ?? 'Online' }} (Radius: {{ $pertemuan->ruangan->radius_meter ?? 100 }}m)</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('dosen.presensi.export-csv', [$kelas->id, $pertemuan->id]) }}" class="btn-mist inline-flex items-center gap-2 text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Ekspor CSV
                    </a>
                </div>
            </div>
        </div>

        {{-- Metric Cards --}}
        <div class="grid gap-4 grid-cols-2 sm:grid-cols-4">
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Hadir (Selfie + GPS)</p>
                <div class="mt-2 text-2xl font-black text-emerald-700 font-mono">{{ $hadirCount }}</div>
                <div class="text-[10px] text-ash-green font-mono mt-1">Tervalidasi sistem</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Izin</p>
                <div class="mt-2 text-2xl font-black text-blue-700 font-mono">{{ $izinCount }}</div>
                <div class="text-[10px] text-ash-green font-mono mt-1">Konfirmasi dosen</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Sakit</p>
                <div class="mt-2 text-2xl font-black text-amber-700 font-mono">{{ $sakitCount }}</div>
                <div class="text-[10px] text-ash-green font-mono mt-1">Surat keterangan</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Alpha / Belum Hadir</p>
                <div class="mt-2 text-2xl font-black text-rose-700 font-mono">{{ $alphaCount }}</div>
                <div class="text-[10px] text-ash-green font-mono mt-1">Dari {{ $enrolledStudents->count() }} mahasiswa</div>
            </div>
        </div>

        {{-- Verification Table --}}
        <div class="window-card">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">attendance_records.view</span>
                    <span class="tag-pill tag-pill-stone text-[10px]">{{ $enrolledStudents->count() }} Mahasiswa Terdaftar</span>
                </div>
            </div>

            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-pale-stone/50 border-b border-warm-mist font-mono uppercase text-olive-char text-[11px]">
                        <tr>
                            <th class="px-4 py-2.5">No</th>
                            <th class="px-4 py-2.5">Mahasiswa</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                            <th class="px-4 py-2.5">Bukti Foto Selfie</th>
                            <th class="px-4 py-2.5">Geolokasi & Jarak</th>
                            <th class="px-4 py-2.5">Waktu Dicatat</th>
                            <th class="px-4 py-2.5 text-right">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist font-sans">
                        @foreach($enrolledStudents as $idx => $mhs)
                            @php $p = $presensis->get($mhs->id); @endphp
                            <tr class="hover:bg-soft-linen/50 transition">
                                <td class="px-4 py-3 font-mono text-olive-char">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-deep-moss">{{ $mhs->nama }}</div>
                                    <div class="text-[10px] font-mono text-olive-char">NIM: {{ $mhs->nim }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($p)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase
                                            @if($p->status->value === 'hadir') bg-emerald-100 text-emerald-800 border border-emerald-300
                                            @elseif($p->status->value === 'izin') bg-blue-100 text-blue-800 border border-blue-300
                                            @elseif($p->status->value === 'sakit') bg-amber-100 text-amber-800 border border-amber-300
                                            @else bg-rose-100 text-rose-800 border border-rose-300 @endif">
                                            {{ $p->status->label() }}
                                        </span>
                                        <div class="text-[9px] font-mono text-olive-char mt-0.5">{{ $p->metode->value ?? 'mandiri' }}</div>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-rose-100 text-rose-800 border border-rose-300 uppercase">
                                            Alpha
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($p && $p->foto_selfie_path)
                                        <a href="{{ route('foto-presensi.show', $p->id) }}" target="_blank" class="inline-flex items-center gap-1.5 group">
                                            <img src="{{ route('foto-presensi.show', $p->id) }}" alt="Selfie" class="w-10 h-10 object-cover rounded border border-warm-mist group-hover:border-signal-blue transition">
                                            <span class="text-[10px] font-mono text-signal-blue underline group-hover:text-deep-moss">Lihat Foto</span>
                                        </a>
                                    @else
                                        <span class="text-olive-char text-[10px] font-mono italic">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($p && $p->latitude)
                                        <div class="font-mono text-[10px] text-deep-moss">
                                            {{ number_format($p->latitude, 5) }}, {{ number_format($p->longitude, 5) }}
                                        </div>
                                        <div class="text-[10px] mt-0.5 font-mono {{ $p->is_dalam_radius ? 'text-emerald-700' : 'text-amber-700' }}">
                                            Jarak: {{ $p->jarak_meter }}m ({{ $p->is_dalam_radius ? 'Valid Geofence' : 'Di Luar Radius' }})
                                        </div>
                                    @else
                                        <span class="text-olive-char text-[10px] font-mono italic">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-mono text-[11px] text-olive-char">
                                    {{ $p?->dicatat_pada ? $p->dicatat_pada->format('H:i:s d/m/y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('dosen.presensi.update', [$kelas->id, $pertemuan->id, $mhs->id]) }}" class="inline-flex items-center gap-1">
                                        @csrf
                                        <select name="status" class="text-[10px] p-1 rounded border border-warm-mist bg-paper-white" onchange="this.form.submit()">
                                            <option value="hadir" {{ ($p?->status->value ?? '') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="izin" {{ ($p?->status->value ?? '') === 'izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="sakit" {{ ($p?->status->value ?? '') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="alpha" {{ ($p?->status->value ?? 'alpha') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.dashboard>

