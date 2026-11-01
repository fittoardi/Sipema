<x-layouts.dashboard title="Presensi Perkuliahan Sesi {{ $pertemuan->pertemuan_ke }}">
    <div class="space-y-6" x-data="presensiApp()">

        {{-- Header Card --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="font-mono text-xs text-signal-blue hover:underline">&larr; Kembali ke Dashboard</a>
                    <span class="text-olive-char text-xs">/</span>
                    <span class="font-mono text-xs font-bold text-deep-moss">live_attendance.camera</span>
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
                        <span>Dosen: {{ $kelas->dosen->nama ?? '-' }}</span>
                        <span>&bull;</span>
                        <span>Ruangan: {{ $pertemuan->ruangan->nama ?? 'Online' }}</span>
                    </div>
                </div>

                <div>
                    @if($presensi)
                        <span class="px-3 py-1.5 rounded-[4px] bg-emerald-100 text-emerald-800 border border-emerald-300 font-mono text-xs font-bold uppercase">
                            Presensi Tercatat ({{ $presensi->status->label() }})
                        </span>
                    @elseif($pertemuan->is_presensi_open)
                        <span class="px-3 py-1.5 rounded-[4px] bg-emerald-600 text-white font-mono text-xs font-bold uppercase animate-pulse">
                            Presensi Dibuka
                        </span>
                    @else
                        <span class="px-3 py-1.5 rounded-[4px] bg-rose-100 text-rose-800 border border-rose-300 font-mono text-xs font-bold uppercase">
                            Presensi Ditutup
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if($presensi)
            {{-- Receipt / Already Attended Card --}}
            <div class="window-card p-6 bg-cream-paper border border-warm-mist max-w-2xl mx-auto">
                <div class="flex items-center gap-3 border-b border-warm-mist pb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-300 flex items-center justify-center text-emerald-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-deep-moss">Bukti Kehadiran Perkuliahan</h3>
                        <p class="text-xs text-olive-char font-mono">Dicatat pada: {{ $presensi->dicatat_pada ? $presensi->dicatat_pada->format('d/m/Y H:i:s') : '-' }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                    <div>
                        <span class="text-olive-char">Status Kehadiran:</span>
                        <div class="font-bold text-emerald-700 uppercase mt-0.5">{{ $presensi->status->label() }}</div>
                    </div>
                    <div>
                        <span class="text-olive-char">Metode Pencatatan:</span>
                        <div class="font-bold text-deep-moss capitalize mt-0.5">{{ $presensi->metode->value ?? 'mandiri' }}</div>
                    </div>
                    <div>
                        <span class="text-olive-char">Jarak ke Ruangan:</span>
                        <div class="font-bold text-deep-moss mt-0.5">{{ $presensi->jarak_meter ?? 0 }} meter ({{ $presensi->is_dalam_radius ? 'Dalam Radius' : 'Luar Radius' }})</div>
                    </div>
                    <div>
                        <span class="text-olive-char">Geolokasi:</span>
                        <div class="font-bold text-deep-moss mt-0.5">{{ $presensi->latitude ? number_format($presensi->latitude, 5).', '.number_format($presensi->longitude, 5) : '-' }}</div>
                    </div>
                </div>

                @if($presensi->foto_selfie_path)
                    <div class="mt-4 pt-4 border-t border-warm-mist">
                        <span class="block text-xs font-mono text-olive-char mb-2">Foto Bukti Selfie Wajah:</span>
                        <img src="{{ route('foto-presensi.show', $presensi->id) }}" alt="Foto Selfie" class="w-48 h-36 object-cover rounded border border-warm-mist shadow-2xs">
                    </div>
                @endif
            </div>

        @elseif(! $pertemuan->is_presensi_open)
            {{-- Closed Alert --}}
            <div class="window-card p-8 bg-cream-paper border border-warm-mist text-center max-w-xl mx-auto">
                <div class="w-12 h-12 rounded-full bg-rose-100 border border-rose-300 text-rose-700 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-11a4 4 0 00-8 0v4h8V4z"/></svg>
                </div>
                <h3 class="text-base font-bold text-deep-moss">Presensi Belum Dibuka</h3>
                <p class="text-xs text-olive-char mt-1">
                    Dosen pengampu belum membuka sesi presensi untuk pertemuan ini. Silakan periksa kembali saat perkuliahan berlangsung.
                </p>
            </div>

        @else
            {{-- Interactive WebRTC Camera + GPS Viewfinder Form --}}
            <div class="grid gap-6 grid-cols-1 md:grid-cols-2">

                {{-- Camera Viewfinder Box --}}
                <div class="window-card p-6 bg-cream-paper border border-warm-mist space-y-4">
                    <div class="flex items-center justify-between border-b border-warm-mist pb-2">
                        <h3 class="font-bold text-sm text-deep-moss font-sans">1. Pengambilan Foto Selfie Langsung</h3>
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">WebRTC Camera</span>
                    </div>

                    <div class="relative bg-black rounded overflow-hidden aspect-video flex items-center justify-center">
                        {{-- Video Element --}}
                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover" x-show="!photoCaptured"></video>

                        {{-- Captured Preview --}}
                        <img :src="photoData" x-show="photoCaptured" class="w-full h-full object-cover">

                        {{-- Camera Not Started Placeholder --}}
                        <div x-show="!cameraActive && !photoCaptured" class="text-center p-4 text-white/70">
                            <p class="text-xs font-mono mb-2">Kamera belum diaktifkan.</p>
                            <button type="button" @click="startCamera()" class="btn-amber text-xs py-1.5 px-3">
                                Aktifkan Kamera Wajah
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 justify-center">
                        <button type="button" x-show="cameraActive && !photoCaptured" @click="takePhoto()" class="btn-amber text-xs py-2 px-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Ambil Foto Selfie
                        </button>

                        <button type="button" x-show="photoCaptured" @click="retakePhoto()" class="btn-mist text-xs py-2 px-4">
                            Ulangi Foto
                        </button>
                    </div>

                    <canvas x-ref="canvas" width="640" height="480" class="hidden"></canvas>
                </div>

                {{-- GPS Location & Form Submission --}}
                <div class="window-card p-6 bg-cream-paper border border-warm-mist space-y-4">
                    <div class="flex items-center justify-between border-b border-warm-mist pb-2">
                        <h3 class="font-bold text-sm text-deep-moss font-sans">2. Verifikasi Lokasi & Kode</h3>
                        <span class="tag-pill tag-pill-blue text-[10px] font-mono">GPS Geofence</span>
                    </div>

                    {{-- GPS Status Card --}}
                    <div class="p-3 rounded-[4px] border border-warm-mist bg-paper-white space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-mono text-olive-char">Status GPS:</span>
                            <span class="font-mono font-bold" :class="gpsReady ? 'text-emerald-700' : 'text-amber-700'" x-text="gpsStatus"></span>
                        </div>
                        <div class="text-[11px] font-mono text-deep-moss" x-show="gpsReady">
                            Koordinat: <span x-text="lat"></span>, <span x-text="lon"></span>
                            <div class="text-[10px] text-olive-char mt-0.5">Akurasi: &plusmn;<span x-text="accuracy"></span> meter</div>
                        </div>
                        <button type="button" @click="acquireGps()" class="btn-mist text-[11px] py-1 px-2.5 w-full mt-1">
                            Deteksi Ulang Lokasi GPS
                        </button>
                    </div>

                    <form method="POST" action="{{ route('mahasiswa.presensi.store', $pertemuan->id) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="latitude" :value="lat">
                        <input type="hidden" name="longitude" :value="lon">
                        <input type="hidden" name="akurasi_meter" :value="accuracy">
                        <input type="hidden" name="foto_selfie" :value="photoData">

                        @if($pertemuan->kode_presensi)
                            <div>
                                <label class="block text-xs font-bold text-deep-moss font-mono mb-1">
                                    Kode PIN Presensi: <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="kode_presensi" placeholder="Masukkan 6-karakter PIN dari dosen" class="w-full text-xs p-2.5 rounded-[4px] border border-warm-mist bg-paper-white font-mono uppercase tracking-widest focus:outline-none focus:border-amber-glow" required>
                            </div>
                        @endif

                        <button type="submit" class="btn-amber text-xs py-2.5 px-4 w-full flex items-center justify-center gap-2 font-bold" :disabled="!photoCaptured && {{ $pertemuan->wajib_selfie ? 'true' : 'false' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Kirim Presensi Sekarang
                        </button>
                    </form>
                </div>

            </div>
        @endif

    </div>

    {{-- Alpine.js WebRTC Controller Script --}}
    <script>
        function presensiApp() {
            return {
                cameraActive: false,
                photoCaptured: false,
                photoData: '',
                stream: null,
                lat: null,
                lon: null,
                accuracy: null,
                gpsReady: false,
                gpsStatus: 'Mendeteksi lokasi...',

                init() {
                    this.acquireGps();
                },

                async startCamera() {
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                            audio: false
                        });
                        this.$refs.video.srcObject = this.stream;
                        this.cameraActive = true;
                    } catch (err) {
                        alert('Tidak dapat mengakses kamera: ' + err.message + '. Mohon izinkan izin kamera di peramban Anda.');
                    }
                },

                takePhoto() {
                    const video = this.$refs.video;
                    const canvas = this.$refs.canvas;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    this.photoData = canvas.toDataURL('image/jpeg', 0.8);
                    this.photoCaptured = true;

                    // Stop video stream to release camera
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.cameraActive = false;
                    }
                },

                retakePhoto() {
                    this.photoCaptured = false;
                    this.photoData = '';
                    this.startCamera();
                },

                acquireGps() {
                    if (!navigator.geolocation) {
                        this.gpsStatus = 'Browser tidak mendukung GPS';
                        return;
                    }

                    this.gpsStatus = 'Mengunci sinyal satelit GPS...';
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            this.lat = pos.coords.latitude;
                            this.lon = pos.coords.longitude;
                            this.accuracy = Math.round(pos.coords.accuracy);
                            this.gpsReady = true;
                            this.gpsStatus = 'Lokasi Terkunci (Akurat)';
                        },
                        (err) => {
                            this.gpsStatus = 'Gagal: ' + err.message;
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                }
            };
        }
    </script>
</x-layouts.dashboard>

