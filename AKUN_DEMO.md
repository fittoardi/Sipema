# Akun Demo SIPEMA

Dokumen ini berisi daftar kredensial akun demo bawaan untuk pengujian dan evaluasi aplikasi **SIPEMA** (Sistem Informasi Penilaian Mahasiswa).

---

## 🚀 Cara Mengisi Data Akun Demo

Jalankan perintah database seeder berikut pada terminal:

```bash
php artisan db:seed
```

Atau jika ingin mereset ulang seluruh tabel beserta data seed:

```bash
php artisan migrate:fresh --seed
```

---

## 🔑 Ringkasan Kredensial

| Peran (Role) | Nama Pengguna | Identifier Login (Pilih Salah Satu) | Password | URL Dashboard |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Super Admin | `admin@sipenma.test` | `password` | `/admin` / `/admin/dashboard` |
| **Dosen** | Dr. Budi Santoso | `budi@sipenma.test`<br>NIDN: `0012345678`<br>NIP: `198501012010011001` | `password` | `/dosen/dashboard` |
| **Mahasiswa** | Andi Pratama | `andi@sipenma.test`<br>NIM: `23110001` | `password` | `/mahasiswa/dashboard` |

---

## 📋 Rincian & Hak Akses Peran

### 1. Super Admin
- **Email**: `admin@sipenma.test`
- **Password**: `password`
- **Peran**: `admin`
- **Akses Halaman**:
  - Halaman Login: [`/login`](file:///d:/Laravel/Sipema/resources/views/auth/login.blade.php)
  - Filament Admin Panel: `/admin`
  - Custom Admin Dashboard: `/admin/dashboard`
- **Fitur Utama**:
  - Manajemen Program Studi (Prodi)
  - Manajemen Data Dosen
  - Manajemen Data Mahasiswa
  - Manajemen Mata Kuliah & SKS
  - Manajemen Kelas & Pengajar
  - Input & Rekap Nilai Akademik
  - Laporan & Filter Nilai (Ekspor CSV, Excel, dan PDF)

---

### 2. Dosen
- **Nama**: Dr. Budi Santoso
- **Email**: `budi@sipenma.test`
- **NIDN**: `0012345678`
- **NIP**: `198501012010011001`
- **Password**: `password`
- **Peran**: `dosen`
- **Akses Halaman**:
  - Halaman Login: [`/login`](file:///d:/Laravel/Sipema/resources/views/auth/login.blade.php) (Bisa menggunakan Email, NIDN, atau NIP)
  - Dashboard Dosen: `/dosen/dashboard`
  - Kelola Nilai: `/dosen/dashboard/nilai`
- **Fitur Utama**:
  - Ringkasan kelas perkuliahan yang diampu
  - Input & pembaruan nilai mahasiswa per kelas (Kuis, Tugas, UTS, UAS)
  - Penghitungan otomatis bobot nilai, grade, dan status kelulusan
  - Unduh rekapitulasi nilai kelas format CSV

---

### 3. Mahasiswa
- **Nama**: Andi Pratama
- **NIM**: `23110001`
- **Angkatan**: `2023`
- **Program Studi**: Teknik Informatika
- **Email**: `andi@sipenma.test`
- **Password**: `password`
- **Peran**: `mahasiswa`
- **Akses Halaman**:
  - Halaman Login: [`/login`](file:///d:/Laravel/Sipema/resources/views/auth/login.blade.php) (Bisa menggunakan Email atau NIM)
  - Dashboard Mahasiswa: `/mahasiswa/dashboard`
- **Fitur Utama**:
  - Ringkasan progres studi (Program Studi, total mata kuliah, jadwal)
  - Pemantauan nilai akhir, perolehan grade (A–E), dan status kelulusan
  - Unduh lembar transkrip nilai akademik format CSV

---

## 💡 Fitur Multi-Identifier Login

Sistem login SIPEMA mendukung satu kolom fleksibel (`login`) pada formulir masuk:
- **Admin**: Masuk dengan alamat email.
- **Dosen**: Dapat masuk dengan **Email**, **NIDN**, maupun **NIP**.
- **Mahasiswa**: Dapat masuk dengan **Email** maupun **NIM**.

