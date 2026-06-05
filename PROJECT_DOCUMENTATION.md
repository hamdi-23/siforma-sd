# Siforma SD - Sistem Manajemen Sekolah Dasar

Aplikasi web Laravel untuk manajemen sekolah dasar yang fokus pada:
1. **Presensi Guru** - Tracking kehadiran guru harian
2. **Laporan/Jurnal Pembelajaran Harian Guru** - Dokumentasi pembelajaran harian
3. **Rekap Bulanan Otomatis** - Rekapitulasi laporan bulanan secara otomatis
4. **Monitoring Kepala Sekolah/Admin** - Dashboard monitoring & pelaporan

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- Laravel 11
- MySQL/SQLite

## Installation

### 1. Jalankan di project folder
```bash
cd c:\laragon\www\siforma-sd\siforma-sd
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database setup
```bash
php artisan migrate
php artisan db:seed
```

### 5. Build assets
```bash
npm run dev
```

### 6. Jalankan server
```bash
php artisan serve
```

Server akan berjalan di http://localhost:8000

## Test Credentials

Setelah menjalankan seeder, gunakan credentials berikut untuk login:

### Admin
- **Email**: admin@example.com
- **Password**: password (default factory password)

### Kepala Sekolah (Principal)
- **Email**: principal@example.com
- **Password**: password

### Guru (Teacher)
- **Email**: guru1@example.com hingga guru5@example.com
- **Password**: password

## Project Structure

### Database Schema

#### 1. **users** (Authentication & Authorization)
```
- id (Primary Key)
- name
- email (unique)
- password
- role (enum: 'admin', 'teacher', 'principal')
- remember_token
- email_verified_at
- timestamps
```

#### 2. **teachers** (Data Guru)
```
- id (Primary Key)
- user_id (FK to users)
- nip (unique, Nomor Induk Pegawai)
- subject (Mata pelajaran)
- phone
- address
- status (enum: 'active', 'inactive', 'on_leave')
- hire_date
- timestamps
```

#### 3. **attendances** (Presensi Harian)
```
- id (Primary Key)
- teacher_id (FK to teachers)
- date
- check_in_time
- check_out_time
- status (enum: 'present', 'late', 'absent', 'sick', 'leave')
- notes
- timestamps
- unique constraint: (teacher_id, date)
```

#### 4. **daily_reports** (Laporan Pembelajaran Harian)
```
- id (Primary Key)
- teacher_id (FK to teachers)
- report_date
- class (kelas yang diajar)
- learning_objectives (tujuan pembelajaran)
- learning_materials (materi pembelajaran)
- teaching_methods (metode pembelajaran)
- student_response (respons siswa)
- assignments_given (tugas yang diberikan)
- attendance_count (jumlah siswa hadir)
- total_students (total siswa)
- notes
- status (enum: 'draft', 'submitted', 'reviewed')
- submitted_at
- timestamps
- unique constraint: (teacher_id, report_date)
```

#### 5. **monthly_recaps** (Rekap Bulanan)
```
- id (Primary Key)
- teacher_id (FK to teachers)
- year
- month
- total_days (total hari kerja)
- present_days (hari hadir)
- absent_days (hari absen)
- late_days (hari terlambat)
- sick_days (hari sakit)
- leave_days (hari cuti)
- total_reports_submitted (laporan dikirim)
- total_reports_reviewed (laporan di-review)
- attendance_percentage
- summary
- generated_at
- timestamps
- unique constraint: (teacher_id, year, month)
```

## Features

### 1. Presensi Guru (Attendance Module)
- **URL**: `/attendance`
- **Features**:
  - Guru dapat mencatat presensi harian (check-in/check-out)
  - Admin/Principal dapat melihat presensi semua guru
  - Status: present, late, absent, sick, leave
  - Statistik bulanan kehadiran
  
**Routes**:
- `GET /attendance` - List presensi
- `POST /attendance` - Buat presensi baru
- `GET /attendance/{id}` - Lihat detail presensi
- `PUT /attendance/{id}` - Update presensi
- `GET /attendance/statistics` - Statistik kehadiran

### 2. Laporan Harian Guru (Daily Report Module)
- **URL**: `/daily-report`
- **Features**:
  - Guru dapat membuat laporan pembelajaran harian
  - Laporan dapat disimpan sebagai draft atau dikirim
  - Admin/Principal dapat me-review laporan
  - Pencatatan: tujuan, materi, metode, respons siswa, tugas, kehadiran kelas
  
**Routes**:
- `GET /daily-report` - List laporan
- `POST /daily-report` - Buat laporan baru
- `GET /daily-report/{id}` - Lihat detail laporan
- `PUT /daily-report/{id}` - Update laporan
- `POST /daily-report/{id}/review` - Review laporan (admin/principal)

### 3. Rekap Bulanan Otomatis (Monthly Recap Module)
- **URL**: `/monthly-recap`
- **Features**:
  - Generate rekap bulanan secara otomatis
  - Rekapitulasi: kehadiran, presensi, laporan
  - Persentase kehadiran otomatis
  - Summary otomatis
  
**Routes**:
- `GET /monthly-recap` - List rekap
- `GET /monthly-recap/{id}` - Lihat detail rekap
- `POST /monthly-recap/generate/{teacher_id}/{year}/{month}` - Generate single recap
- `POST /monthly-recap/generate-all/{year}/{month}` - Generate all recaps

### 4. Dashboard & Monitoring (Dashboard Module)
- **URL**: `/dashboard`
- **Fitur Guru**:
  - Status presensi hari ini
  - Presensi terakhir 7 hari
  - Statistik bulan ini (hadir, absen, terlambat)
  - Laporan terbaru yang dibuat
  - Jumlah draft laporan
  - Rekap bulanan terakhir

- **Fitur Admin/Principal**:
  - Statistik guru (total active, on leave)
  - Summary presensi hari ini (hadir, absen, terlambat, sakit, cuti)
  - Statistik laporan (total, submitted, reviewed)
  - Guru dengan presensi terendah bulan ini
  - Status recap bulan ini
  - Guru yang belum submit laporan hari ini

## Console Commands

### Generate Monthly Recaps
Untuk menggenerate monthly recap secara manual:

```bash
# Generate recap untuk bulan sebelumnya
php artisan recap:generate-monthly

