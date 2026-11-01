# Prompt — Membangun Sistem Akademik & E-Learning Hybrid Mahasiswa (SIELMA) dengan Laravel

> Cara pakai: salin **Master Prompt** ke AI coding agent (mis. Claude Code, Antigravity, Cursor) sebagai konteks awal, lalu jalankan **Prompt per Fase** satu per satu. Review hasil tiap fase (jalankan test, cek acceptance, commit) sebelum lanjut. Ganti semua `[PLACEHOLDER]`. Dokumen pendamping: `01-implementation-plan.md`.

---

## 1. Master Prompt (Konteks Proyek)

```text
Kamu adalah senior Laravel engineer yang berpengalaman membangun sistem informasi akademik dan learning management system (LMS) terintegrasi di Indonesia.

## Proyek
Bangun SIELMA (Sistem Akademik & E-Learning Hybrid Mahasiswa) untuk [Universitas Merdeka / NAMA_KAMPUS].
- Model: aplikasi web internal satu institusi (Laravel monolith, bukan SaaS multi-tenant)
- Fungsi inti:
  1. Modul Akademik Inti: Master Data (Prodi, Ruangan dengan koordinat GPS & radius, Tahun Akademik & periode KRS, Dosen, Mahasiswa dengan plotting Dosen Wali, Mata Kuliah, Kelas Perkuliahan offline/online/hybrid).
  2. Modul KRS Online & Perwalian: Pengisian KRS oleh mahasiswa, validasi batas SKS, kuota kelas dengan lockForUpdate(), deteksi bentrok jadwal anti-collision, peninjauan & persetujuan KRS oleh Dosen Wali (PA), serta cetak PDF KRS resmi.
  3. Manajemen Pertemuan (Sesi 1–16) & BAP: Mendukung mode Tatap Muka (Offline di ruangan kampus) maupun Daring (Online via tautan Zoom/GMeet), dilengkapi Berita Acara Perkuliahan (BAP).
  4. Materi Ajar & Diskusi: Unggah dokumen PDF/PPTX, link luar, dan video per sesi + pelacakan progres selesai belajar mahasiswa + forum diskusi komentar bertingkat.
  5. Presensi Berbasis Foto Selfie Live & GPS Geofencing:
     - Kamera langsung di browser via WebRTC (navigator.mediaDevices.getUserMedia) — tanpa fitur unggah galeri foto lama untuk status Hadir.
     - Kompresi canvas client-side (maks 640x480 px, JPEG 75%, watermark waktu & NIM).
     - Pembacaan koordinat GPS presisi tinggi (navigator.geolocation) & perhitungan jarak server-side dengan rumus Haversine terhadap koordinat ruangan.
     - Validasi Geofencing: Pertemuan offline wajib berada dalam radius ruangan (mis. <= 100m); pertemuan online mencatat titik koordinat & foto selfie.
     - Opsional Kode PIN 6 digit sesi untuk kelas offline.
     - Galeri verifikasi bukti presensi untuk dosen (thumbnail foto, badge jarak meter, pin lokasi Google Maps, dan tombol koreksi status) serta input presensi manual oleh dosen.
     - Penyimpanan foto selfie aman di private disk (storage/app/private/presensi/) dengan proteksi Policy.
  6. Tugas (Assignment) & Penilaian: Deadline tugas, unggah jawaban submission, penilaian dosen (0–100) & feedback, serta tombol sinkronisasi rata-rata nilai tugas ke rekap nilai akhir.
  7. Pengelolaan Nilai Akhir: Input nilai Kuis, Tugas, UTS, UAS; kalkulasi otomatis Nilai Akhir, Grade (A–E), dan Status Kelulusan (Lulus min. C); command nilai:recalculate.
  8. Dashboard per Role & Laporan: Dashboard interaktif dengan grafik Chart.js untuk Admin, Dosen, dan Mahasiswa; laporan akademik (KRS, Presensi, Nilai) dengan filter & ekspor PDF/Excel.
- Pengguna: Admin, Dosen (sebagai Dosen Pengampu & Dosen Wali), Mahasiswa
- Bahasa antarmuka: Bahasa Indonesia. Zona waktu: Asia/Jakarta
- Skala nilai: 0–100
- Bobot komponen default: Kuis [10], Tugas [20], UTS [30], UAS [40] (total 100, dibaca dari database)
- Skala grade default: A >= [80], B >= [70], C >= [60], D >= [50], E >= 0 (Lulus min. C, dibaca dari database)

## Arsitektur: LARAVEL 13 MONOLITH
Satu aplikasi Laravel monolith: autentikasi, otorisasi role, modul akademik, LMS, presensi kamera/GPS, dan pelaporan dalam satu codebase.

Stack wajib:
- Laravel 13, PHP 8.3+
- Frontend: Blade + Tailwind CSS (Vite) + Alpine.js (viewfinder kamera live, kompresi canvas, pembacaan GPS). Tidak menggunakan SPA terpisah.
- Auth: Laravel Breeze (stack Blade): login, logout, reset password. Registrasi publik DINONAKTIFKAN (/register 404).
- Otorisasi: kolom users.role (PHP Backed Enum) + Middleware role + Gate + Policy (tanpa dependensi paket permission eksternal).
- Database: MySQL / MariaDB (InnoDB, lockForUpdate() pada transaksi kuota KRS).
- File Storage: Storage::disk('private') untuk foto selfie presensi, materi, dan submission tugas, diakses via streaming controller ber-Policy.
- Peta & Jarak: Rumus Haversine server-side (GeoDistanceCalculator) + link Google Maps / Leaflet.
- Grafik: Chart.js
- Export: maatwebsite/excel (Excel) dan barryvdh/laravel-dompdf (PDF Lembar KRS, Daftar Hadir, Laporan Nilai)
- Testing: Pest (atau PHPUnit) + Laravel Pint untuk code formatting

## Tiga Peran Utama
1. ADMIN
   - Kelola Master Data: Program Studi, Ruangan (+ input titik GPS lat/lng & radius meter), Tahun Akademik (+ toggle buka/tutup KRS), Dosen, Mahasiswa (+ plotting Dosen Wali), Mata Kuliah, Kelas Perkuliahan (offline/online/hybrid).
   - Override KRS dan manajemen peserta kelas.
   - Atur bobot nilai dan skala grade.
   - Dashboard global institusi, laporan seluruh data akademik, ekspor PDF/Excel.
2. DOSEN
   - Sebagai Dosen Wali: Tinjau pengajuan KRS mahasiswa bimbingan, beri catatan, lalu setujui (approve) atau minta revisi.
   - Sebagai Dosen Pengampu:
     - Kelola sesi perkuliahan 1–16 (mode offline/online) dan isi Berita Acara Perkuliahan (BAP).
     - Unggah materi pembelajaran dan pantau progres selesai mahasiswa.
     - Buka/tutup sesi presensi (atur wajib selfie, wajib GPS, kode PIN).
     - Galeri verifikasi presensi: cek thumbnail foto selfie, jarak GPS dari ruangan, link peta, dan koreksi/input manual status presensi.
     - Buat tugas berbatas waktu, periksa jawaban submission, beri skor & feedback.
     - Sinkronkan rata-rata nilai tugas LMS ke kolom Tugas pada rekap nilai akhir.
     - Input nilai Kuis/UTS/UAS massal, cetak daftar hadir, dan ekspor laporan nilai kelas (PDF & Excel).
3. MAHASISWA
   - Isi KRS online pada periode aktif: pilih kelas, cek validasi SKS & anti-bentrok jadwal, ajukan ke Dosen Wali, dan cetak PDF KRS setelah disetujui.
   - Akses jadwal kuliah mingguan (offline/online), materi pertemuan, dan tandai selesai belajar.
   - Presensi mandiri: ambil foto selfie langsung dari kamera depan dan kirim lokasi GPS presisi tinggi ke server.
   - Unggah jawaban tugas sebelum deadline, pantau nilai & feedback, serta berdiskusi di forum pertemuan.
   - Lihat rekap nilai akhir, perolehan grade, dan status kelulusan (KHS).

## Aturan Domain & Basis Data
- Tabel (21 tabel, snake_case):
  users, program_studi, ruangan, tahun_akademik, dosen, mahasiswa, mata_kuliah, kelas,
  krs, krs_detail, kelas_mahasiswa, pertemuan, materi, progres_materi, presensi,
  tugas, pengumpulan_tugas, diskusi, nilai, pengaturan_nilai, skala_grade.
- Relasi & Kunci Penting:
  - ruangan: id, kode, nama, gedung, kapasitas, tipe, latitude (decimal 10,7), longitude (decimal 10,7), radius_meter (default 100).
  - krs: mahasiswa_id, tahun_akademik_id, total_sks, status (draft/diajukan/revisi/disetujui), catatan_wali.
  - krs_detail: krs_id, kelas_id (unique krs_id + kelas_id).
  - kelas: mata_kuliah_id, dosen_id, tahun_akademik_id, ruangan_id, nama_kelas, tipe_kelas (offline/online/hybrid), hari, jam_mulai, jam_selesai, kapasitas.
  - pertemuan: kelas_id, pertemuan_ke, judul, mode (offline/online), ruangan_id, tautan_meeting, tanggal, berita_acara, is_presensi_open, wajib_selfie, wajib_gps, kode_presensi.
  - presensi: pertemuan_id, mahasiswa_id, status (hadir/izin/sakit/alpha), foto_selfie_path, latitude, longitude, akurasi_meter, jarak_meter, is_dalam_radius, metode (mandiri/manual_dosen).
- Aturan Validasi KRS:
  - Batas SKS: total_sks + sks_mk <= maks_sks_default (24 SKS).
  - Kuota kelas: jumlah peminat (diajukan + disetujui) < kapasitas kelas (DB lockForUpdate).
  - Anti-Bentrok Jadwal: hari sama AND jam_mulai_A < jam_selesai_B AND jam_selesai_A > jam_mulai_B.
  - Sinkronisasi: Saat KRS disetujui, daftarkan mahasiswa ke kelas_mahasiswa dan buat baris awal di tabel nilai.
- Aturan Presensi Selfie & GPS:
  - Perhitungan jarak di server memakai rumus Haversine (GeoDistanceCalculator) dengan jari-jari bumi R = 6.371.000 m.
  - Pertemuan offline: jarak <= ruangan.radius_meter (toleransi min(akurasi, 50) m). Di luar radius ditolak untuk status Hadir.
  - Pertemuan online: is_dalam_radius = true, tetap mencatat foto selfie & koordinat GPS.
  - Foto selfie disimpan di storage/app/private/presensi/{pertemuan_id}/{mahasiswa_id}_{timestamp}.jpg.
- Aturan Nilai:
  - Nilai Akhir = (kuis*bobot_kuis + tugas*bobot_tugas + uts*bobot_uts + uas*bobot_uas) / 100.
  - Nilai akhir hanya lengkap jika semua komponen terisi; pembulatan 2 desimal half-up; snapshot tersimpan dan dapat dihitung ulang dengan command nilai:recalculate.

## Aturan Teknis (Wajib)
1. Seluruh logika bisnis rumit dipisahkan ke Services (KrsService, JadwalConflictDetector, GeoDistanceCalculator, PresensiService, NilaiCalculator, SinkronisasiTugasService, DashboardService, LaporanService).
2. Otorisasi berlapis: Middleware role -> Policy per resource (KrsPolicy, KelasPolicy, PertemuanPolicy, PresensiPolicy, TugasPolicy, NilaiPolicy). Cegah IDOR dan lindungi akses streaming foto selfie.
3. Hak Akses File Aman (UU PDP): Foto selfie mahasiswa, file materi, dan tugas tidak boleh disimpan di direktori public. Gunakan private disk dan streaming controller ber-Policy.
4. Kamera & GPS Wajib Aman: Antarmuka kamera live tidak menyediakan opsi unggah dari galeri untuk presensi hadir. Seluruh endpoint geolokasi dan kamera memerlukan konteks aman (HTTPS).
5. Query Database Efisien: Hindari N+1 query dengan eager loading (with()), tambahkan index pada kolom foreign key dan filter laporan, serta gunakan pagination.
6. Validasi Input: Selalu gunakan Form Request Class dengan aturan validasi ketat.
7. Kode Bersih & Teruji: Jalankan vendor/bin/pint dan php artisan test --compact sebelum menyelesaikan setiap tahap.
```

