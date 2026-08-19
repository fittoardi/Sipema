# ENTITY RELATIONSHIP DIAGRAM (ERD)

## SIPENMA – Sistem Pengelolaan Nilai Mahasiswa

---

## 1. Entitas Database

### 1. Users

Menyimpan data akun seluruh pengguna sistem.

| Field      | Type      | Key    | Keterangan            |
| ---------- | --------- | ------ | --------------------- |
| id         | BIGINT    | PK     | ID user               |
| name       | VARCHAR   |        | Nama pengguna         |
| email      | VARCHAR   | UNIQUE | Email                 |
| password   | VARCHAR   |        | Password              |
| role       | ENUM      |        | admin/dosen/mahasiswa |
| created_at | TIMESTAMP |        |                       |
| updated_at | TIMESTAMP |        |                       |

---

### 2. Program Studis

Menyimpan data program studi.

| Field      | Type      | Key    | Keterangan         |
| ---------- | --------- | ------ | ------------------ |
| id         | BIGINT    | PK     | ID program studi   |
| kode       | VARCHAR   | UNIQUE | Kode prodi         |
| nama       | VARCHAR   |        | Nama program studi |
| created_at | TIMESTAMP |        |                    |
| updated_at | TIMESTAMP |        |                    |

---

### 3. Dosens

Menyimpan data dosen.

| Field      | Type      | Key    | Keterangan        |
| ---------- | --------- | ------ | ----------------- |
| id         | BIGINT    | PK     | ID dosen          |
| user_id    | BIGINT    | FK     | User pemilik akun |
| prodi_id   | BIGINT    | FK     | Program studi     |
| nidn       | VARCHAR   | UNIQUE | NIDN              |
| nama       | VARCHAR   |        | Nama dosen        |
| created_at | TIMESTAMP |        |                   |
| updated_at | TIMESTAMP |        |                   |

Relasi:

* `dosens.user_id → users.id`
* `dosens.prodi_id → program_studis.id`

---

### 4. Mahasiswas

Menyimpan data mahasiswa.

| Field      | Type      | Key    | Keterangan        |
| ---------- | --------- | ------ | ----------------- |
| id         | BIGINT    | PK     | ID mahasiswa      |
| user_id    | BIGINT    | FK     | User pemilik akun |
| prodi_id   | BIGINT    | FK     | Program studi     |
| nim        | VARCHAR   | UNIQUE | NIM               |
| nama       | VARCHAR   |        | Nama mahasiswa    |
| angkatan   | YEAR      |        | Tahun angkatan    |
| created_at | TIMESTAMP |        |                   |
| updated_at | TIMESTAMP |        |                   |

Relasi:

* `mahasiswas.user_id → users.id`
* `mahasiswas.prodi_id → program_studis.id`

---

### 5. Mata Kuliahs

Menyimpan data mata kuliah.

| Field      | Type      | Key    | Keterangan       |
| ---------- | --------- | ------ | ---------------- |
| id         | BIGINT    | PK     | ID mata kuliah   |
| prodi_id   | BIGINT    | FK     | Program studi    |
| kode       | VARCHAR   | UNIQUE | Kode mata kuliah |
| nama       | VARCHAR   |        | Nama mata kuliah |
| sks        | INT       |        | Jumlah SKS       |
| created_at | TIMESTAMP |        |                  |
| updated_at | TIMESTAMP |        |                  |

Relasi:

`mata_kuliahs.prodi_id → program_studis.id`

---

### 6. Kelas

Menyimpan kelas perkuliahan.

| Field          | Type      | Key | Keterangan     |
| -------------- | --------- | --- | -------------- |
| id             | BIGINT    | PK  | ID kelas       |
| mata_kuliah_id | BIGINT    | FK  | Mata kuliah    |
| dosen_id       | BIGINT    | FK  | Dosen pengampu |
| nama           | VARCHAR   |     | Nama kelas     |
| tahun_akademik | VARCHAR   |     | Tahun akademik |
| semester       | ENUM      |     | Ganjil/Genap   |
| created_at     | TIMESTAMP |     |                |
| updated_at     | TIMESTAMP |     |                |

