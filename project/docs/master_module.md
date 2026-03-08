# Dokumentasi Modul Master

Dokumentasi ini menjelaskan fungsionalitas dan cara penggunaan fitur-fitur yang terdapat di dalam menu **Master** pada Sistem Informasi IDEP.

## 👥 Manajemen Pengguna (Users)

Sub-modul **Users** digunakan untuk mengelola data pengguna yang memiliki akses ke dalam sistem. Berikut adalah panduan penggunaan fitur-fitur pada modul Users.

### 1. Melihat Daftar Pengguna (List Users)

**URL:** `/master/users`

Pada halaman utama modul Users, Anda akan melihat tabel daftar pengguna dengan kolom-kolom berikut:

- **No:** Nomor urut.
- **Nama:** Nama lengkap pengguna.
- **Username:** Nama pengguna untuk login.
- **Email:** Alamat surel pengguna.
- **Jabatan:** Posisi atau jabatan pengguna di organisasi.
- **Tingkat Akses (Roles):** Peran klasifikasi hak akses (misal: Admin, Staf, dll).
- **Status:** Status keaktifan akun (Centang/Aktif atau Silang/Tidak Aktif).
- **Aksi:** Tombol-tombol untuk melihat detail atau mengubah data.

**Fitur Tabel (DataTables):**

- **Pencarian & Pagination:** Anda dapat mencari pengguna spesifik melalui kolom pencarian di kanan atas tabel dan mengatur jumlah baris per halaman (5, 10, 25, 50, 100, 200).
- **Export Data:** Di kiri atas tabel terdapat tombol Export untuk menyalin, mencetak (Print), atau mengunduh data dalam format Excel maupun PDF.
- **Visibilitas Kolom (Colvis):** Tombol dengan ikon mata digunakan untuk mengatur tampilan kolom-kolom tabel.

### 2. Menambahkan Pengguna Baru (Create User)

Untuk menambahkan pengguna baru, klik pada bagian **"Create User"** (panel yang dapat di-expand) di atas tabel daftar pengguna.

**Form Isian:**

1. **Nama (Wajib):** Isi dengan nama lengkap pengguna (minimal 3 karakter).
2. **Username (Wajib):** Isi dengan nama pengguna unik yang akan digunakan untuk login (minimal 5 karakter). Sistem akan melakukan pengecekan ketersediaan username secara otomatis.
3. **Email (Wajib):** Isi dengan email valid. Sistem akan mengecek agar email tidak duplikat.
4. **Jabatan (Wajib):** Pilih jabatan pengguna dari menu dropdown yang tersedia.
5. **Password (Wajib):** Buat kata sandi untuk pengguna.
6. **Retype Password (Wajib):** Ketik ulang kata sandi untuk konfirmasi keamanan.
7. **Tingkat Akses / Roles (Wajib):** Pilih satu atau beberapa role untuk mengatur hak izin pengguna di aplikasi.
8. **Status Aktif:** Secara default di-centang (akun aktif). Hilangkan centang jika perlu menjadikan akun non-aktif semenjak awal.

Klik tombol **"Save"** untuk menyimpan pengguna baru. Form ini dilindungi oleh proses validasi otomatis (Real-time).

### 3. Melihat Detail Pengguna (View User)

Pada tabel daftar pengguna, temukan baris user lalu klik tombol **View** (ikon mata biru) pada kolom aksi.
Sistem akan menampilkan pop-up (Modal) yang berisi ringkasan data pengguna, meliputi Nama, Username, Email, Jabatan, Tingkat Akses (Roles), dan Status Akun.

### 4. Mengubah Data Pengguna (Edit User)

Pada tabel, klik tombol **Edit** (ikon pensil hijau/kuning) pada kolom aksi. Modal form Edit akan muncul.

**Hal yang perlu diperhatikan saat meng-edit:**

- Data Nama, Email, Jabatan, Role, Status, dan Password dapat diperbarui.
- **Username:** Kolom ini hanya bisa diisi jika nilainya sebelumnya kosong. Apabila username lama sudah ada, sistem akan menguncinya (Readonly) agar tidak dapat diubah-ubah.
- **Password:** Kosongkan form isian Password dan Retype Password jika Anda **tidak** ingin mengganti sandi yang sudah ada. Isi form tersebut hanya jika akan ada penyetelan ulang sandi (Password Reset).
- Pengecekan username dan email duplikat tetap berjalan.

Klik **"Update"** untuk menerapkan pembaruan data pengguna.