---

## 2. Prompt per Fase

### Fase 1 — Persiapan & Stack Setup

```text
Fase 1: Persiapan & Stack Setup.
1. Verifikasi lingkungan: PHP 8.3+, Composer, Node.js, dan database MySQL/MariaDB.
2. Pastikan file .env terkonfigurasi dengan:
   APP_NAME="SIELMA", APP_LOCALE=id, APP_FALLBACK_LOCALE=en, APP_TIMEZONE=Asia/Jakarta.
3. Pastikan Tailwind CSS dan Alpine.js siap digunakan via Vite.
4. Pastikan paket barryvdh/laravel-dompdf dan maatwebsite/excel terpasang dan kompatibel dengan Laravel 13.
5. Konfigurasi disk 'private' di config/filesystems.php untuk menampung arsip foto selfie presensi dan materi/tugas.
6. Siapkan file bahasa Indonesia (lang/id/) untuk validasi, auth, dan pagination.
7. Pastikan pengujian awal dengan Pest/PHPUnit dan Laravel Pint berjalan sukses.
Berhenti setelah selesai dan beri ringkasan + cara verifikasi.
```

### Fase 2 — Desain Database, Schema & Migration (21 Tabel)

```text
Fase 2: Desain database & pembuatan migrasi lengkap.
1. Buat migration untuk seluruh 21 tabel sesuai ERD pada Implementation Plan dengan urutan dependensi foreign key yang tepat:
   - users (kolom role: admin, dosen, mahasiswa)
   - program_studi (kode, nama)
   - ruangan (kode, nama, gedung, kapasitas, tipe, latitude decimal(10,7), longitude decimal(10,7), radius_meter smallint default 100)
   - tahun_akademik (tahun, semester, is_active, is_krs_open, krs_mulai, krs_selesai, maks_sks_default)
   - dosen (user_id, nidn, nama, program_studi_id)
   - mahasiswa (user_id, nim, nama, program_studi_id, dosen_wali_id, angkatan)
   - mata_kuliah (kode, nama, sks, semester_paket, program_studi_id)
   - kelas (mata_kuliah_id, dosen_id, tahun_akademik_id, ruangan_id nullable, nama_kelas, tipe_kelas [offline/online/hybrid], hari, jam_mulai, jam_selesai, kapasitas)
   - krs (mahasiswa_id, tahun_akademik_id, total_sks, status [draft/diajukan/revisi/disetujui], catatan_wali, disetujui_oleh, disetujui_pada)
   - krs_detail (krs_id, kelas_id) -> unique(krs_id, kelas_id)
   - kelas_mahasiswa (kelas_id, mahasiswa_id, krs_id nullable) -> unique(kelas_id, mahasiswa_id)
   - pertemuan (kelas_id, pertemuan_ke, judul, mode [offline/online], ruangan_id nullable, tautan_meeting nullable, tanggal, berita_acara, is_presensi_open, wajib_selfie, wajib_gps, kode_presensi nullable)
   - materi (pertemuan_id, judul, tipe [file/link/video], konten_atau_url, file_path, urutan)
   - progres_materi (materi_id, mahasiswa_id, selesai_pada)
   - presensi (pertemuan_id, mahasiswa_id, status [hadir/izin/sakit/alpha], foto_selfie_path nullable, latitude nullable, longitude nullable, akurasi_meter nullable, jarak_meter nullable, is_dalam_radius bool, metode [mandiri/manual_dosen], ip_address, user_agent, keterangan)
   - tugas (pertemuan_id, judul, instruksi, lampiran_path, batas_waktu, allow_late)
   - pengumpulan_tugas (tugas_id, mahasiswa_id, file_path, catatan_mahasiswa, dikumpulkan_pada, is_terlambat, nilai nullable, umpan_balik nullable)
   - diskusi (pertemuan_id, user_id, parent_id nullable, isi)
   - nilai (kelas_mahasiswa_id, kuis, tugas, uts, uas, nilai_akhir, grade, status, updated_by)
   - pengaturan_nilai (komponen [kuis/tugas/uts/uas], bobot)
   - skala_grade (grade, nilai_min, is_lulus, urutan)
2. Pasang foreignId()->constrained()->restrictOnDelete() pada data master dan cascade pada data anak transaksional (mis. krs_detail, progres_materi, presensi).
3. Tambahkan SoftDeletes pada tabel master (program_studi, ruangan, dosen, mahasiswa, mata_kuliah).
4. Verifikasi dengan menjalankan php artisan migrate:fresh dan pastikan seluruh tabel terbuat tanpa kesalahan.
Berhenti setelah selesai dan beri ringkasan tabel yang dibuat.
```

