# PKM Center - Dokumentasi Akun Login

## Database Setup

Database telah berhasil dibuat dengan struktur:

-   Tabel: roles, users, kelompoks, kelompok_user
-   Relasi: User belongsTo Role, User hasMany/belongsToMany Kelompok

## Akun Login yang Tersedia

### 1. Admin

-   **Email**: admin@pkm.ac.id
-   **Password**: admin123
-   **Role**: Administrator
-   **Akses**: Manajemen user, pengaturan sistem

### 2. Dekan

-   **Email**: dekan@ft.ac.id
-   **Password**: dekan123
-   **Nama**: Dr. Ahmad Fauzi, M.Kom
-   **NIDN**: 0401068901
-   **Role**: Dekan
-   **Akses**: Persetujuan akhir proposal, statistik fakultas

### 3. Kaprodi Teknik Informatika

-   **Email**: kaprodi.ti@ft.ac.id
-   **Password**: kaprodi123
-   **Nama**: Dr. Siti Nurhaliza, M.T
-   **NIDN**: 0402078902
-   **Program Studi**: Teknik Informatika
-   **Role**: Kaprodi
-   **Akses**: Verifikasi proposal prodi, laporan PKM

### 4. Kaprodi Sistem Informasi

-   **Email**: kaprodi.si@ft.ac.id
-   **Password**: kaprodi123
-   **Nama**: Dr. Budi Santoso, M.Kom
-   **NIDN**: 0403088903
-   **Program Studi**: Sistem Informasi
-   **Role**: Kaprodi

### 5. Dosen (4 Akun)

#### Dosen 1

-   **Email**: rina.wati@ft.ac.id
-   **Password**: dosen123
-   **Nama**: Dr. Rina Wati, M.Kom
-   **NIDN**: 0404098904
-   **Program Studi**: Teknik Informatika

#### Dosen 2

-   **Email**: rizki.pratama@ft.ac.id
-   **Password**: dosen123
-   **Nama**: M. Rizki Pratama, M.T
-   **NIDN**: 0405108905
-   **Program Studi**: Teknik Informatika

#### Dosen 3

-   **Email**: fitri.handayani@ft.ac.id
-   **Password**: dosen123
-   **Nama**: Dra. Fitri Handayani, M.Si
-   **NIDN**: 0406118906
-   **Program Studi**: Sistem Informasi

#### Dosen 4

-   **Email**: agus.setiawan@ft.ac.id
-   **Password**: dosen123
-   **Nama**: Agus Setiawan, M.Kom
-   **NIDN**: 0407128907
-   **Program Studi**: Teknik Informatika

### 6. Mahasiswa (6 Akun)

#### Mahasiswa 1

-   **Email**: andi.wijaya@student.ac.id
-   **Password**: mahasiswa123
-   **Nama**: Andi Wijaya
-   **NIM**: 2021001
-   **Program Studi**: Teknik Informatika

#### Mahasiswa 2

-   **Email**: dewi.lestari@student.ac.id
-   **Password**: mahasiswa123
-   **Nama**: Dewi Lestari
-   **NIM**: 2021002
-   **Program Studi**: Teknik Informatika

#### Mahasiswa 3

-   **Email**: raka.permana@student.ac.id
-   **Password**: mahasiswa123
-   **Nama**: Raka Permana
-   **NIM**: 2021003
-   **Program Studi**: Teknik Informatika

#### Mahasiswa 4

-   **Email**: maya.sari@student.ac.id
-   **Password**: mahasiswa123
-   **Nama**: Maya Sari
-   **NIM**: 2021004
-   **Program Studi**: Sistem Informasi

#### Mahasiswa 5

-   **Email**: faisal.rahman@student.ac.id
-   **Password**: mahasiswa123
-   **Nama**: Faisal Rahman
-   **NIM**: 2021005
-   **Program Studi**: Sistem Informasi

#### Mahasiswa 6

-   **Email**: sinta.maharani@student.ac.id
-   **Password**: mahasiswa123
-   **Nama**: Sinta Maharani
-   **NIM**: 2021006
-   **Program Studi**: Sistem Informasi

## Cara Login

1. Buka browser dan akses: http://127.0.0.1:8000
2. Klik tombol "Login" di navigation menu
3. Masukkan email dan password sesuai role yang diinginkan
4. Setelah login, sistem akan redirect ke dashboard sesuai role

## Fitur Dashboard Per Role

### Dashboard Mahasiswa

-   Melihat profil mahasiswa (NIM, Prodi)
-   Kelompok PKM yang terdaftar
-   Pengajuan PKM sebagai ketua
-   Buat kelompok baru

### Dashboard Dosen

-   Profil dosen (NIDN, Prodi)
-   Kelompok bimbingan
-   Review proposal PKM
-   Monitoring progress mahasiswa

### Dashboard Kaprodi

-   Verifikasi proposal dari program studi
-   Laporan dan statistik PKM
-   Mengelola dosen pembimbing

### Dashboard Dekan

-   Persetujuan akhir proposal PKM
-   Statistik tingkat fakultas
-   Kebijakan PKM

### Dashboard Admin

-   Manajemen user (CRUD)
-   Pengaturan sistem
-   Log aktivitas
-   Backup database

## Database Configuration

File `.env` sudah dikonfigurasi untuk MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pkm_center
DB_USERNAME=root
DB_PASSWORD=
```

## Model & Relationships

### User Model

-   belongsTo: Role
-   belongsToMany: Kelompok (through kelompok_user)
-   hasMany: Kelompok (as ketua)
-   hasMany: Kelompok (as dosen pembimbing)

### Role Model

-   hasMany: User

### Kelompok Model

-   belongsTo: User (ketua)
-   belongsTo: User (dosen_pembimbing)
-   belongsToMany: User (anggota)

## Helper Methods di User Model

-   `isMahasiswa()` - cek apakah user adalah mahasiswa
-   `isDosen()` - cek apakah user adalah dosen
-   `isKaprodi()` - cek apakah user adalah kaprodi
-   `isDekan()` - cek apakah user adalah dekan
-   `isAdmin()` - cek apakah user adalah admin

## Testing

Server sedang berjalan di: http://127.0.0.1:8000

Silakan coba login dengan salah satu akun di atas untuk menguji sistem!