# Generate recap untuk bulan/tahun tertentu
php artisan recap:generate-monthly --year=2026 --month=1
```

## User Roles & Permissions

### 1. **Admin**
- Akses penuh ke semua fitur
- Dapat melihat semua presensi guru
- Dapat melihat semua laporan guru
- Dapat generate monthly recap
- Dapat review laporan harian

### 2. **Principal (Kepala Sekolah)**
- Akses penuh monitoring
- Dapat melihat semua presensi guru
- Dapat melihat semua laporan guru
- Dapat generate monthly recap
- Dapat review laporan harian
- Tidak dapat mengelola user

### 3. **Teacher (Guru)**
- Dapat mencatat presensi sendiri
- Dapat membuat laporan pembelajaran harian
- Hanya melihat data pribadi (presensi & laporan sendiri)
- Dapat melihat rekap bulanan pribadi

## Models & Relationships

```
User
├── hasOne: Teacher
├── hasMany: Sessions

Teacher
├── belongsTo: User
├── hasMany: Attendances
├── hasMany: DailyReports
├── hasMany: MonthlyRecaps

Attendance
├── belongsTo: Teacher

DailyReport
├── belongsTo: Teacher

MonthlyRecap
├── belongsTo: Teacher
```

## Available Scopes & Methods

### Attendance Model
```php
$attendance->byDate($date) // Filter by date
$attendance->byDateRange($startDate, $endDate) // Filter by date range
$attendance->present() // Only present/late
$attendance->absent() // Only absent
```

### DailyReport Model
```php
$report->byDate($date) // Filter by date
$report->byDateRange($startDate, $endDate) // Filter by date range
$report->submitted() // Only submitted reports
$report->reviewed() // Only reviewed reports
```

### MonthlyRecap Model
```php
$recap->byYearMonth($year, $month) // Filter by year & month
$recap->byYear($year) // Filter by year
```

## Security Features

- Middleware authentication untuk semua routes
- Role-based access control (RBAC)
- Guru hanya dapat mengakses data pribadi
- Admin/Principal dapat mengakses semua data
- Model-level authorization checks

## File Structure

```
app/
├── Console/Commands/
│   └── GenerateMonthlyRecaps.php
├── Http/
│   ├── Controllers/
│   │   ├── AttendanceController.php
│   │   ├── DailyReportController.php
│   │   ├── MonthlyRecapController.php
│   │   └── DashboardController.php
│   └── Middleware/
│       └── AdminOrPrincipal.php
├── Models/
│   ├── User.php
│   ├── Teacher.php
│   ├── Attendance.php
│   ├── DailyReport.php
│   └── MonthlyRecap.php