### Fase 3 — Model Eloquent, Enum, Casts & Factory

```text
Fase 3: Model Eloquent, Enum, Casts & Factory.
1. Buat PHP Backed Enums dengan method label() berbahasa Indonesia:
   - Role: 'admin', 'dosen', 'mahasiswa'
   - StatusKrs: 'draft', 'diajukan', 'revisi', 'disetujui'
   - TipeKelas: 'offline', 'online', 'hybrid'
   - ModePertemuan: 'offline', 'online'
   - Hari: 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
   - StatusPresensi: 'hadir', 'izin', 'sakit', 'alpha'
   - MetodePresensi: 'mandiri', 'manual_dosen'
   - StatusNilai: 'lulus', 'tidak_lulus', 'belum_lengkap'
   - Semester: 'Ganjil', 'Genap'
2. Buat seluruh Model Eloquent lengkap dengan properti $fillable, $casts, relasi (belongsTo, hasMany, belongsToMany, hasOne), dan query scopes:
   - User, ProgramStudi, Ruangan, TahunAkademik, Dosen, Mahasiswa, MataKuliah,
   - Kelas, Krs, KrsDetail, KelasMahasiswa, Pertemuan, Materi, ProgresMateri,
   - Presensi, Tugas, PengumpulanTugas, Diskusi, Nilai, PengaturanNilai, SkalaGrade.
3. Buat Database Factories untuk model utama dengan data fiktif realistis Indonesia.
4. Buat unit test dasar untuk menguji relasi antar model.
Berhenti setelah selesai dan beri ringkasan + hasil test.
```

