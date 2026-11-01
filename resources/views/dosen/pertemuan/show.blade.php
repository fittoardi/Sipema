<x-layouts.dashboard title="Pertemuan {{ $pertemuan->pertemuan_ke }} - {{ $kelas->mataKuliah->nama }}">
    <div class="space-y-6">

        {{-- Header Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <a href="{{ route('dosen.pertemuan.index', $kelas->id) }}" class="font-mono text-xs text-signal-blue hover:underline">&larr; Kembali ke Timeline Sesi</a>
                    <span class="text-olive-char text-xs">/</span>
                    <span class="font-mono text-xs font-bold text-deep-moss">session_{{ $pertemuan->pertemuan_ke }}.detail</span>
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
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">Pertemuan Ke-{{ $pertemuan->pertemuan_ke }}</span>
                        <span class="tag-pill {{ $pertemuan->mode->value === 'online' ? 'tag-pill-purple' : 'tag-pill-blue' }} text-[10px] uppercase">{{ $pertemuan->mode->value ?? $pertemuan->mode }}</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-deep-moss font-sans">
                        {{ $pertemuan->judul }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-olive-char font-mono">
                        <span class="font-bold text-deep-moss">{{ $kelas->mataKuliah->nama }} ({{ $kelas->nama_kelas }})</span>
                        <span>&bull;</span>
                        <span>Tanggal: {{ $pertemuan->tanggal ? \Carbon\Carbon::parse($pertemuan->tanggal)->format('d F Y') : '-' }}</span>
                        <span>&bull;</span>
                        <span>Lokasi: {{ $pertemuan->ruangan->nama ?? ($pertemuan->mode->value === 'online' ? 'Online Meeting' : 'Ruang Kelas') }}</span>
                    </div>
                </div>

                {{-- Presensi Fast Toggle Button --}}
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('dosen.pertemuan.toggle-presensi', [$kelas->id, $pertemuan->id]) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-[4px] font-mono text-xs font-bold flex items-center gap-2 transition
                            {{ $pertemuan->is_presensi_open ? 'bg-rose-600 hover:bg-rose-700 text-white shadow-sm' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm' }}">
                            <span class="w-2 h-2 rounded-full {{ $pertemuan->is_presensi_open ? 'bg-white animate-pulse' : 'bg-white/50' }}"></span>
                            {{ $pertemuan->is_presensi_open ? 'Tutup Sesi Presensi' : 'Buka Sesi Presensi' }}
                        </button>
                    </form>

                    <a href="{{ route('dosen.presensi.index', [$kelas->id, $pertemuan->id]) }}" class="btn-amber text-xs py-2 px-3">
                        Galeri Presensi ({{ $pertemuan->presensis->count() }} Mhs)
                    </a>
                </div>
            </div>
        </div>

        {{-- Presensi Active Status Banner --}}
        @if($pertemuan->is_presensi_open)
            <div class="p-4 rounded-[4px] bg-emerald-50 border border-emerald-300 text-emerald-950 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-200 border border-emerald-400 flex items-center justify-center font-mono font-bold text-emerald-800 text-sm animate-pulse">
                        LIVE
                    </div>
                    <div>
                        <h4 class="font-bold text-xs font-mono uppercase tracking-wider text-emerald-900">Sesi Presensi Mandiri Sedang Terbuka</h4>
                        <p class="text-xs text-emerald-800 mt-0.5">
                            Mahasiswa dapat melakukan presensi wajah & verifikasi GPS melalui akun mereka.
                        </p>
                    </div>
                </div>

                @if($pertemuan->kode_presensi)
                    <div class="bg-paper-white px-4 py-2 rounded border border-emerald-300 text-center">
                        <div class="text-[10px] font-mono text-olive-char uppercase">Kode PIN Presensi:</div>
                        <div class="text-xl font-black font-mono tracking-widest text-emerald-700">{{ $pertemuan->kode_presensi }}</div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Grid: BAP & Settings, Materi, Tugas --}}
        <div class="grid gap-6 grid-cols-1 lg:grid-cols-3">

            {{-- Column 1: BAP & Session Form --}}
            <div class="window-card p-6 bg-cream-paper border border-warm-mist space-y-4">
                <div class="flex items-center justify-between border-b border-warm-mist pb-2">
                    <h3 class="font-bold text-sm text-deep-moss font-sans">Berita Acara & Pengaturan Sesi</h3>
                    <span class="tag-pill tag-pill-stone text-[10px]">BAP</span>
                </div>

                <form method="POST" action="{{ route('dosen.pertemuan.update', [$kelas->id, $pertemuan->id]) }}" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[11px] font-bold text-olive-char font-mono mb-1">Judul Sesi</label>
                        <input type="text" name="judul" value="{{ old('judul', $pertemuan->judul) }}" class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow" required>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-olive-char font-mono mb-1">Mode Perkuliahan</label>
                            <select name="mode" class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow">
                                <option value="offline" {{ $pertemuan->mode->value === 'offline' ? 'selected' : '' }}>Offline (Tatap Muka)</option>
                                <option value="online" {{ $pertemuan->mode->value === 'online' ? 'selected' : '' }}>Online (Virtual)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-olive-char font-mono mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $pertemuan->tanggal ? \Carbon\Carbon::parse($pertemuan->tanggal)->format('Y-m-d') : '') }}" class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-olive-char font-mono mb-1">Ruangan (Jika Offline)</label>
                        <select name="ruangan_id" class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow">
                            <option value="">-- Pilih Ruangan Kampus --</option>
                            @foreach($ruangans as $r)
                                <option value="{{ $r->id }}" {{ $pertemuan->ruangan_id == $r->id ? 'selected' : '' }}>{{ $r->nama }} ({{ $r->kode }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-olive-char font-mono mb-1">Tautan Virtual Meeting (Jika Online)</label>
                        <input type="url" name="tautan_meeting" value="{{ old('tautan_meeting', $pertemuan->tautan_meeting) }}" placeholder="https://meet.google.com/..." class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-olive-char font-mono mb-1">Berita Acara Perkuliahan (BAP)</label>
                        <textarea name="berita_acara" rows="4" placeholder="Catatan materi yang diajarkan, kendala, atau capaian pembelajaran..." class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow">{{ old('berita_acara', $pertemuan->berita_acara) }}</textarea>
                    </div>

                    <div class="flex items-center gap-4 text-xs font-mono">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="wajib_selfie" value="1" {{ $pertemuan->wajib_selfie ? 'checked' : '' }} class="rounded border-warm-mist text-signal-blue">
                            <span>Wajib Selfie</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="wajib_gps" value="1" {{ $pertemuan->wajib_gps ? 'checked' : '' }} class="rounded border-warm-mist text-signal-blue">
                            <span>Wajib GPS</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-amber text-xs py-2 px-4 w-full">
                        Simpan Perubahan Sesi & BAP
                    </button>
                </form>
            </div>

            {{-- Column 2: Materi Pembelajaran --}}
            <div class="window-card p-6 bg-cream-paper border border-warm-mist space-y-4">
                <div class="flex items-center justify-between border-b border-warm-mist pb-2">
                    <h3 class="font-bold text-sm text-deep-moss font-sans">Materi Pembelajaran</h3>
                    <span class="tag-pill tag-pill-blue text-[10px]">{{ $pertemuan->materis->count() }} Berkas</span>
                </div>

                {{-- Existing Materials --}}
                <div class="space-y-2">
                    @forelse($pertemuan->materis as $m)
                        <div class="p-2.5 rounded-[4px] bg-paper-white border border-warm-mist flex items-center justify-between gap-2 text-xs">
                            <div class="overflow-hidden">
                                <div class="font-bold text-deep-moss truncate">{{ $m->judul }}</div>
                                <div class="text-[10px] font-mono text-olive-char capitalize">Tipe: {{ $m->tipe }}</div>
                            </div>

                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                @if($m->tipe === 'url' && $m->url)
                                    <a href="{{ $m->url }}" target="_blank" class="text-signal-blue text-[10px] font-mono hover:underline">Buka Tautan</a>
                                @endif
                                <form method="POST" action="{{ route('dosen.pertemuan.materi.destroy', [$kelas->id, $pertemuan->id, $m->id]) }}" onsubmit="return confirm('Hapus materi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-[10px] font-mono">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-olive-char font-mono text-xs">
                            Belum ada materi pembelajaran yang diunggah.
                        </div>
                    @endforelse
                </div>

                {{-- Add Material Form --}}
                <div class="pt-3 border-t border-warm-mist" x-data="{ tipeMateri: 'file' }">
                    <h4 class="text-xs font-bold font-mono text-deep-moss mb-2">+ Tambah Materi</h4>
                    <form method="POST" action="{{ route('dosen.pertemuan.materi.store', [$kelas->id, $pertemuan->id]) }}" enctype="multipart/form-data" class="space-y-2.5">
                        @csrf
                        <div>
                            <input type="text" name="judul" placeholder="Judul materi (misal: Slide PPT Bab 1)" class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow" required>
                        </div>
                        <div>
                            <select name="tipe" x-model="tipeMateri" class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white">
                                <option value="file">Unggah File (PDF/PPT/DOC)</option>
                                <option value="url">Tautan Eksternal (URL / YouTube)</option>
                                <option value="teks">Teks / Catatan Kuliah</option>
                            </select>
                        </div>

                        <div x-show="tipeMateri === 'file'">
                            <input type="file" name="file" class="w-full text-xs p-1.5 rounded-[4px] border border-warm-mist bg-paper-white">
                        </div>

                        <div x-show="tipeMateri === 'url'">
                            <input type="url" name="url" placeholder="https://..." class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white">
                        </div>

                        <div x-show="tipeMateri === 'teks'">
                            <textarea name="konten" rows="3" placeholder="Tuliskan catatan materi di sini..." class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white"></textarea>
                        </div>

                        <button type="submit" class="btn-mist text-xs py-1.5 px-3 w-full">
                            Unggah Materi
                        </button>
                    </form>
                </div>
            </div>

            {{-- Column 3: Penugasan (Assignments) --}}
            <div class="window-card p-6 bg-cream-paper border border-warm-mist space-y-4">
                <div class="flex items-center justify-between border-b border-warm-mist pb-2">
                    <h3 class="font-bold text-sm text-deep-moss font-sans">Penugasan Mahasiswa</h3>
                    <span class="tag-pill tag-pill-amber text-[10px]">{{ $pertemuan->tugas->count() }} Tugas</span>
                </div>

                {{-- Existing Assignments --}}
                <div class="space-y-2">
                    @forelse($pertemuan->tugas as $t)
                        <div class="p-2.5 rounded-[4px] bg-paper-white border border-warm-mist space-y-1 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-deep-moss">{{ $t->judul }}</span>
                                <span class="tag-pill tag-pill-stone text-[9px]">{{ $t->pengumpulans->count() }} Dikumpulkan</span>
                            </div>
                            <div class="text-[10px] text-olive-char font-mono">
                                Deadline: {{ \Carbon\Carbon::parse($t->batas_waktu)->format('d/m/Y H:i') }}
                            </div>
                            <p class="text-[11px] text-deep-moss line-clamp-2 mt-1">{{ $t->instruksi }}</p>
                        </div>
                    @empty
                        <div class="text-center py-4 text-olive-char font-mono text-xs">
                            Belum ada tugas pada sesi ini.
                        </div>
                    @endforelse
                </div>

                {{-- Add Assignment Form --}}
                <div class="pt-3 border-t border-warm-mist">
                    <h4 class="text-xs font-bold font-mono text-deep-moss mb-2">+ Buat Penugasan Baru</h4>
                    <form method="POST" action="{{ route('dosen.pertemuan.tugas.store', [$kelas->id, $pertemuan->id]) }}" enctype="multipart/form-data" class="space-y-2.5">
                        @csrf
                        <div>
                            <input type="text" name="judul" placeholder="Judul tugas (misal: Tugas 1 - Studi Kasus)" class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow" required>
                        </div>
                        <div>
                            <textarea name="instruksi" rows="3" placeholder="Instruksi pengerjaan tugas..." class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white focus:outline-none focus:border-amber-glow" required></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-olive-char font-mono mb-1">Batas Waktu Pengumpulan</label>
                            <input type="datetime-local" name="batas_waktu" class="w-full text-xs p-2 rounded-[4px] border border-warm-mist bg-paper-white" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-olive-char font-mono mb-1">Lampiran Soal (Opsional)</label>
                            <input type="file" name="lampiran" class="w-full text-xs p-1.5 rounded-[4px] border border-warm-mist bg-paper-white">
                        </div>
                        <button type="submit" class="btn-amber text-xs py-1.5 px-3 w-full">
                            Simpan & Terbitkan Tugas
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</x-layouts.dashboard>

