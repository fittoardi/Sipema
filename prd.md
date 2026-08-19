# PRODUCT REQUIREMENT DOCUMENT (PRD)

## Sistem Pengelolaan Nilai Mahasiswa (SIPENMA)

**Versi:** 1.0
**Platform:** Web Application
**Framework:** Laravel 13
**Database:** MySQL
**Frontend:** Blade + Tailwind CSS
**Authentication:** Laravel Breeze

---

## 1. Ringkasan Produk

SIPENMA (Sistem Pengelolaan Nilai Mahasiswa) adalah aplikasi berbasis web yang digunakan untuk mengelola data akademik mahasiswa, dosen, program studi, mata kuliah, kelas, serta nilai mahasiswa.

Sistem memiliki tiga role utama:

1. **Admin**
2. **Dosen**
3. **Mahasiswa**

Admin bertanggung jawab terhadap pengelolaan data master dan pengguna. Dosen bertanggung jawab terhadap pengelolaan nilai mahasiswa pada kelas yang diampunya. Mahasiswa dapat melihat informasi akademik dan nilai yang telah diberikan.

Sistem juga menyediakan dashboard, perhitungan nilai akhir secara otomatis, grade, status kelulusan, serta laporan yang dapat diekspor ke PDF dan Excel.

---

# 2. Latar Belakang

Pengelolaan nilai mahasiswa membutuhkan data yang terstruktur agar proses input, perhitungan, penyimpanan, dan pelaporan nilai dapat dilakukan secara konsisten.

SIPENMA dirancang untuk:

* mengurangi proses pengelolaan nilai secara manual;
* memusatkan data akademik;
* mengurangi kesalahan perhitungan nilai;
* menyediakan akses berdasarkan role pengguna;
* menyediakan informasi nilai secara cepat;
* menghasilkan laporan akademik dalam format PDF dan Excel.

---

# 3. Tujuan Produk

### Tujuan utama

Membangun sistem pengelolaan nilai mahasiswa berbasis web yang terintegrasi dan memiliki kontrol akses berdasarkan role.

### Tujuan khusus

* Mengelola data program studi.
* Mengelola data dosen.
* Mengelola data mahasiswa.
* Mengelola data mata kuliah.
* Mengelola data kelas.
* Mengelola data nilai mahasiswa.
* Menghitung nilai akhir secara otomatis.
* Menentukan grade dan status nilai.
* Menampilkan dashboard akademik.
* Menyediakan laporan nilai.
* Menyediakan export PDF.
* Menyediakan export Excel.

---

# 4. Ruang Lingkup

## 4.1 Termasuk dalam sistem

### Authentication

* Login
* Logout
* Reset password

### Authorization

* Role Admin
* Role Dosen
* Role Mahasiswa
* Middleware
* Policy
* Gate

### Master Data

* Program Studi
* Dosen
* Mahasiswa
* Mata Kuliah
* Kelas

### Modul Nilai

* Nilai Kuis
* Nilai Tugas
* Nilai UTS
* Nilai UAS
* Nilai Akhir
* Grade
* Status

### Dashboard

* Total mahasiswa
* Total dosen
* Distribusi grade
* Grafik nilai

### Laporan

* Laporan nilai
* Export PDF
* Export Excel

### Testing & Deployment

* Unit Test
* Feature Test
* Optimasi
* Deployment

Roadmap memang menetapkan dashboard dengan total mahasiswa, total dosen, distribusi grade, dan grafik nilai.

---

# 5. Role dan Hak Akses

| Fitur                | Admin | Dosen | Mahasiswa |
| -------------------- | ----: | ----: | --------: |
| Login                |     ✓ |     ✓ |         ✓ |
| Logout               |     ✓ |     ✓ |         ✓ |
| Reset Password       |     ✓ |     ✓ |         ✓ |
| Dashboard            |     ✓ |     ✓ |         ✓ |
| Kelola Program Studi |     ✓ |     - |         - |
| Kelola Dosen         |     ✓ |     - |         - |
| Kelola Mahasiswa     |     ✓ |     - |         - |
| Kelola Mata Kuliah   |     ✓ |     - |         - |
| Kelola Kelas         |     ✓ |     - |         - |
| Input Nilai          |     - |     ✓ |         - |
| Edit Nilai           |     - |     ✓ |         - |
| Lihat Nilai          |     ✓ |     ✓ |         ✓ |
| Laporan Nilai        |     ✓ |     ✓ |         ✓ |
| Export PDF           |     ✓ |     ✓ |         - |
| Export Excel         |     ✓ |     ✓ |         - |