### Fase 4 — Database Seeder (Data Default & Akun Demo)

```text
Fase 4: Database Seeder idempoten terurut via DatabaseSeeder.
1. PengaturanNilaiSeeder: kuis 10, tugas 20, uts 30, uas 40.
2. SkalaGradeSeeder: A >= 80 (lulus), B >= 70 (lulus), C >= 60 (lulus), D >= 50 (tidak lulus), E >= 0 (tidak lulus).
3. Seeder Akun Demo & Data Akademik (environment local/testing):
   - Admin: admin@sipenma.test / password
   - Dosen: budi@sipenma.test / password (NIDN: 0012345678, NIP: 198501012010011001)
   - Mahasiswa: andi@sipenma.test / password (NIM: 23110001, Dosen Wali: Dr. Budi Santoso)
   - Master Ruangan lengkap dengan data GPS realistis (mis. Ruang Lab Komputer 1, Gedung TI Lt. 2, lat/lng kampus, radius 100m).
   - Tahun Akademik aktif (is_active = true, is_krs_open = true).
   - Minimal 3 Program Studi, 3 Dosen, 20 Mahasiswa, 6 Mata Kuliah, dan beberapa Kelas Perkuliahan (offline & online).
4. Jalankan php artisan migrate:fresh --seed dan pastikan seluruh seeder berhasil tanpa konflik.
Berhenti setelah selesai dan tampilkan ringkasan jumlah data seeder.
```