---

**Catatan Teknis:**
Setiap pengolahan form maupun pemuatan data tabel dilakukan secara asinkronus dengan AJAX. Data pengguna diproses melalui endpoint `users.api` sehingga tabel akan memuat data secara efektif, tanpa butuh menyengarkan ulang (reload) antarmuka program.

## 🌍 Manajemen Wilayah (Provinsi - Dusun)

Sistem Informasi IDEP menggunakan struktur hierarki wilayah administratif Indonesia yang berjenjang: **Provinsi > Kabupaten/Kota > Kecamatan > Desa/Kelurahan > Dusun**.
Karena sifatnya yang berjenjang, Anda harus memastikan data tingkat atas (misalnya Provinsi) sudah tersedia sebelum membuat data tingkat di bawahnya (misalnya Kabupaten).

Semua sub-modul wilayah menggunakan antarmuka DataTables standar dengan form tambah (Create) di bagian atas dan aksi View/Edit di setiap baris data.

### 1. Provinsi

**URL:** `/master/provinsi`
**Form Isian Tambah Data:**

- **Kode:** Masukkan kode resmi provinsi (misal: `51` untuk Bali).
- **Nama:** Nama provinsi (misal: `BALI`).
- **Status (Aktif):** Centang untuk mengaktifkan referensi provinsi ini.

### 2. Kabupaten / Kota

**URL:** `/master/kabupaten`
**Form Isian Tambah Data:**

- **Pilih Provinsi:** Dropdown untuk memilih provinsi induk.
- **Kode:** Masukkan kode kabupaten/kota (misal: `51.71`).
- **Nama:** Nama kabupaten atau kota.
- **Tipe:** Pilih apakah entitas ini adalah `Kabupaten` atau `Kota`.

### 3. Kecamatan

**URL:** `/master/kecamatan`
**Form Isian Tambah Data:**

- **Pilih Provinsi:** Dropdown provinsi.
- **Pilih Kabupaten:** Dropdown kabupaten (Opsi akan muncul setelah Provinsi dipilih).
- **Kode:** Kode kecamatan.
- **Nama:** Nama kecamatan.

### 4. Desa / Kelurahan

**URL:** `/master/desa`
**Form Isian Tambah Data:**

- **Pilih Provinsi, Kabupaten, & Kecamatan:** Pilih secara berurutan dari atas ke bawah.
- **Kode:** Kode desa (Format standar: `XX.XX.XX.XXXX`).
- **Nama:** Nama desa.

### 5. Dusun

**URL:** `/master/dusun`
Tingkatan administratif terkecil yang didata dalam sistem.
**Form Isian Tambah Data:**

- **Pilih Hierarki Wilayah Induk:** Pilih berurutan mulai dari Provinsi, Kabupaten, Kecamatan, hingga Desa.
- **Kode:** Kode Dusun (16 digit angka).
- **Nama:** Nama Dusun (Hanya boleh diawali huruf, tidak boleh ada karakter ganda seperti spasi beruntun).
- **Kode Pos:** Masukkan 5 digit angka kode pos wilayah bersangkutan.

## 🔑 Manajemen Peran (Roles) & Jabatan

### 1. Role Sistem (Hak Akses)

**URL:** `/master/role`
Modul ini mengatur jenis peran apa saja yang ada di lingkup sistem (Sistem Role) dan hak apa saja yang bisa diakses mereka.
**Form Isian Tambah Data:**

- **Nama Role:** Jabatan dalam sistem (misalnya: `Admin`, `Super Admin`, `Staff`).
- **Permissions:** Opsi dropdown multi-select untuk memberikan izin-izin spesifik (misal: `user_create`, `user_edit_all`, dll) kepada peran ini.
- **Status (Aktif):** Centang untuk mengaktifkan role.

_(Pastikan Role sudah didefinisikan sebelum membuat User baru)._

### 2. Jabatan (MJabatan)

**URL:** `/master/mjabatan`
Modul ini mengatur referensi pos jabatan dalam struktur kelembagaan organisasi (bukan sekedar izin akses aplikasi).
**Form Isian Tambah Data:**

- **Nama Jabatan:** Nama posisi fungsional/struktural (misal: `Direktur`, `Ketua Program`).
- **Deskripsi:** Detail mengenai jabatan tersebut.
- **Status (Aktif):** Centang untuk mengaktifkan jabatan.

## 🎯 Manajemen Data Program (Bantuan, SDG, dan Sasaran)