---

# 6. Functional Requirements

## FR-01 Authentication

Sistem harus menyediakan:

* login pengguna;
* logout pengguna;
* reset password;
* validasi kredensial;
* redirect berdasarkan role.

---

## FR-02 User Management

Sistem menyimpan informasi pengguna yang digunakan untuk autentikasi.

Data minimal:

* id;
* name;
* email;
* password;
* role.

Role terdiri dari:

* admin;
* dosen;
* mahasiswa.

---

## FR-03 Program Studi

Admin dapat:

* melihat program studi;
* menambah program studi;
* mengubah program studi;
* menghapus program studi.

Data minimal:

* id;
* kode program studi;
* nama program studi.

---

## FR-04 Dosen

Admin dapat melakukan CRUD data dosen.

Data minimal:

* id;
* user_id;
* NIDN;
* nama;
* program studi.

Setiap dosen dapat memiliki akun pengguna.

---

## FR-05 Mahasiswa

Admin dapat melakukan CRUD data mahasiswa.

Data minimal:

* id;
* user_id;
* NIM;
* nama;
* program studi;
* angkatan.

Setiap mahasiswa dapat memiliki akun pengguna.

---

## FR-06 Mata Kuliah

Admin dapat mengelola mata kuliah.

Data minimal:

* id;
* kode mata kuliah;
* nama mata kuliah;
* SKS;
* program studi.

---

## FR-07 Kelas

Admin dapat membuat kelas dan menentukan:

* mata kuliah;
* dosen pengampu;
* tahun akademik;
* semester.

Kelas menjadi penghubung antara mata kuliah, dosen, dan mahasiswa.

---

## FR-08 Nilai

Dosen dapat memasukkan nilai mahasiswa berdasarkan kelas yang diampu.

Komponen nilai:

* Kuis;
* Tugas;
* UTS;
* UAS.

Sistem kemudian menghitung nilai akhir secara otomatis.

Roadmap secara eksplisit menetapkan empat komponen tersebut dan proses perhitungan nilai akhir, grade, serta status.

---

## FR-09 Perhitungan Nilai

Sistem menghitung:

**Nilai Akhir = bobot Kuis + bobot Tugas + bobot UTS + bobot UAS**

Bobot masing-masing komponen sebaiknya dibuat sebagai konfigurasi agar dapat diubah tanpa mengubah struktur database.

> Catatan: roadmap belum menentukan persentase bobot dan batas grade. Karena itu angka tersebut belum ditetapkan dalam PRD ini.

---

## FR-10 Grade

Sistem menentukan grade berdasarkan nilai akhir.

Contoh struktur yang dapat dikonfigurasi:

| Grade |       Rentang |
| ----- | ------------: |
| A     | dikonfigurasi |
| B     | dikonfigurasi |
| C     | dikonfigurasi |
| D     | dikonfigurasi |
| E     | dikonfigurasi |

Batas nilai **belum ditentukan dalam roadmap**, sehingga sebaiknya tidak di-hardcode pada tahap awal.

---

## FR-11 Status

Sistem menentukan status berdasarkan hasil nilai.

Contoh:

* Lulus
* Tidak Lulus

Aturan status juga sebaiknya dibuat configurable.

---

## FR-12 Dashboard

Dashboard menampilkan informasi:

### Admin

* Total mahasiswa
* Total dosen
* Distribusi grade
* Grafik nilai

### Dosen

* Kelas yang diampu
* Jumlah mahasiswa
* Ringkasan nilai

### Mahasiswa

* Mata kuliah yang diikuti
* Nilai
* Grade
* Status

---

## FR-13 Laporan

Sistem menyediakan laporan nilai.

Filter laporan dapat berdasarkan:

* mahasiswa;
* mata kuliah;
* kelas;
* dosen;
* program studi;
* semester.

Format output:

* PDF;
* Excel.

Roadmap menetapkan laporan nilai beserta export PDF dan Excel.

---

# 7. Non-Functional Requirements

## NFR-01 Security

Sistem harus:

* menggunakan autentikasi;
* menggunakan authorization;
* menggunakan middleware;
* menerapkan Policy/Gate;
* melakukan validasi input;
* melakukan hashing password.

Roadmap menetapkan penggunaan Policy, Gate, dan Middleware untuk membatasi akses berdasarkan role.

## NFR-02 Performance

Sistem harus dapat menampilkan dashboard dan data akademik dengan waktu respons yang wajar.