### Fase 5 — Autentikasi, Role Middleware & Otorisasi Kebijakan (Policy)

```text
Fase 5: Autentikasi, Role Middleware & Policies.
1. Pastikan rute publik Breeze /register dinonaktifkan (404).
2. Multi-Identifier Login: dukung login fleksibel (Email untuk Admin; Email/NIDN/NIP untuk Dosen; Email/NIM untuk Mahasiswa).
3. Redirect pasca-login berbasis role:
   - Admin -> /admin/dashboard
   - Dosen -> /dosen/dashboard
   - Mahasiswa -> /mahasiswa/dashboard
4. Middleware RoleMiddleware (alias: 'role') untuk memproteksi prefix /admin, /dosen, dan /mahasiswa.
5. Buat Policies untuk mencegah celah keamanan IDOR:
   - KrsPolicy: Mahasiswa hanya melihat/mengisi KRS miliknya saat periode buka; Dosen Wali hanya menyetujui KRS mahasiswa bimbingannya; Admin dapat meng-override.
   - KelasPolicy: Dosen hanya mengelola kelas yang diampunya; Mahasiswa hanya mengakses kelas yang disetujui di KRS-nya.
   - PertemuanPolicy, PresensiPolicy, TugasPolicy, MateriPolicy: membatasi hak akses file dan tindakan hanya untuk dosen pengampu atau mahasiswa kelas terkait.
6. Buat Feature Test untuk menguji autentikasi, redirect login per role, proteksi middleware, dan blokir akses lintas user (403).
Berhenti setelah selesai dan beri ringkasan + hasil test.
```

### Fase 6 — Master Data Admin (Ruangan GPS, Tahun Akademik, Dosen, Mahasiswa, Kelas)

```text
Fase 6: Master Data Admin.
Bangun antarmuka CRUD Admin lengkap dengan Controller (namespace App\Http\Controllers\Admin), FormRequest, rute terproteksi, pencarian, filter, dan pagination:
1. Program Studi: CRUD kode & nama prodi.
2. Ruangan & Lokasi GPS: CRUD kode, nama ruangan, gedung, kapasitas, tipe ruangan, serta input koordinat GPS latitude, longitude, dan radius_meter (tampilkan preview peta / link koordinat).
3. Tahun Akademik: CRUD tahun, semester, tanggal buka/tutup KRS, toggle sakelar is_krs_open, dan tombol aktifkan semester tunggal.
4. Dosen: CRUD NIDN, nama, prodi, email, NIP + sinkronisasi pembuatan akun User di dalam DB::transaction.
5. Mahasiswa: CRUD NIM, nama, prodi, angkatan, email + pemilihan Dosen Wali (PA) + sinkronisasi akun User di DB::transaction.
6. Mata Kuliah: CRUD kode, nama, SKS, semester paket, dan prodi.
7. Kelas & Jadwal Kuliah: CRUD mata kuliah, dosen pengampu, tahun akademik, tipe kelas (offline/online/hybrid), ruangan, hari, jam mulai, jam selesai, dan kapasitas kuota.
8. Feature Test untuk seluruh CRUD Master Data Admin.
Berhenti setelah selesai dan beri ringkasan + hasil test.
```

### Fase 7 — Modul KRS Online & Perwalian Dosen Wali