Modul-modul ini esensial untuk mengkategorikan bentuk program/bantuan, kaitannya dengan pencapaian target global, dan kelompok yang disasar.

### 1. Jenis Bantuan

**URL:** `/master/jenisbantuan`
Berfungsi untuk mendata ragam tipe bantuan yang diberikan (contoh: _Bantuan Pangan_, _Bantuan Tunai_, _Pelatihan_).
**Form Isian Tambah Data:**

- **Nama Jenis Bantuan:** Deskripsi/nama jenis bantuan.
- **Status (Aktif):** Centang untuk mengaktifkan referensi bantuan ini.

### 2. Kaitan SDG (Sustainable Development Goals)

**URL:** `/master/kaitan_sdg`
Digunakan untuk memetakan bagaimana kegiatan/program yang berjalan selaras dengan agenda SDGs spesifik (contoh: _1. No Poverty_, _2. Zero Hunger_).
**Form Isian Tambah Data:**

- **Nama Kaitan SDG:** Nama/Kategori SDG.
- **Status (Aktif):** Centang untuk mengaktifkan relasi SDG.

### 3. Kelompok Marjinal

**URL:** `/master/kelompokmarjinal`
Data sasaran kelompok rentan/marjinal (contoh: _Disabilitas_, _Lansia_, _Perempuan Kepala Keluarga_).
**Form Isian Tambah Data:**

- **Nama Kelompok Marjinal:** Kelompok rentan spesifik.
- **Status (Aktif):** Centang untuk mengaktifkan.

## 🤝 Manajemen Kemitraan (Pendonor & Partner)

### 1. Kategori Pendonor

**URL:** `/master/kategoripendonor`
Mengklasifikasikan pengelompokan secara luas tipe entitas pemberi dana (contoh: _Pemerintah_, _NGO Internasional_, _Sektor Swasta_).
**Form Isian Tambah Data:**

- **Nama Kategori Pendonor:** Nama klasifikasi.
- **Status (Aktif):** Centang untuk mengaktifkan kategori.

### 2. Tipe Pendonor (MPendonor)

**URL:** `/master/mpendonor`
Buku daftar / direktori para pendonor spesifik. Basis datanya menginduk pada Kategori Pendonor.
**Form Isian Tambah Data:**

- **Kategori Pendonor:** Dropdown untuk memilih kategori pendonor.
- **Nama:** Nama instansi/individu pendonor.
- **PIC (Person In Charge):** Nama kontak penanggung jawab dari pihak pendonor.
- **Email:** Email kontak pendonor.
- **No. Telepon:** Nomor kontak pendonor.
- **Status (Aktif):** Centang untuk mengaktifkan data pendonor.

### 3. Partner

**URL:** `/master/partner`
Data lembaga/organisasi mitra yang diajak bekerja sama dalam pelaksanaan kegiatan di lapangan.
**Form Isian Tambah Data:**

- **Nama Partner:** Nama entitas mitra.
- **Status (Aktif):** Centang untuk mengaktifkan referensi mitra.

### 4. Peran

**URL:** `/master/peran`
Menentukan jenis andil atau kapasitas keterlibatan suatu pihak (baik internal/mitra/beneficiary) di dalam program (contoh: _Fasilitator_, _Peserta_, _Narasumber_).
**Form Isian Tambah Data:**

- **Nama Peran:** Nama jabatan peran pelaksanaan di lapangan.
- **Status (Aktif):** Centang untuk mengaktifkan opsi peran.

## 📏 Manajemen Pengukuran Acuan Laporan

### 1. Satuan

**URL:** `/master/satuan`
Referensi besaran ukuran (units) yang digunakan dalam pendataan sistem logistik atau perhitungan laporan realisasi (contoh: _Paket_, _Pcs_, _Orang_, _Kg_).
**Form Isian Tambah Data:**

- **Nama Satuan:** Nama ukuran (misalkan: `Orang`, `Unit`).
- **Status (Aktif):** Centang untuk mengaktifkan referensi satuan ukuran.

### 2. Target Rencana Strategis (Target Reinstra)

**URL:** `/master/target_reinstra`
Menjadi acuan pencapaian rencana kerja internal yayasan/organisasi dalam suatu periode.
**Form Isian Tambah Data:**

- **Nama Target Reinstra:** Uraian target (misal: _Pemberdayaan 1000 Petani_).
- **Status (Aktif):** Centang untuk mengaktifkan dokumen acuan.