Query database harus menggunakan:

* indexing;
* eager loading;
* pagination.

## NFR-03 Maintainability

Kode harus mengikuti struktur Laravel dan menggunakan:

* Model;
* Controller;
* Request Validation;
* Policy;
* Middleware;
* Service jika diperlukan.

## NFR-04 Scalability

Struktur database harus memungkinkan penambahan:

* semester;
* tahun akademik;
* komponen nilai;
* fitur akademik lainnya.

---

# 8. User Stories

## Admin

**US-01**
Sebagai Admin, saya ingin login agar dapat mengakses sistem.

**US-02**
Sebagai Admin, saya ingin mengelola program studi agar data program studi selalu terbarui.

**US-03**
Sebagai Admin, saya ingin mengelola dosen agar data dosen tersimpan terpusat.

**US-04**
Sebagai Admin, saya ingin mengelola mahasiswa agar data mahasiswa dapat digunakan dalam sistem.

**US-05**
Sebagai Admin, saya ingin mengelola mata kuliah agar mata kuliah dapat digunakan pada kelas.

**US-06**
Sebagai Admin, saya ingin mengelola kelas agar dosen dan mahasiswa dapat dikaitkan dengan mata kuliah.

---

## Dosen

**US-07**
Sebagai Dosen, saya ingin melihat kelas yang saya ampu.

**US-08**
Sebagai Dosen, saya ingin melihat mahasiswa pada kelas saya.

**US-09**
Sebagai Dosen, saya ingin memasukkan nilai kuis, tugas, UTS, dan UAS.

**US-10**
Sebagai Dosen, saya ingin sistem menghitung nilai akhir secara otomatis.

**US-11**
Sebagai Dosen, saya ingin melihat grade dan status mahasiswa.

**US-12**
Sebagai Dosen, saya ingin membuat laporan nilai.

---

## Mahasiswa

**US-13**
Sebagai Mahasiswa, saya ingin login ke sistem.

**US-14**
Sebagai Mahasiswa, saya ingin melihat mata kuliah yang saya ikuti.

**US-15**
Sebagai Mahasiswa, saya ingin melihat nilai saya.

**US-16**
Sebagai Mahasiswa, saya ingin melihat nilai akhir, grade, dan status.

---

# 9. Acceptance Criteria Utama

### Authentication

* User dapat login dengan kredensial valid.
* User ditolak jika kredensial salah.
* User dapat logout.
* User dapat melakukan reset password.

### Authorization

* Admin dapat mengakses master data.
* Dosen hanya dapat mengelola nilai kelas yang diampunya.
* Mahasiswa hanya dapat melihat data akademiknya sendiri.

### Nilai

* Dosen dapat memasukkan empat komponen nilai.
* Sistem menghitung nilai akhir.
* Sistem menentukan grade.
* Sistem menentukan status.

### Laporan

* User yang memiliki akses dapat melihat laporan.
* Laporan dapat diekspor ke PDF.
* Laporan dapat diekspor ke Excel.

---

# 10. Roadmap Implementasi

| Phase | Modul          | Output                 |
| ----- | -------------- | ---------------------- |
| 1     | Persiapan      | Laravel 13 siap        |
| 2     | Database       | ERD & schema           |
| 3     | Migration      | Struktur database      |
| 4     | Model          | Model & relasi         |
| 5     | Seeder         | Data awal              |
| 6     | Authentication | Login/logout/reset     |
| 7     | Authorization  | Policy/Gate/Middleware |
| 8     | Dashboard      | Dashboard role         |
| 9     | Master Data    | CRUD akademik          |
| 10    | Nilai          | Input & perhitungan    |
| 11    | Laporan        | PDF & Excel            |
| 12    | Testing        | Testing & deployment   |

## Urutan ini mengikuti roadmap sumber secara langsung.

# 11. Teknologi

* Laravel 13
* PHP
* MySQL
* Blade
* Tailwind CSS
* Laravel Breeze
* Laravel Excel
* DomPDF
* Chart.js
* Git

Teknologi tersebut berasal dari fase persiapan roadmap.

---

# 12. Out of Scope

Untuk versi awal, fitur berikut belum menjadi bagian dari requirement:

* KRS online;
* presensi;
* jadwal kuliah;
* transkrip akademik;
* pembayaran;
* notifikasi;
* integrasi SIAKAD eksternal;
* mobile application.

Fitur tersebut tidak tercantum pada roadmap yang diberikan.