```text
Fase 7: Modul KRS Online & Perwalian.
1. Buat Service Classes:
   - JadwalConflictDetector: mendeteksi bentrok jadwal hari & jam antar kelas yang dipilih (jam_mulai_A < jam_selesai_B && jam_selesai_A > jam_mulai_B).
   - KrsService: menangani logika pemilihan kelas dengan DB transaction & lockForUpdate() (validasi batas SKS, kuota kelas, mata kuliah unik, dan periode buka KRS).
2. Mahasiswa - Pengisian KRS:
   - Katalog pemilihan kelas per semester aktif dengan indikator sisa kuota dan badge bentrok jadwal.
   - Tabel draf KRS: lihat daftar kelas yang dipilih, total SKS, hapus kelas, dan tombol Ajukan KRS ke Dosen Wali.
   - Status KRS: Draft -> Diajukan -> Revisi / Disetujui.
   - Fitur Cetak PDF KRS resmi (menggunakan DomPDF) setelah status Disetujui.
3. Dosen Wali - Perwalian KRS:
   - Halaman daftar mahasiswa bimbingan akademik beserta status KRS.
   - Halaman review KRS mahasiswa: rincian kelas yang diajukan, total SKS, form catatan pembimbing.
   - Tombol Setujui KRS (otomatis sinkronkan peserta ke kelas_mahasiswa dan buat baris awal di tabel nilai) atau Minta Revisi.
4. Admin - Override KRS: kemampuan admin melihat status KRS seluruh mahasiswa dan melakukan override bila diperlukan.
5. Feature test komprehensif untuk seluruh alur KRS, deteksi bentrok jadwal, pembatasan kuota, dan approval Dosen Wali.
Berhenti setelah selesai dan beri ringkasan + hasil test.
```

### Fase 8 — Ruang Kelas, Pertemuan (Sesi 1–16) & Materi Pembelajaran

```text
Fase 8: Ruang Kelas, Pertemuan & Materi Pembelajaran.
1. Dosen - Manajemen Pertemuan:
   - Tampilan kelas perkuliahan: daftar pertemuan 1–16.
   - Tambah/edit pertemuan: judul, tanggal, mode (offline di ruangan fisik atau online via link Zoom/GMeet), dan Berita Acara Perkuliahan (BAP).
2. Dosen - Materi Pembelajaran:
   - Unggah materi pada pertemuan: tipe file (PDF, PPTX, DOCX), tautan luar (link artikel), atau video (embed YouTube / link).
   - File materi disimpan aman di storage/app/private/materi/ dan diunduh via stream controller yang memvalidasi otorisasi.
3. Mahasiswa - Akses Perkuliahan:
   - Halaman ruang kelas: jadwal, informasi dosen, daftar pertemuan terbit, dan tautan video conference untuk kelas online.
   - Buka/unduh materi dan tombol "Tandai Selesai Belajar" (mencatat riwayat ke tabel progres_materi).
4. Forum Diskusi Sederhana:
   - Komentar dan tanya jawab bertingkat per pertemuan antara dosen dan mahasiswa kelas tersebut.
5. Feature Test untuk pembuatan pertemuan, unggah/unduh materi terproteksi, progres materi, dan otorisasi kelas.
Berhenti setelah selesai dan beri ringkasan + hasil test.
```

### Fase 9 — Modul Presensi Terverifikasi (Foto Selfie Live & GPS Geofencing)

```text
Fase 9: Modul Presensi Terverifikasi (Foto Selfie Live & GPS Geofencing).
1. Service Classes:
   - GeoDistanceCalculator: implementasi rumus Haversine untuk menghitung jarak meter antara koordinat GPS mahasiswa dan koordinat ruangan kelas (R = 6.371.000 meter).
   - PresensiService: menangani validasi presensi (cek sesi buka, validasi selfie live, validasi toleransi radius GPS untuk kelas offline, verifikasi PIN sesi, dan penyimpanan file foto selfie).
2. Dosen - Pengaturan Sesi & Verifikasi:
   - Sakelar buka/tutup presensi per pertemuan, dengan opsi wajib_selfie (default true), wajib_gps (default true), dan input opsional PIN 6 digit.
   - Galeri Verifikasi Presensi Dosen: menampilkan tabel mahasiswa dengan thumbnail foto selfie (klik modal untuk zoom), badge jarak GPS (contoh: "24m - Dalam Radius" atau "320m - Luar Radius"), link titik lokasi Google Maps, dan tombol koreksi status cepat (Hadir/Izin/Sakit/Alpha).
   - Input Presensi Manual Dosen: dosen dapat mencatat kehadiran mahasiswa secara langsung (metode = manual_dosen) untuk toleransi jika perangkat mahasiswa bermasalah.
3. Mahasiswa - Presensi Mandiri:
   - Antarmuka Kamera Live (Alpine.js + WebRTC navigator.mediaDevices.getUserMedia): tampilan viewfinder kamera depan, tombol capture foto ke <canvas> (resize maks 640x480 px, kompresi JPEG 75%, watermark waktu & NIM). Tidak ada opsi unggah file dari galeri HP untuk mencegah foto lama.
   - Pembacaan GPS presisi tinggi via navigator.geolocation.getCurrentPosition dengan indikator jarak real-time ke ruangan.
   - Form pengiriman presensi: foto selfie blob + latitude + longitude + akurasi + kode PIN (jika diaktifkan).
   - Khusus status Izin / Sakit: mahasiswa mengisi form keterangan dan mengunggah foto surat dokter/keterangan pendukung.
4. Keamanan & Storage:
   - Foto disimpan di storage/app/private/presensi/{pertemuan_id}/.
   - Buat FotoPresensiController yang dilindungi PresensiPolicy (hanya mahasiswa pemilik foto, dosen pengampu kelas, dan admin yang boleh melihat gambar).
5. Testing:
   - Unit Test GeoDistanceCalculator (uji titik identik = 0m, uji titik dalam radius 100m, uji titik di luar radius).
   - Feature Test: presensi berhasil di dalam radius, presensi ditolak di luar radius, presensi tanpa selfie ditolak saat wajib_selfie=true, proteksi privasi foto presensi (403 untuk mahasiswa lain), dan input manual dosen.
Berhenti setelah selesai dan beri ringkasan + hasil test.
```