Relasi:

* `kelas.mata_kuliah_id → mata_kuliahs.id`
* `kelas.dosen_id → dosens.id`

---

### 7. Nilais

Menyimpan nilai mahasiswa pada suatu kelas.

| Field        | Type      | Key | Keterangan        |
| ------------ | --------- | --- | ----------------- |
| id           | BIGINT    | PK  | ID nilai          |
| kelas_id     | BIGINT    | FK  | Kelas             |
| mahasiswa_id | BIGINT    | FK  | Mahasiswa         |
| kuis         | DECIMAL   |     | Nilai kuis        |
| tugas        | DECIMAL   |     | Nilai tugas       |
| uts          | DECIMAL   |     | Nilai UTS         |
| uas          | DECIMAL   |     | Nilai UAS         |
| nilai_akhir  | DECIMAL   |     | Hasil perhitungan |
| grade        | VARCHAR   |     | Grade             |
| status       | VARCHAR   |     | Status kelulusan  |
| created_at   | TIMESTAMP |     |                   |
| updated_at   | TIMESTAMP |     |                   |

Relasi:

* `nilais.kelas_id → kelas.id`
* `nilais.mahasiswa_id → mahasiswas.id`

---

# 2. Relasi Antar Entitas

### Users → Dosen

**1 : 0..1**

Satu user dapat menjadi satu dosen.

```text
users
  │
  └────── 0..1 dosens
```

### Users → Mahasiswa

**1 : 0..1**

Satu user dapat menjadi satu mahasiswa.

```text
users
  │
  └────── 0..1 mahasiswas
```

### Program Studi → Dosen

**1 : N**

Satu program studi dapat memiliki banyak dosen.

```text
program_studis
      │
      └────── N dosens
```

### Program Studi → Mahasiswa

**1 : N**

Satu program studi dapat memiliki banyak mahasiswa.

```text
program_studis
      │
      └────── N mahasiswas
```

### Program Studi → Mata Kuliah

**1 : N**

Satu program studi dapat memiliki banyak mata kuliah.

```text
program_studis
      │
      └────── N mata_kuliahs
```

### Mata Kuliah → Kelas

**1 : N**

Satu mata kuliah dapat memiliki banyak kelas.

```text
mata_kuliahs
      │
      └────── N kelas
```

### Dosen → Kelas

**1 : N**

Satu dosen dapat mengampu banyak kelas.

```text
dosens
  │
  └────── N kelas
```

### Kelas → Nilai

**1 : N**

Satu kelas memiliki banyak data nilai mahasiswa.

```text
kelas
  │
  └────── N nilais
```

### Mahasiswa → Nilai

**1 : N**

Satu mahasiswa dapat memiliki banyak nilai dari berbagai kelas.

```text
mahasiswas
     │
     └────── N nilais
```

---

# 3. ERD Diagram

