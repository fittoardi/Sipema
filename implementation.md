# IMPLEMENTATION PLAN SIPENMA

## 1. Status Saat Ini
- **Framework & Package**: Laravel 13, Filament v5, Livewire v4, Tailwind CSS v3, PHP 8.3.
- **Database & Migrasi**: Tabel `users`, `prodis`, `dosens`, `mahasiswas`, `mata_kuliahs`, `kelas`, `jadwals`, `nilais`, `kelas_mahasiswa`, `password_reset_codes` sudah dibuat dan dimigrasi.
- **Autentikasi**: Laravel Breeze & Filament Panel terintegrasi dengan kustomisasi role (`admin`, `dosen`, `mahasiswa`).
- **Routing**:
  - Web: `/admin` (Filament Panel untuk admin)
  - `/dosen/*`: Dashboard & Modul Nilai untuk dosen (`role:dosen`)
  - `/mahasiswa/*`: Dashboard mahasiswa (`role:mahasiswa`)

## 2. Roadmap Implementasi Teknis

### Fase 1: Master Data & Filament Resources (Selesai)
- [x] CRUD Program Studi (`Prodi`)
- [x] CRUD Dosen (`Dosen`)
- [x] CRUD Mahasiswa (`Mahasiswa`)
- [x] CRUD Mata Kuliah (`MataKuliah`)

### Fase 2: Modul Akademik & Nilai (Dalam Pengembangan)
- [ ] Pengelolaan Kelas & Penugasan Dosen Pengampu (`Kelas`)
- [ ] Relasi Mahasiswa ke Kelas (`kelas_mahasiswa`)
- [ ] Input & Perhitungan Nilai (Kuis, Tugas, UTS, UAS -> Nilai Akhir & Grade) oleh Dosen (`NilaiController`)
- [ ] Halaman Rekap Nilai Mahasiswa (`Mahasiswa\DashboardController`)

### Fase 3: Laporan & Ekspor (Planned)
- [ ] Generate PDF Laporan Nilai (DomPDF)
- [ ] Export Excel Laporan Nilai (Laravel Excel)

### Fase 4: Pengujian & Pemeliharaan (Continuous)
- [ ] Unit & Feature Testing dengan Pest v4
- [ ] Code Formatter (`vendor/bin/pint --format agent`)