### Fase 10 — Modul Tugas (Assignment) & Sinkronisasi Nilai Akhir

```text
Fase 10: Modul Tugas & Sinkronisasi Nilai Akhir.
1. Dosen - Penugasan:
   - Buat tugas pada pertemuan: judul, instruksi, lampiran panduan, batas waktu (deadline), dan opsi izinkan pengumpulan terlambat (allow_late).
   - Daftar pengumpulan tugas: unduh file jawaban submission mahasiswa, beri skor (0–100), dan berikan catatan umpan balik (feedback).
2. Mahasiswa - Pengumpulan Tugas:
   - Lihat rincian tugas dan sisa waktu deadline.
   - Unggah file tugas (PDF/ZIP) dan catatan pengantar sebelum batas waktu berakhir.
   - Lihat skor nilai dan umpan balik yang diberikan dosen setelah dinilai.
3. Sinkronisasi Nilai & Perhitungan Nilai Akhir:
   - SinkronisasiTugasService: tombol di dashboard kelas dosen untuk menarik nilai rata-rata seluruh tugas e-learning mahasiswa ke kolom komponen "Tugas" pada rekap nilai.
   - NilaiCalculator: menghitung Nilai Akhir otomatis berdasarkan bobot dari database:
     Nilai Akhir = (Kuis*bobot + Tugas*bobot + UTS*bobot + UAS*bobot) / 100.
   - Penentuan Grade (A–E) dan status kelulusan (Lulus jika grade >= C).
   - Dosen\NilaiController: input nilai massal (Kuis, UTS, UAS) dengan validasi FormRequest (0–100, maks 2 desimal).
   - Artisan command: php artisan nilai:recalculate {--kelas=} untuk perhitungan ulang massal.
4. Feature Test untuk pembuatan tugas, pengumpulan file jawaban, penilaian dosen, sinkronisasi nilai tugas, dan perhitungan nilai akhir.
Berhenti setelah selesai dan beri ringkasan + hasil test.
```

### Fase 11 — Dashboard per Role & Laporan Akademik (PDF & Excel)

```text
Fase 11: Dashboard per Role & Laporan Akademik.
1. DashboardService & Tampilan Dashboard Interaktif:
   - Admin: metrik total mahasiswa, dosen, kelas aktif, ruangan kampus; grafik sebaran grade nilai institusi; ringkasan status KRS semester aktif.
   - Dosen: ringkasan kelas yang diampu, jadwal mengajar hari ini (offline/online), daftar mahasiswa bimbingan yang butuh approval KRS, dan progres penilaian tugas/nilai.
   - Mahasiswa: jadwal kuliah hari ini beserta ruangan/link meeting, status persetujuan KRS, progres belajar materi, dan ringkasan nilai semester berjalan.
   - Komponen Chart.js reusable via Blade (x-chart).
2. Laporan Akademik (LaporanService):
   - Laporan Rekapitulasi Nilai Kelas (filter: prodi, tahun akademik, kelas, dosen).
   - Laporan Rekapitulasi Presensi & Kehadiran Mahasiswa (persentase hadir, sakit, izin, alpha).
3. Ekspor Dokumen Resmi:
   - Ekspor PDF (DomPDF): Cetak Lembar KRS Mahasiswa, Rekap Daftar Hadir Pertemuan, dan Kartu Hasil Studi (KHS) / Laporan Nilai ber-kop resmi.
   - Ekspor Excel (Maatwebsite Excel): Rekapitulasi nilai dan presensi mahasiswa per kelas.
4. Feature Test pengujian data dashboard per role, proteksi filter laporan, dan otorisasi ekspor PDF/Excel.
Berhenti setelah selesai dan beri ringkasan + hasil test.
```