```text
                         ┌─────────────────────┐
                         │        USERS        │
                         ├─────────────────────┤
                         │ PK id               │
                         │ name                │
                         │ email               │
                         │ password            │
                         │ role                │
                         └─────────┬───────────┘
                                   │
                     ┌─────────────┴─────────────┐
                     │                           │
                     │ 1                       1 │
                     ▼                           ▼
          ┌──────────────────┐        ┌──────────────────┐
          │      DOSENS      │        │    MAHASISWAS    │
          ├──────────────────┤        ├──────────────────┤
          │ PK id            │        │ PK id            │
          │ FK user_id       │        │ FK user_id       │
          │ FK prodi_id      │        │ FK prodi_id      │
          │ nidn             │        │ nim              │
          │ nama             │        │ nama             │
          └────────┬─────────┘        │ angkatan         │
                   │                  └────────┬─────────┘
                   │                           │
                   │                           │
                   │ N                         │ N
                   │                           │
                   ▼                           │
          ┌──────────────────┐                 │
          │      KELAS       │                 │
          ├──────────────────┤                 │
          │ PK id            │                 │
          │ FK mata_kuliah_id│                 │
          │ FK dosen_id      │                 │
          │ nama             │                 │
          │ tahun_akademik   │                 │
          │ semester         │                 │
          └────────┬─────────┘                 │
                   │                           │
                   │ 1                         │
                   │                           │
                   ▼                           │
          ┌──────────────────┐                 │
          │      NILAIS      │◄────────────────┘
          ├──────────────────┤              N
          │ PK id            │
          │ FK kelas_id      │
          │ FK mahasiswa_id  │
          │ kuis             │
          │ tugas            │
          │ uts              │
          │ uas              │
          │ nilai_akhir      │
          │ grade            │
          │ status           │
          └──────────────────┘
                   ▲
                   │
                   │ N
                   │
          ┌────────┴─────────┐
          │  MATA_KULIAHS    │
          ├──────────────────┤
          │ PK id            │
          │ FK prodi_id      │
          │ kode             │
          │ nama             │
          │ sks              │
          └────────┬─────────┘
                   │
                   │ N
                   │
                   ▼
          ┌──────────────────┐
          │ PROGRAM_STUDIS   │
          ├──────────────────┤
          │ PK id            │
          │ kode             │
          │ nama             │
          └──────────────────┘
```

---

# 4. Relational Schema

```text
users
-----
PK id
name
email
password
role


program_studis
--------------
PK id
kode
nama


dosens
------
PK id
FK user_id → users.id
FK prodi_id → program_studis.id
nidn
nama


mahasiswas
----------
PK id
FK user_id → users.id
FK prodi_id → program_studis.id
nim
nama
angkatan


mata_kuliahs
------------
PK id
FK prodi_id → program_studis.id
kode
nama
sks


kelas
-----
PK id
FK mata_kuliah_id → mata_kuliahs.id
FK dosen_id → dosens.id
nama
tahun_akademik
semester


nilais
------
PK id
FK kelas_id → kelas.id
FK mahasiswa_id → mahasiswas.id
kuis
tugas
uts
uas
nilai_akhir
grade
status
```

---

# 5. Constraint yang Disarankan

### Unique

```text
users.email
program_studis.kode
dosens.nidn
mahasiswas.nim
mata_kuliahs.kode
```

### Unique nilai mahasiswa dalam kelas

```text
UNIQUE(
    kelas_id,
    mahasiswa_id
)
```

Artinya satu mahasiswa tidak boleh mempunyai dua record nilai untuk kelas yang sama.

---

# 6. Alur Data Utama

```text
Program Studi
      │
      ├──── Dosen
      │
      ├──── Mahasiswa
      │
      └──── Mata Kuliah
                  │
                  ▼
                Kelas
                  │
           ┌──────┴──────┐
           │              │
         Dosen        Mahasiswa
           │              │
           └──────┬───────┘
                  ▼
                Nilai
                  │
          ┌───────┼────────┐
          ▼       ▼        ▼
       Nilai    Grade    Status
       Akhir
```

---

# 7. Catatan Desain

Struktur ERD ini sengaja dibuat mengikuti entitas yang secara eksplisit tercantum pada roadmap: `users`, `program_studis`, `dosens`, `mahasiswas`, `mata_kuliahs`, `kelas`, dan `nilais`.

Roadmap belum mendefinisikan tabel khusus untuk:

* tahun akademik;
* semester;
* enrollment/peserta kelas;
* bobot komponen nilai;
* grade configuration;
* status configuration.

Karena itu entitas tersebut **tidak saya tambahkan sebagai tabel wajib** pada ERD versi 1.0 agar desain tetap sesuai dengan sumber roadmap.

Namun, apabila SIPENMA nantinya dikembangkan lebih besar, tabel `kelas_mahasiswa`, `tahun_akademiks`, dan `komponen_nilais` dapat dipertimbangkan sebagai pengembangan tahap berikutnya.
