# Dokumentasi Sistem Aplikasi Siforma SD

Siforma SD adalah sistem informasi presensi dan pelaporan harian berbasis web yang dirancang khusus untuk memenuhi kebutuhan administrasi dan pemantauan kinerja guru di tingkat Sekolah Dasar (SD). Sistem ini dilengkapi dengan fitur geolokasi (GPS) untuk keamanan presensi serta modul pelaporan yang terstruktur.

## Daftar Modul Utama & Fitur

### 1. Modul Autentikasi (Login & Logout)
Sistem memiliki kontrol akses yang aman dengan berbagai tingkat kewenangan (Role):
- **Admin**: Akses penuh ke seluruh fitur, pengaturan aplikasi, dan manajemen pengguna.
- **Kepala Sekolah (Principal)**: Fokus pada pemantauan (viewing) data presensi guru, statistik kehadiran, dan memberikan *review* / persetujuan pada Laporan Harian guru.
- **Guru (Teacher)**: Akses terbatas untuk melakukan absensi (Check-in / Check-out) dan menyusun Laporan Harian pembelajaran kelasnya masing-masing.

### 2. Modul Dashboard
Halaman beranda yang memberikan informasi ringkas secara *real-time*:
- **Statistik Cepat**: Menampilkan total guru, persentase kehadiran hari ini, dan jumlah laporan harian yang menunggu *review*.
- **Akses Cepat (Shortcut)**: Memudahkan pengguna melompat ke fitur yang paling sering digunakan sesuai perannya.

### 3. Modul Master Data
Modul pengelolaan data inti sistem yang hanya bisa dikelola oleh Admin:
- **Manajemen Guru**: Menambah, mengubah, atau menonaktifkan data profil guru beserta mata pelajaran yang diampunya.
- **Manajemen Kelas**: Mengelola daftar ruang kelas yang aktif di sekolah. Data ini terhubung dengan *dropdown* pilihan saat guru membuat laporan.

### 4. Modul Presensi (Kehadiran Guru)
Modul utama untuk memantau jam kerja guru secara digital:
- **Validasi Berbasis Lokasi (GPS & Geofencing)**: Memastikan guru benar-benar berada di lingkungan sekolah saat melakukan absensi masuk (*Check-in*) dan pulang (*Check-out*) dengan membandingkan titik koordinat GPS dari perangkat guru terhadap koordinat sekolah.
- **Deteksi Keterlambatan Otomatis**: Secara otomatis melabeli guru dengan status "Terlambat" jika waktu klik *Check-in* melewati batas jam masuk baku, dan status "Tidak tepat waktu" jika *Check-out* lebih cepat dari jam pulang baku.
- **Statistik & Rekapitulasi**: Fitur (khusus Admin & Kepala Sekolah) untuk merekap tingkat absensi guru secara keseluruhan dalam bentuk data tabel bulanan.

### 5. Modul Laporan Harian
Modul pelaporan jurnal mengajar guru untuk standarisasi administratif:
- **Input Jurnal**: Guru wajib mengisi tujuan pembelajaran, materi, metode, kehadiran siswa, dan tugas yang diberikan setiap harinya.
- **Lampiran File**: Kemampuan mengunggah lampiran *file* materi (seperti RPP, presentasi, PDF, dsb) untuk arsip pembelajaran.
- **Alur Persetujuan (Approval Flow)**: Laporan yang di-submit oleh guru akan dikirimkan ke Kepala Sekolah. Kepala Sekolah kemudian bisa memberikan ulasan (Review) dan persetujuan secara sistem.

### 6. Modul Pengaturan Sistem (Settings)
Halaman *backend* bagi Admin untuk mengendalikan perilaku dan parameter sistem secara dinamis:
- **Pengaturan Jam Presensi**: Bebas mendefinisikan jam batas masuk (Jam Terlambat) dan jam pulang sekolah.
- **Pengaturan Koordinat (Geolokasi)**: Dilengkapi dengan antarmuka yang sangat *user-friendly* dengan kemampuan **"Ambil Lokasi Saat Ini"** untuk menetapkan *Latitude*, *Longitude* titik nol sekolah, serta pengaturan radius keamanan absensi (contoh: 50 - 100 meter).

### 7. Panduan Menjalankan Aplikasi (Local Development)
Agar seluruh fitur aplikasi berjalan normal (termasuk fitur Ekspor Background), Anda **tidak disarankan** hanya menggunakan perintah `php artisan serve`.

Gunakan perintah bawaan Laravel 11 terpadu berikut pada terminal utama Anda:
```bash
composer run dev
# ATAU
npm run dev
```
**Mengapa menggunakan perintah tersebut?**
Perintah di atas akan secara otomatis dan bersamaan menjalankan 3 *service* penting:
1. **HTTP Server** (`php artisan serve`) untuk menjalankan website.
2. **Queue Listener** (`php artisan queue:listen`) untuk memproses pekerjaan di latar belakang seperti **Ekspor Excel dan PDF**, agar statusnya tidak nyangkut di "Menunggu...".
3. **Vite Server** (`npm run dev`) untuk melakukan *hot-reloading* pada aset CSS dan Javascript modern (jika digunakan).

> [!WARNING]
> Jika Anda hanya menggunakan `php artisan serve`, maka tugas-tugas *background* seperti *Export Data* tidak akan pernah diproses dan akan diam berstatus "*Menunggu...*" selamanya.

---
*Dokumen ini diperbarui secara berkala oleh sistem Siforma SD.*