### Fase 12 — Finalisasi, Optimasi, Security Audit & Panduan Deployment

```text
Fase 12: Finalisasi, Optimasi, Security Audit & Deployment.
1. Security & Privacy Audit:
   - Audit IDOR pada seluruh endpoint model binding.
   - Verifikasi UU PDP: pastikan foto selfie presensi dan file tugas mahasiswa tidak pernah bocor ke public storage.
   - Validasi MIME type file upload dan sanitasi input.
   - Rate limiting pada rute autentikasi dan presensi.
2. Optimasi Performa & Storage:
   - Audit dan perbaiki potensi N+1 query pada jadwal mingguan, galeri presensi, dan laporan nilai menggunakan eager loading.
   - Artisan command presensi:prune-foto untuk pembersihan arsip foto selfie fisik dari tahun akademik lampau yang melebihi batas retensi.
3. Kualitas Kode & Automated Testing:
   - Jalankan seluruh test suite dengan Pest: php artisan test --compact dan pastikan 100% test lulus.
   - Jalankan formatter: vendor/bin/pint --format agent untuk merapikan seluruh file PHP.
4. Dokumentasi & Panduan Deployment:
   - Perbarui README.md dan panduan konfigurasi SSL/HTTPS untuk mendukung kamera WebRTC & GPS di perangkat smartphone.
   - Buat panduan deployment Nginx, konfigurasi client_max_body_size, php-fpm, dan cron job scheduler.
Berikan laporan akhir penyelesaian proyek SIELMA secara menyeluruh.
```

---

## 3. Prompt Tambahan (Opsional)

**Review Kode & Privasi Data**
```text
Lakukan audit kode dan keamanan menyeluruh pada proyek SIELMA ini. Fokus utama:
1. Pastikan foto selfie presensi hanya dapat diakses melalui endpoint streaming ber-Policy dan tidak pernah terekspos di disk public.
2. Uji keandalan rumus Haversine di GeoDistanceCalculator terhadap batas toleransi koordinat ruangan.
3. Periksa potensi race condition pada pemilihan kelas KRS (pastikan lockForUpdate aktif).
4. Audit N+1 query pada galeri verifikasi presensi dan katalog jadwal.
Tampilkan laporan temuan terurut berdasarkan tingkat risiko dan solusi perbaikannya.
```

**Uji Lapangan Simulasi Presensi Selfie & GPS**
```text
Buat Feature Test khusus yang mensimulasikan skenario presensi nyata:
1. Mahasiswa mengirim presensi Hadir dengan koordinat tepat di ruangan kelas -> status Hadir, foto tersimpan di disk private.
2. Mahasiswa mengirim presensi Hadir dengan koordinat 350 meter dari ruangan -> ditolak validasi 422.
3. Mahasiswa mengirim presensi Izin dengan lampiran foto surat dokter -> status Izin, tersimpan valid.
4. Dosen mengubah status mahasiswa dari Alpha menjadi Hadir secara manual -> status terupdate dengan metode manual_dosen.
Jalankan test dan tampilkan laporan hasilnya.
```

**Seed Data Skala Penuh untuk Pengujian Beban**
```text
Buat seeder khusus pengujian performa lokal: 50 dosen, 2.500 mahasiswa, 250 kelas perkuliahan, 16 pertemuan per kelas, dan ribuan data presensi/nilai.
Ukur waktu respon query pada dashboard admin, galeri presensi dosen, dan ekspor laporan nilai. Berikan rekomendasi indeks database bila ditemukan bottleneck.
```

---

## 4. Checklist Kebutuhan Implementasi

- [ ] Nama resmi institusi, logo kampus, dan palet warna sistem
- [ ] Daftar ruangan fisik kampus beserta titik koordinat GPS (latitude, longitude) dan radius toleransi (meter)
- [ ] Daftar program studi, kurikulum mata kuliah, dan semester paket
- [ ] Data awal dosen (NIDN, NIP, nama, email, prodi) dan mahasiswa (NIM, nama, angkatan, plotting dosen wali)
- [ ] Konfigurasi periode aktif semester dan rentang tanggal pengisian KRS
- [ ] Konfigurasi bobot penilaian (default: Kuis 10, Tugas 20, UTS 30, UAS 40) dan skala grade (A–E)
- [ ] Sertifikat SSL (HTTPS) pada server pengembangan lokal dan produksi (wajib untuk akses Kamera WebRTC & Geolocation)
- [ ] Konfigurasi disk storage private di server untuk menampung foto selfie terkompresi dan dokumen tugas
- [ ] Layanan email transaksi (SMTP) untuk kebutuhan reset password pengguna
- [ ] Format kop resmi dokumen PDF (Lembar KRS, Daftar Hadir Perkuliahan, Kartu Hasil Studi)