database/
├── migrations/
│   ├── 2026_01_01_000003_add_role_to_users_table.php
│   ├── 2026_01_02_000000_create_teachers_table.php
│   ├── 2026_01_03_000000_create_attendances_table.php
│   ├── 2026_01_04_000000_create_daily_reports_table.php
│   └── 2026_01_05_000000_create_monthly_recaps_table.php
├── factories/
│   ├── TeacherFactory.php
│   ├── AttendanceFactory.php
│   └── DailyReportFactory.php
└── seeders/
    └── DatabaseSeeder.php

routes/
└── web.php
```

## Development Tips

1. **Untuk Testing Presensi**:
   ```bash
   php artisan tinker
   $teacher = \App\Models\Teacher::first();
   $teacher->attendances()->create(['date' => today(), 'status' => 'present', 'check_in_time' => now()->format('H:i:s'), 'check_out_time' => now()->format('H:i:s')]);
   ```

2. **Untuk Reset Database**:
   ```bash
   php artisan migrate:refresh --seed
   ```

3. **Untuk Generate Recap Manual**:
   ```bash
   php artisan recap:generate-monthly --year=2026 --month=1
   ```

## Next Steps - Frontend Development

Views yang perlu dibuat untuk menyempurnakan aplikasi:

### Authentication Views
- `resources/views/auth/login.blade.php` - Login page
- `resources/views/auth/register.blade.php` - Register page

### Dashboard Views
- `resources/views/dashboard/teacher.blade.php` - Teacher dashboard
- `resources/views/dashboard/admin.blade.php` - Admin/Principal dashboard

### Attendance Views
- `resources/views/attendance/index.blade.php` - List presensi
- `resources/views/attendance/create.blade.php` - Buat presensi
- `resources/views/attendance/edit.blade.php` - Edit presensi
- `resources/views/attendance/show.blade.php` - Detail presensi
- `resources/views/attendance/statistics.blade.php` - Statistik

### Daily Report Views
- `resources/views/daily-report/index.blade.php` - List laporan
- `resources/views/daily-report/create.blade.php` - Buat laporan
- `resources/views/daily-report/edit.blade.php` - Edit laporan
- `resources/views/daily-report/show.blade.php` - Detail laporan

### Monthly Recap Views
- `resources/views/monthly-recap/index.blade.php` - List rekap
- `resources/views/monthly-recap/show.blade.php` - Detail rekap

## Sistem Telah Diimplementasikan

✅ Database schema lengkap dengan 5 tabel utama
✅ Model-model dengan relationships dan scopes
✅ 4 Controllers dengan business logic lengkap
✅ Routes terstruktur dengan middleware role-based
✅ Middleware untuk access control (admin/principal)
✅ Factories untuk testing & seeding
✅ DatabaseSeeder dengan data test
✅ Console Command untuk generate monthly recap
✅ User roles & permissions (admin, teacher, principal)
✅ Authentication & authorization system

## API Ready

Semua endpoints sudah siap untuk digunakan:
- Attendance management
- Daily report management
- Monthly recap generation
- Dashboard data retrieval

Tinggal membuat frontend Views menggunakan Blade template.
